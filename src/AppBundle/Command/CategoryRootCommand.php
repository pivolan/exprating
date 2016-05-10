<?php

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductReservationOverEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CategoryRootCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:category:root')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Category[] $categories */
        $categories = $em->getRepository('AppBundle:Category')->getRootNodes('lft');
        $rootCategory = (new Category())->setSlug('root')->setName('root');
        $em->persist($rootCategory);
        foreach ($categories as $category) {
            $category->setParent($rootCategory);
            $rootCategory->addChild($category);
        }
        $em->flush();
        $output->writeln('Success fully created root');
    }
}
