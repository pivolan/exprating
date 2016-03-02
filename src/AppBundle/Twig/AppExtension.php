<?php

/**
 * Date: 05.02.16
 * Time: 18:35.
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension
{
    const KEY_CATEGORIES = 'categories';
    const KEY_PEOPLE_GROUP = 'peopleGroup';
    /** @var  \Twig_Environment */
    protected $twig;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(\Twig_Environment $twig, EntityManager $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('breadcrumbs', [$this, 'breadcrumbs']),
        ];
    }

    /**
     * @param Category $category
     *
     * @return string
     */
    public function breadcrumbs($category, $peopleGroup)
    {
        /** @var Category[] $categories */
        $categories = $this->entityManager->getRepository('AppBundle:Category')->getPath($category);

        return $this->twig->render(
            'AppBundle:Extensions:breadcrumbs.html.twig',
            [
                self::KEY_CATEGORIES   => $categories,
                self::KEY_PEOPLE_GROUP => $peopleGroup,
            ]
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app_extension';
    }
}
