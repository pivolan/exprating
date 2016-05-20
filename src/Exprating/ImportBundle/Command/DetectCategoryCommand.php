<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use Exprating\ImportBundle\CompareText\EvalTextRus;
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
     * @var EvalTextRus
     */
    protected $evalTextRus;

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param EvalTextRus $evalTextRus
     */
    public function setEvalTextRus($evalTextRus)
    {
        $this->evalTextRus = $evalTextRus;
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
        /**
         * Достаем товары с не листовыми категориями
         * @var Product[] $products
         */
        $products = $this->em->getRepository('AppBundle:Product')->getWithNotLisnCategoryQuery()->getResult();
        foreach ($products as $product) {
            /** @var Category[] $lastLevelCategories */
            $lastLevelCategories = [];
            //Получаем листовые вложенные категории для этого товара.
            $this->recursive($product->getCategory(), $lastLevelCategories);
            //Определяем наиболее подходящую категорию для этого товара
            $prevPercent = 0.0;
            foreach ($lastLevelCategories as $category) {
                $percent = $this->evalTextRus->evaltextRus($category->getName(), $product->getName());
                if ($percent > $prevPercent) {
                    $product->setCategory($category);
                    $output->writeln(
                        "For Product name '{$product->getName()}' set category '{$category->getName()}'
                         with percent: {$percent}"
                    );
                    $prevPercent = $percent;
                }
            }
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
