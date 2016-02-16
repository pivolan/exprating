<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Event\ProductPublishEvent;
use Doctrine\ORM\EntityManager;

class PublishProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handler(ProductPublishEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();

        $curatorDecision = new CuratorDecision();
        $curatorDecision->setCurator($expert->getCurator())
            ->setProduct($product)
            ->setUpdatedAt(new \DateTime());
        $this->em->persist($curatorDecision);
        $this->em->flush();
    }
} 