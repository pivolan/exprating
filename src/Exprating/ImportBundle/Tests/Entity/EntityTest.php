<?php
/**
 * Date: 22.03.16
 * Time: 4:00
 */

namespace Exprating\ImportBundle\Tests\Entity;


use Exprating\ImportBundle\Entity\Categories;
use Exprating\ImportBundle\Entity\Item;
use Exprating\ImportBundle\Entity\Parameters;
use Exprating\ImportBundle\Entity\SiteProductRubrics;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalize()
    {
        $parameter = new Parameters();
        $parameter->getId();
        $item = new Item();
        $cateoriesOne = (new Categories())
            ->setUrl('asd')
            ->addParameter($parameter)
            ->removeParameter($parameter)
            ->addItem($item)
            ->removeItem($item);
        $cateoriesOne->getItems();
        $cateoriesOne->getParameters();
        $cateoriesOne->getUrl();

        $item->addParameter($parameter)
            ->removeParameter($parameter)
            ->setVotesCount(1)
            ->setUrlCrc32('sdf')
            ->setCategoryUrl('sdf');
        $item->getVotesCount();
        $item->getCategoryUrl();
        $item->getUrlCrc32();

        $child = new SiteProductRubrics();
        $siteProductRubrics = (new SiteProductRubrics())
            ->setLeftKey(1)
            ->setRightKey(1)
            ->setLevel(1)
            ->setTreeId(1)
            ->setAdded(new \DateTime())
            ->setChildcount(10)
            ->setParsershortname('wert')
            ->setParserattr('sdf')
            ->setParsersynonyms('qwer')
            ->setSearchweightsummer('wer')
            ->setSearchweightwinter('sdf')
            ->setParserlinkingenabled(true)
            ->setShowman(true)
            ->setShowwoman(true)
            ->setShowchild(true)
            ->setShowall(true)
            ->setParent(new SiteProductRubrics())
            ->addChild($child)
            ->removeChild($child);
        $siteProductRubrics->getLeftKey();
        $siteProductRubrics->getRightKey();
        $siteProductRubrics->getLevel();
        $siteProductRubrics->getTreeId();
        $siteProductRubrics->getAdded();
        $siteProductRubrics->getChildcount();
        $siteProductRubrics->getParsershortname();
        $siteProductRubrics->getParserattr();
        $siteProductRubrics->getParsersynonyms();
        $siteProductRubrics->getSearchweightsummer();
        $siteProductRubrics->getSearchweightwinter();
        $siteProductRubrics->getParserlinkingenabled();
        $siteProductRubrics->getParent();
        $siteProductRubrics->getShowman();
        $siteProductRubrics->getShowwoman();
        $siteProductRubrics->getShowchild();
        $siteProductRubrics->getShowall();
    }
}