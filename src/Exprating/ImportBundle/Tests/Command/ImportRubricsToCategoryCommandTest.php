<?php
/**
 * Date: 21.03.16
 * Time: 20:47
 */

namespace Exprating\ImportBundle\Tests\Command;


use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use AppBundle\Entity\RatingSettings;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use AppBundle\Repository\CategoryRepository;
use Exprating\FakerBundle\Faker\FakeEntitiesGenerator;
use Exprating\ImportBundle\Command\ImportRubricsToCategoryCommand;
use Exprating\ImportBundle\Command\RepairCategoryCommand;
use Exprating\ImportBundle\Entity\SiteProductRubrics;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ImportRubricsToCategoryCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $rubric111 = (new SiteProductRubrics())
            ->setName('рубрика 111')
            ->setShowall(true)
            ->setId(111);
        $rubric11 = (new SiteProductRubrics())
            ->setName('рубрика 11')
            ->setShowman(true)
            ->setShowwoman(true)
            ->setShowchild(true)
            ->addChild($rubric111)
            ->setId(11);
        $rubric1 = (new SiteProductRubrics())
            ->setName('рубрика 1')
            ->addChild($rubric11)
            ->setId(1);
        $rubric2 = (new SiteProductRubrics())
            ->setName('рубрика 2')
            ->setId(2);

        $rubrics = [$rubric1, $rubric2];

        $repository = $this->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repository->method('findBy')
            ->willReturn($rubrics);

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())->method('persist')->with($this->isInstanceOf(Category::class));
        $entityManager->expects($this->at(0))->method('persist')->with(
            $this->callback(
                function (Category $category) {
                    return $category->getName() == 'рубрика 1'
                           && $category->getSlug() == 'rubrika-1-1';
                }
            )
        );
        $entityManager->expects($this->at(4))->method('persist')->with(
            $this->callback(
                function (Category $category) {
                    return $category->getName() == 'рубрика 11' && $category->getSlug() == 'rubrika-11-11' &&
                        $category->getParent()->getName() == 'рубрика 1';
                }
            )
        );
        $entityManager->expects($this->at(6))->method('persist')->with(
            $this->callback(
                function (Category $category) {
                    return $category->getName() == 'рубрика 111' && $category->getSlug() == 'rubrika-111-111';
                }
            )
        );
        $entityManager->expects($this->at(7))->method('persist')->with(
            $this->callback(
                function (Category $category) {
                    return $category->getName() == 'рубрика 2' && $category->getSlug() == 'rubrika-2-2';
                }
            )
        );
        $entityManager->expects($this->once())->method('flush');
        $entityManager->expects($this->exactly(4))->method('getReference')->willReturn(new PeopleGroup());

        $entityManagerImport = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManagerImport->method('getRepository')
            ->willReturn($repository);
        $command = new ImportRubricsToCategoryCommand();
        $command->setEm($entityManager);
        $command->setEmImport($entityManagerImport);

        $slugify = new Slugify();
        $command->setSlugify($slugify);

        $input = new StringInput('');
        $output = new BufferedOutput();
        $command->run($input, $output);
    }
}