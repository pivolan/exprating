<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\RatingSettings;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RepairCategoryCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('import:repair_category')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Category[] $categories */
        $categories = $this->em->getRepository('AppBundle:Category')->findAll();
        foreach ($categories as $category) {
            if (!$category->getRatingSettings()) {
                $ratingSettings = new RatingSettings();
                $ratingSettings->setRating1Label('Отзыв1')
                    ->setRating2Label('Отзыв2')
                    ->setRating3Label('Отзыв3')
                    ->setRating4Label('Отзыв4')
                    ->setCategory($category);
                $category->setRatingSettings($ratingSettings);
                $this->em->persist($ratingSettings);
                $output->writeln($category->getName().$category->getSlug());
            }
        }
        $this->em->flush();
    }
}
