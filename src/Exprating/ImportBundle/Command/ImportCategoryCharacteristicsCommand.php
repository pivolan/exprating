<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use Exprating\CharacteristicBundle\Entity\CategoryCharacteristic;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\ImportBundle\Entity\AliasItem;
use Exprating\ImportBundle\Entity\Categories;
use Exprating\ImportBundle\Entity\Item;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportItemCommand
 * @package Exprating\ImportBundle\Command
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class ImportCategoryCharacteristicsCommand extends ContainerAwareCommand
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
     * @param EntityManager $emImport
     */
    public function setEmImport(EntityManager $emImport)
    {
        $this->emImport = $emImport;
    }

    /**
     * @param Slugify $slugify
     */
    public function setSlugify(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    protected function configure()
    {
        $this
            ->setName('import:category_characteristics')
            ->setDescription('Greet someone');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Categories[] $categories */
        $categories = $this->emImport->getRepository('ExpratingImportBundle:Categories')->findAll();
        foreach ($categories as $categoryImport) {
            $categoryExpratingId = $categoryImport->getAliasCategory()->getCategoryExpratingId();
            $category = $this->em->getRepository('AppBundle:Category')->find($categoryExpratingId);
            if ($category) {
                foreach ($categoryImport->getParameters() as $parameter) {
                    $characterstic = $this
                        ->em
                        ->getRepository('CharacteristicBundle:Characteristic')
                        ->findOneBy(['name'=>$parameter->getGroupName()]);
                    if (!$characterstic) {
                        $characterstic = (new Characteristic())
                            ->setName($parameter->getGroupName())
                            ->setLabel($parameter->getGroupName())
                            ->setSlug($this->slugify->slugify($parameter->getGroupName()));
                    }
                    $categoryCharacterstic = (new CategoryCharacteristic())
                        ->setCategory($category)
                        ->setCharacteristic($characterstic);
                    $this->em->persist($characterstic);
                    $this->em->persist($categoryCharacterstic);
                    $category->addCategoryCharacteristic($categoryCharacterstic);
                }
            }
        }
        $this->em->flush();
    }
}
