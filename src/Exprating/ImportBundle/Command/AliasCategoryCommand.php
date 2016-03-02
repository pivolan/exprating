<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use Exprating\ImportBundle\CompareText\EvalTextRus;
use Exprating\ImportBundle\Entity\AliasCategory;
use Exprating\ImportBundle\Entity\Categories;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AliasCategoryCommand
 * @package Exprating\ImportBundle\Command
 *
 * @SupressWarnings(PHPMD.CyclomaticComplexity)
 */
class AliasCategoryCommand extends ContainerAwareCommand
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

    /**
     * @param EvalTextRus $evalTextRus
     */
    public function setEvalTextRus(EvalTextRus $evalTextRus)
    {
        $this->evalTextRus = $evalTextRus;
    }

    protected function configure()
    {
        $this
            ->setName('import:alias')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Получим все категории из экспрейтинга, последнего уровня, ставим условие
        // через реп, что нет вложенных категорий
        $lastLevelCategories = $this->em->getRepository('AppBundle:Category')->getLastLevel();
        //Получим все категории для импорта, последнего уровня, ставим условие что есть хоть
        // один товар, innerJoin, where AliasCategory IS NULL
        $lastLevelCategoriesImport = $this->emImport->getRepository(
            'ExpratingImportBundle:Categories'
        )->getFreeLastLevel();
        //будем идти по каждой категории импорта
        //проверяем что у нее нет aliasCategory, иначе пропускаем обработку
        //Формируем три переменных: Название категории, полный путь
        //С этими данными лезем по всем категориям из Exprating, сравниваем имена,
        // ставим наибольший вес. Сравниваем пути, вес меньше.
        //Категории с наибольшим совпадением записываем в aliases

        $aliases = [];
        $matches = [];
        foreach ($lastLevelCategoriesImport as $key => $categoryImport) {
            if ($categoryImport->getAliasCategory()) {
                continue;
            }
            $path1 = $categoryImport->getName();
            $categoryParentImport = $categoryImport;
            while ($categoryParentImport = $categoryParentImport->getParent()) {
                $path1 .= ' '.$categoryParentImport->getName();
            }
            $prevPercent = 0.0;
            foreach ($lastLevelCategories as $category) {
                /** @var Category[] $path2Array */
                $path2Array = $this->em->getRepository('AppBundle:Category')->getPath($category);
                $path2 = $category->getName();
                foreach ($path2Array as $categoryParent) {
                    $path2 .= ' '.$categoryParent->getName();
                }
                $percent = 0;
                similar_text($path1, $path2, $percent);
                $evalTextPercent = $this->evalTextRus->evaltextRus($path1, $path2);

                similar_text($categoryImport->getName(), $category->getName(), $percentCategoryName);

                $sumPercent = (float)$percent + $percentCategoryName + $evalTextPercent;

                if ($sumPercent > $prevPercent) {
                    $matches[$categoryImport->getId()] = [
                        $path1,
                        $category->getSlug().' '.$path2,
                        0,
                        $evalTextPercent,
                        $percent,
                    ];
                    $peopleGroup = AliasCategory::PEOPLE_GROUP_ALL;
                    $childText = 'детей детский детское детские дети мальчиков девочек';
                    $manText = 'мужчин мужская мужское';
                    $womanText = 'женщин женская девушек девушки';
                    $childPercent = $this->evalTextRus->evaltextRus($categoryImport->getName(), $childText, 3);
                    $manPercent = $this->evalTextRus->evaltextRus($categoryImport->getName(), $manText, 3);
                    $womanPercent = $this->evalTextRus->evaltextRus($categoryImport->getName(), $womanText, 3);
                    $allPercent = 45;
                    if ($childPercent > $allPercent) {
                        $peopleGroup = AliasCategory::PEOPLE_GROUP_CHILD;
                    }
                    if ($manPercent > $childPercent && $manPercent > $allPercent) {
                        $peopleGroup = AliasCategory::PEOPLE_GROUP_MAN;
                    }
                    if ($womanPercent > $manPercent && $womanPercent > $childPercent && $womanPercent > $allPercent) {
                        $peopleGroup = AliasCategory::PEOPLE_GROUP_WOMAN;
                    }

                    $aliases[$categoryImport->getId()] = [$categoryImport, $category, $peopleGroup];
                    $prevPercent = $sumPercent;
                }
            }
        }
        usort(
            $matches,
            function ($a, $b) {
                return $a[3] + $a[4] > $b[3] + $b[4];
            }
        );
        foreach ($matches as $percent => $row) {
            echo sprintf("%d %d %s: %s -> %s \n", $row[3], $row[4], $row[2], $row[0], $row[1]);
        }
        foreach ($aliases as $alias) {
            $aliasCategory = new AliasCategory();
            $aliasCategory->setCategoryExpratingId($alias[1]->getSlug())
                ->setCategoryIrecommend($alias[0])
                ->setPeopleGroup($alias[2]);
            $this->emImport->persist($aliasCategory);
        }
        $this->emImport->flush();
    }
}
