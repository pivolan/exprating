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

        $entityManagerImport = $this->emImport;
        $repoRubrics = $entityManagerImport->getRepository('ExpratingImportBundle:SiteProductRubrics');
        $repoItem = $entityManagerImport->getRepository('ExpratingImportBundle:Item');
        $appEntityManager = $this->em;
        /** @var SiteProductRubrics[] $rubrics */
        $rubrics = $repoRubrics->findAll();
        /** @var Item[] $items */
        $itemsIterator = $repoItem->createQueryBuilder('a')->getQuery()->iterate();
        $matches = [];
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
                evaltextRus(2, $path, $path2, $evalTextPercent);

                similar_text($itemName, $rubricName, $percentName);
                similar_text($categoryName, $rubricName, $percentCategoryName);

                //$delta = levenshtein($path, $path2);
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

function preprocessRus($lim_len_symb, $text, &$arrt, &$count_t)
{
    $text2 = mb_strtolower($text);
    $strm = preg_replace('/[^а-я]/u', " ", $text2);            ## print "<hr>".$strm."<hr>";  //ok
    $arrt = [];
    $_wordst = preg_split("/ +/u", $strm);
    foreach ($_wordst as $ws) {
        $lengws = mb_strlen($ws);                     ##-- print "ws=$ws*  \$lengws=$lengws<br>";  //Ok
        if (($ws != "") && ($lengws > $lim_len_symb)) {
            $arrt[] = $ws;      //////////print "--------------------ws=$ws*<br>";
        }#(lengws_res)
    }
    $count_t = count($arrt);
}//end func

function evaltextRus($limit_symbols = 2, $str1, $str2, &$out, $_delta_lett = 3, $_prob75 = 0.702)
{
    preprocessRus($limit_symbols, $str1, $arr1, $count1);
    preprocessRus($limit_symbols, $str2, $arr2, $count2);
    $eq = 0;
    foreach ($arr1 as $ws1) {
        foreach ($arr2 as $ws2) {
            $lengws1 = mb_strlen($ws1);
            $lengws2 = mb_strlen($ws2);
            $deltalenws = abs($lengws1 - $lengws2);
            if (preg_match("/$ws1/u", $ws2) || preg_match("/$ws2/u", $ws1)) {
                if ($deltalenws <= $_delta_lett) {
                    ## print "совпадение: $ws1 $ws2    <b>$deltalenws</b><br>";
                    $eq++;
                }
            } else {
                $ab1 = [];
                $ab2 = [];
                $ab1 = preg_split("//u", $ws1);
                $ab2 = preg_split("//u", $ws2);
                $eqlet = 0;
                $countab1 = count($ab1) - 2;
                $countab2 = count($ab2) - 2;
                if ($lengws1 > $lengws2) {
                    $eqdelete = $lengws1;
                } else {
                    $eqdelete = $lengws2;
                }
                for ($j = 1; $j <= $countab1 && $j <= $countab2; $j++) {  ///////////////////////print $ab1[$j]." = ".$ab2[$j]."<br>";
                    if ($ab1[$j] != "" || $ab2[$j] != "") {
                        if ($ab1[$j] == $ab2[$j]) {
                            $eqlet++;
                        }  ///  print $ab1[$j]." = ".$ab2[$j]."<br>";
                    }
                }#for(j=1)
                $reseq = $eqlet / $eqdelete;   //$countab1;
                if ($reseq > $_prob75) {
                    $eq += $reseq;
                    ##  print "~~~~~~несовпадение-OK:~~~$ws1~~~$ws2~~~\$eqdelete=$eqdelete($lengws1,$lengws2)~~~\$eqlet=$eqlet~~~\$reseq=$reseq<br>";
                }
                ///print "---несовпадение:--$ws1---$ws2---\$eqdelete=$eqdelete ($lengws1, $lengws2)----\$eqlet=$eqlet---\$reseq=$reseq<br>";
            }//else
        }
    }##for(for)
    $sumcount = ($count2 + $count1) / 2;
    $per = (100 * $eq) / $sumcount;
    $per = sprintf("%5.2f", $per);
    $out["limit"] = $limit_symbols;
    $out["count1"] = $count1;
    $out["count2"] = $count2;
    $out["avercount"] = $sumcount;
    return $per;
}//end func
