<?php
/**
 * Date: 12.02.16
 * Time: 19:26
 */

namespace Exprating\ImportBundle\Command;


use AppBundle\Entity\Category;
use Exprating\ImportBundle\Entity\SiteProductRubrics;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->setName('import:import')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $entityManagerImport = $this->emImport;
        $repo = $entityManagerImport->getRepository('ExpratingImportBundle:SiteProductRubrics');
        $appEntityManager = $this->em;
        /** @var SiteProductRubrics[] $rubrics */
        $rubrics = $repo->createQueryBuilder('a')->where('a.parent is null')
            ->getQuery()->getResult();
        $slugify = $this->slugify;

        $recursiveFunction = function ($rubrics, Category $root = null) use (&$recursiveFunction, $slugify, $entityManagerImport, $appEntityManager) {
            /** @var SiteProductRubrics $rubric */
            foreach ($rubrics as $rubric) {
                $category = new Category();
                $slug = $slugify->slugify($rubric->getName());
                $category->setName($rubric->getName())
                    ->setSlug($slug);

                if ($root) {
                    $category->setParent($root)
                        ->setName($rubric->getName())
                        ->setSlug($root->getSlug() . '->' . $slug);
                }
                $appEntityManager->persist($category);
                if (count($rubric->getChildren())) {
                    $recursiveFunction($rubric->getChildren(), $category);
                }
            }
        };
        $recursiveFunction($rubrics, null);
        $appEntityManager->flush();
    }
} 