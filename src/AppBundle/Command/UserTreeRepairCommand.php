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

class UserTreeRepairCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:user:tree')
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
        $repo = $em->getRepository('AppBundle:User');

        $users = $repo->findAll();
        $repo->recover();
        foreach ($users as $user) {
            $lvl = 0;
            $parent = $user;
            while ($parent = $parent->getCurator()) {
                $lvl++;
            }
            $user->setLvl($lvl);
        }

        $em->flush();
        $output->writeln('Success fully tree repaired');
    }
}
