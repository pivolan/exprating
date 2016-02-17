<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Event\ProductPublishRequestEvent;
use Doctrine\ORM\EntityManager;

class PublishRequestProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handler(ProductPublishRequestEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();

        if ($expert) {
            $curatorDecision = new CuratorDecision();
            $curatorDecision->setCurator($expert->getCurator())
                ->setProduct($product)
                ->setUpdatedAt(new \DateTime());
            $this->em->persist($curatorDecision);
        } else {
            $product->setIsEnabled(true)->setEnabledAt(new \DateTime())->setReservedAt(null);
            $event->stopPropagation();
        }
        $this->em->flush();
    }
} 