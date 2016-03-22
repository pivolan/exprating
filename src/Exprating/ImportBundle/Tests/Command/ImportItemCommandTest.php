<?php
/**
 * Date: 21.03.16
 * Time: 20:47
 */

namespace Exprating\ImportBundle\Tests\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exprating\ImportBundle\Command\ImportItemCommand;
use Exprating\ImportBundle\Entity\AliasCategory;
use Exprating\ImportBundle\Entity\AliasItem;
use Exprating\ImportBundle\Entity\Categories;
use Exprating\ImportBundle\Entity\Item;
use Exprating\ImportBundle\Entity\Parameters;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ImportItemCommandTest extends  \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $aliasItem = (new AliasItem())->setItemExpratingSlug('product');
        $aliasCategory = (new AliasCategory())->setCategoryExpratingId(1);
        $categoryIr = (new Categories())->setAliasCategory($aliasCategory);
        $parameter = (new Parameters())->setName('name')->setGroupName('Тип холодильника');
        $parameterCopy = (new Parameters())->setName('name2')->setGroupName('Тип холодильника');
        $item = (new Item())
            ->setId(1)
            ->setUrl('https://somesite.ru/someprefix/one_more/item_slug')
            ->setName('Название нового итема 1')
            ->setRating(4.87)
            ->setCategory($categoryIr)
            ->addParameter($parameter)
            ->addParameter($parameterCopy);
        $itemClone = clone $item;
        $itemClone->setUrl('https://somesite.ru/someprefix/one_more/item_slug2');
        $itemWithAlias = clone $itemClone;
        $itemWithAlias->setAliasItem($aliasItem);
        $items = [[0 => $item], [0 => $itemClone], [0 => $itemWithAlias]];

        $product = (new Product());

        $emImport = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['persist', 'flush', 'clear', 'getRepository', 'getAllQuery', 'iterate'])
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $emImport->expects($this->any())->method('iterate')
            ->willReturn($items);
        $emImport->expects($this->any())
            ->method($this->anything())
            ->will($this->returnValue($emImport));

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'findOneBy'])
            ->getMock();
        $repository->expects($this->any())->method('find')
            ->with($this->anything())
            ->willReturn(null);
        $repository->expects($this->any())->method('findOneBy')
            ->willReturnCallback(
                function ($args) use ($product) {
                    if ($args == ['slug' => 'item_slug2']) {
                        return new Product();
                    }
                    if ($args == ['slug' => 'product']) {
                        return $product;
                    }
                }
            );

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['persist', 'flush', 'clear', 'getRepository', 'getReference'])
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);
        $category = (new Category())->setSlug('1');
        $em->expects($this->any())->method('getReference')
            ->willReturn($category);

        $testCase = $this;
        $em->expects($this->any())->method('persist')
            ->willReturnCallback(
                function ($product) use ($category, $testCase) {
                    if ($product instanceof Product) {
                        $testCase->assertEquals($category, $product->getCategory());
                        $testCase->assertEquals('/pics/10/1.jpg', $product->getPreviewImage());
                        $testCase->assertEquals('/pics/10/1.jpg', $product->getImages()[0]->getFilename());
                        $testCase->assertEquals('/pics/10/1.jpg', $product->getImages()[0]->getName());
                        $testCase->assertCount(1, $product->getImages());
                        $testCase->assertTrue($product->getImages()[0]->getIsMain());
                    }
                }
            );
        $em->expects($this->any())
            ->method($this->anything())
            ->will($this->returnValue($em));


        $command = new ImportItemCommand();
        $command->setEm($em);
        $command->setEmImport($emImport);
        $tmpDir = sys_get_temp_dir();
        $rootDir = $tmpDir.'/'.uniqid().'/app';
        mkdir($rootDir, 0777, true);
        mkdir($rootDir.'/../web/pics/10/', 0777, true);
        file_put_contents($rootDir.'/../web/pics/10/1.jpg', 'qwerty');
        $command->setRootDir($rootDir);

        $slugify = new Slugify();
        $command->setSlugify($slugify);

        $input = new StringInput('');
        $output = new BufferedOutput();
        $command->run($input, $output);

        $this->assertEquals($category, $product->getCategory());
        $this->assertEquals('/pics/10/1.jpg', $product->getPreviewImage());
        $this->assertEquals('/pics/10/1.jpg', $product->getImages()[0]->getFilename());
        $this->assertEquals('/pics/10/1.jpg', $product->getImages()[0]->getName());
        $this->assertCount(1, $product->getImages());
        $this->assertTrue($product->getImages()[0]->getIsMain());

        $this->assertEquals('Название нового итема 1', $item->getAliasItem()->getItemExpratingName());
        $this->assertEquals('item_slug', $item->getAliasItem()->getItemExpratingSlug());
        $this->assertEquals($item, $item->getAliasItem()->getItemIrecommend());
        $this->assertEquals($item->getUrl(), $item->__toString());
    }
}
