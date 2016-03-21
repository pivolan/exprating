<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use Exprating\ImportBundle\Entity\SiteProductRubrics;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportRubricsToCategoryCommand
 * @package Exprating\ImportBundle\Command
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class ImportRubricsToCategoryCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityManager
     */
    protected $emImport;

    /**
     * @var Slugify
     */
    protected $slugify;

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Slugify $slugify
     */
    public function setSlugify(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @param EntityManager $emImport
     */
    public function setEmImport(EntityManager $emImport)
    {
        $this->emImport = $emImport;
    }

    protected function configure()
    {
        $this
            ->setName('import:category')
            ->setDescription('Greet someone');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Достанем рубрики(категории) из базы импорта
        /** @var SiteProductRubrics[] $rubrics */
        $rubrics = $this->emImport
            ->getRepository('ExpratingImportBundle:SiteProductRubrics')
            ->findBy(['parent' => null]);

        $this->recursiveFunction($rubrics, null);
        $this->em->flush();
    }

    /**
     * Рекурсивная функция обхода  и копирования всего дерева.
     * На вход даем рубрики для импорта из базы Инфоскидки и родителя, если есть, к кому принадлежат категории
     * Передаем в качестве use для возможности использовать внутри функции внешние данные
     * САму функцию, slugify, менеджеры для нашей базы и базы импорта
     *
     * @param               $rubrics
     * @param Category|null $root
     *
     * @throws \Doctrine\ORM\ORMException
     */
    protected function recursiveFunction($rubrics, Category $root = null)
    {
        /** @var SiteProductRubrics $rubric */
        foreach ($rubrics as $rubric) {
            //Проходим по каждой категории в списке
            $category = new Category();
            $slug = $this->slugify->slugify($rubric->getName());
            $category->setName($rubric->getName())
                ->setSlug($slug.'-'.$rubric->getId());
            //Заполняем группу людей, в инфоскиде это просто поля с галочками для кого. У нас - связанная таблица
            if ($rubric->getShowchild()) {
                $category->addPeopleGroup($this->em->getReference(PeopleGroup::class, PeopleGroup::SLUG_CHILD));
            }
            if ($rubric->getShowwoman()) {
                $category->addPeopleGroup($this->em->getReference(PeopleGroup::class, PeopleGroup::SLUG_WOMAN));
            }
            if ($rubric->getShowman()) {
                $category->addPeopleGroup($this->em->getReference(PeopleGroup::class, PeopleGroup::SLUG_MAN));
            }
            if ($rubric->getShowall()) {
                $category->addPeopleGroup($this->em->getReference(PeopleGroup::class, PeopleGroup::SLUG_ALL));
            }
            //Если есть родитель, прописываем его
            if ($root) {
                $category->setParent($root);
            }

            $this->em->persist($category);
            //Если есть дети, запускаем функцию по кругу, рекурсивно
            if (count($rubric->getChildren())) {
                $this->recursiveFunction($rubric->getChildren(), $category);
            }
        }
    }
}
