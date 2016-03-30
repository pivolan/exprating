<?php
/**
 * Date: 30.03.16
 * Time: 19:25
 */

namespace Exprating\ImportBundle\Tests\Command;


use AppBundle\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\ImportBundle\Command\ImportCategoryCharacteristicsCommand;
use Exprating\ImportBundle\Entity\AliasCategory;
use Exprating\ImportBundle\Entity\Categories;
use Exprating\ImportBundle\Entity\Parameters;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ImportCategoryCharacteristicsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $categories = [
            (new Categories())
                ->setAliasCategory((new AliasCategory())->setCategoryExpratingId('cars')),
            (new Categories())
                ->addParameter((new Parameters())->setGroupName('Размер'))
                ->addParameter((new Parameters())->setGroupName('Вес'))
                ->setAliasCategory((new AliasCategory())->setCategoryExpratingId('dogs')),

        ];

        $emImport = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['getRepository', 'findAll'])
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $emImport->expects($this->any())->method('findAll')
            ->willReturn($categories);
        $emImport->expects($this->any())
            ->method($this->anything())
            ->will($this->returnValue($emImport));

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'findOneBy'])
            ->getMock();

        $category = (new Category())->setSlug('dogs');
        $repository->expects($this->any())->method('find')
            ->with($this->anything())
            ->willReturnCallback(
                function ($args) use ($category) {
                    if ($args == 'dogs') {
                        return $category;
                    } else {
                        return null;
                    }
                }
            );
        $repository->expects($this->any())->method('findOneBy')
            ->willReturnCallback(
                function ($args) {
                    if ($args == ['name' => 'Вес']) {
                        return (new Characteristic())->setName('Вес');
                    }

                    return null;
                }
            );

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['persist', 'flush', 'getRepository'])
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        $command = new ImportCategoryCharacteristicsCommand();
        $command->setEm($em);
        $command->setEmImport($emImport);
        $slugify = new Slugify();
        $command->setSlugify($slugify);

        $input = new StringInput('');
        $output = new BufferedOutput();
        $command->run($input, $output);

        $this->assertCount(2, $category->getCategoryCharacteristics());
        $this->assertEquals('Размер', $category->getCategoryCharacteristics()[0]->getCharacteristic()->getName());
        $this->assertEquals('Размер', $category->getCategoryCharacteristics()[0]->getCharacteristic()->getLabel());
        $this->assertEquals('razmer', $category->getCategoryCharacteristics()[0]->getCharacteristic()->getSlug());
        $this->assertEquals("Вес", $category->getCategoryCharacteristics()[1]->getCharacteristic()->getName());
        $this->assertEquals(null, $category->getCategoryCharacteristics()[1]->getCharacteristic()->getLabel());
        $this->assertEquals(null, $category->getCategoryCharacteristics()[1]->getCharacteristic()->getSlug());
    }
}