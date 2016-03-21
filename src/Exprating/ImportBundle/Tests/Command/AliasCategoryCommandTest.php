<?php
/**
 * Date: 21.03.16
 * Time: 20:47
 */

namespace Exprating\ImportBundle\Tests\Command;

use AppBundle\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exprating\ImportBundle\Command\AliasCategoryCommand;
use Exprating\ImportBundle\CompareText\EvalTextRus;
use Exprating\ImportBundle\Entity\AliasCategory;
use Exprating\ImportBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class AliasCategoryCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $aliasCategory = (new AliasCategory());
        $categoryImport = (new Categories());
        $categoryImport2 = (new Categories())
            ->setParent($categoryImport)
            ->setName('детей детское детские');
        $categoryImport3 = (new Categories())
            ->setName('мужчин мужская мужское');
        $categoryImport4 = (new Categories())
            ->setName(' женщин женская девушек девушки');
        $categoryContinue = (new Categories())->setAliasCategory($aliasCategory);

        $categoriesImport = [$categoryImport2, $categoryImport3, $categoryImport4, $categoryContinue];

        $category = (new Category())->setSlug('slug');
        $categories = [$category];

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'findOneBy', 'getLastLevel', 'getFreeLastLevel', 'getPath'])
            ->getMock();

        $repository->method('getFreeLastLevel')
            ->willReturn($categoriesImport);
        $repository->method('getLastLevel')
            ->willReturn($categories);
        $repository->method('getPath')
            ->willReturn($categories);

        $emImport = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['persist', 'flush', 'clear', 'getRepository', 'getAllQuery', 'iterate'])
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $emImport->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);
        $testCase = $this;
        $emImport->method('persist')
            ->willReturnCallback(
                function ($aliasCategory) use ($testCase, $categoriesImport) {
                    /** @var AliasCategory $aliasCategory */
                    $testCase->assertEquals('slug', $aliasCategory->getCategoryExpratingId());
                    $testCase->assertContains($aliasCategory->getCategoryIrecommend(), $categoriesImport);
                    $testCase->assertContains(
                        $aliasCategory->getPeopleGroup(),
                        [
                            AliasCategory::PEOPLE_GROUP_ALL,
                            AliasCategory::PEOPLE_GROUP_CHILD,
                            AliasCategory::PEOPLE_GROUP_MAN,
                            AliasCategory::PEOPLE_GROUP_WOMAN,
                        ]
                    );
                }
            );

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['persist', 'flush', 'clear', 'getRepository', 'getReference'])
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        $command = new AliasCategoryCommand();
        $command->setEm($em);
        $command->setEmImport($emImport);

        $slugify = new Slugify();
        $command->setSlugify($slugify);
        $command->setEvalTextRus(new EvalTextRus());

        $input = new StringInput('');
        $output = new BufferedOutput();
        $command->run($input, $output);
    }
}
