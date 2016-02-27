<?php
/**
 * Date: 12.02.16
 * Time: 19:26
 */

namespace Exprating\ImportBundle\Command;


use AppBundle\Entity\Category;
use Exprating\ImportBundle\CompareText\EvalTextRus;
use Exprating\ImportBundle\Entity\Categories;
use Exprating\ImportBundle\Entity\Item;
use Exprating\ImportBundle\Entity\SiteProductRubrics;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        //Получим все категории из экспрейтинга, последнего уровня, ставим условие через реп, что нет вложенных категорий
        //Получим все категории для импорта, последнего уровня, ставим условие что есть хоть один товар, innerJoin, where AliasCategory IS NULL
        //будем идти по каждой категории импортада
        //проверяем что у нее нет aliasCategory, иначе пропускаем обработку
        //Формируем три переменных: Название категории, полный путь
        //С этими данными лезем по всем категориям из Exprating, сравниваем имена, ставим наибольший вес. Сравниваем пути, вес меньше.
        //Категории с наибольшим совпадением записываем в alias

        foreach ($itemsIterator as $key => $row) {
            /** @var Item $item */
            $item = $row[0];
            $itemName = $item->getName();
            $category = $item->getCategory();
            $categoryName = $category->getName();
            $path = $itemName . ' ' . $category->getName();
            while ($category = $category->getParent()) {
                $path .= ' ' . $category->getName();
            }
            $sumPercent = 0.0;
            $prevPercent = 0.0;
            foreach ($rubrics as $rubric) {
                $rubricId = $rubric->getId();
                $rubricName = $rubric->getName();
                $path2 = $rubric->getName() . ' ' . $rubric->getParsersynonyms() . ' ' . $rubric->getParsershortname();
                while ($rubric = $rubric->getParent()) {
                    $path2 .= ' ' . $rubric->getName();
                }
                $percent = 0;
                similar_text($path, $path2, $percent);
                $evalTextPercent = [];
                $this->evalTextRus->evaltextRus(2, $path, $path2, $evalTextPercent);

                similar_text($itemName, $rubricName, $percentName);
                similar_text($categoryName, $rubricName, $percentCategoryName);

                $sumPercent = (float)$percent + $percentName + $percentCategoryName + $percent;
                if ($sumPercent > $prevPercent) {
                    $matches[$item->getId()] = [$item->getId() . ' ' . $path, $rubricId . ' ' . $path2, 0, $evalTextPercent['sim'], $percent];
                    $prevPercent = $sumPercent;
                }
            }
            if ($key > 100) {
                $this->emImport->detach($row[0]);
                break;
            }
        }
        usort($matches, function ($a, $b) {
            return $a[3] + $a[4] > $b[3] + $b[4];
        });
        foreach ($matches as $percent => $row) {
            echo sprintf("%d %d %s: %s -> %s \n", $row[3], $row[4], $row[2], $row[0], $row[1]);
        }

        $appEntityManager->flush();
    }
}