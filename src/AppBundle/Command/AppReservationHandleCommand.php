<?php

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductReservationOverEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppReservationHandleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:reservation:handle')
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
        /** @var Product[] $products */
        $products = $em->getRepository('AppBundle:Product')->findReserved();
        foreach ($products as $product) {
            $event = new ProductReservationOverEvent($product);
            $this->getContainer()->get('event_dispatcher')->dispatch(ProductEvents::RESERVATION_OVER, $event);
        }
        $em->flush();
        $output->writeln('Success fully unreserved '.count($products).' products');
    }
}
