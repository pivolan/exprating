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
 * Определить категорию у товара, при условии что товар находится не в листовой категории
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class DetectCategoryCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('import:detect:category')
            ->setDescription('Greet someone');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getRepository('AppBundle:Category')->getAll();
        /** @var Product[] $products */
        $products = $this->em->getRepository('AppBundle:Product')->getWithNotLisnCategoryQuery()->iterate();
        foreach ($products as $product) {
            $lastLevelCategories = [];
            $this->recursive($product->getCategory(), $lastLevelCategories);
        }
        $this->em->flush();
    }

    private function recursive(Category $category, array &$categories)
    {
        if ($category->getChildren()->count() > 0) {
            foreach ($category->getChildren() as $categoryChild) {
                $this->recursive($categoryChild, $categories);
            }
        } else {
            $categories[] = $category;
        }
    }
}
