<?php
/**
 * Date: 21.03.16
 * Time: 20:47
 */

namespace Exprating\ImportBundle\Tests\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\RatingSettings;
use Doctrine\ORM\EntityManager;
use AppBundle\Repository\CategoryRepository;
use Exprating\ImportBundle\Command\RepairCategoryCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class RepairCategoryCommandTest extends  \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $ratingSettings = new RatingSettings();
        $ratingSettings
            ->setRating1Label('label1')
            ->setRating2Label('label2')
            ->setRating3Label('label3')
            ->setRating4Label('label4');
        $category1 = (new Category())
            ->setSlug('repair_category_1')
            ->setName('repair_category_1')
            ->setRatingSettings($ratingSettings);
        $category2 = (new Category())
            ->setSlug('repair_category_2')
            ->setName('repair_category_2');
        $categories = [$category1, $category2];
        $repository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repository->method('findAll')
            ->willReturn($categories);

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->method('getRepository')
            ->willReturn($repository);
        $entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(RatingSettings::class));
        $entityManager->expects($this->once())->method('flush');
        $command = new RepairCategoryCommand();
        $command->setEm($entityManager);

        $input = new StringInput('');
        $output = new BufferedOutput();
        $command->run($input, $output);

        $this->assertEquals($category1->getRatingSettings(), $ratingSettings);
        $this->assertNotNull($category2->getRatingSettings());
        $this->assertEquals('Отзыв1', $category2->getRatingSettings()->getRating1Label());
    }
}
