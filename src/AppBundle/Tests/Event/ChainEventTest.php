<?php

/**
 * Date: 19.02.16
 * Time: 19:24.
 */

namespace AppBundle\Tests\Event;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Event\ProductApproveEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductRejectEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Event\ProductReservationOverEvent;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Tests\AbstractWebCaseTest;

class ChainEventTest extends AbstractWebCaseTest
{
    public function testPublish()
    {
        $em = $this->doctrine->getManager();
        /** @var User $curator */
        $curator = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']);
        /** @var User $expert */
        $expert = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'expert']);
        $count = $em->getRepository('AppBundle:CuratorDecision')->countNew($curator);
        $filter = (new ProductFilter())->setCategory($expert->getCategories()[0])->setStatus(
            ProductFilter::STATUS_FREE
        );
        /** @var Product[] $freeProducts */
        $freeProducts = $em->getRepository('AppBundle:Product')->findByFilterQuery($filter)->getResult();
        $product = $freeProducts[0];
        $this->assertFalse($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));

        $eventDispatcher = $this->client->getContainer()->get('event_dispatcher');
        $eventDispatcher->dispatch(ProductEvents::RESERVATION, new ProductReservationEvent($product, $expert));

        $this->assertFalse($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));
        $this->assertEquals($product->getExpertUser(), $expert);
        $this->assertNotNull($product->getReservedAt());

        $eventDispatcher->dispatch(ProductEvents::PUBLISH_REQUEST, new ProductPublishRequestEvent($product));

        /** @var CuratorDecision[] $decisions */
        $decisions = $em->getRepository('AppBundle:CuratorDecision')->waitByCuratorByProduct($curator, $product)
            ->getResult();
        $this->assertCount(1, $decisions);
        $this->assertTrue($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));
        $countNew = $em->getRepository('AppBundle:CuratorDecision')->countNew($curator);
        $this->assertEquals($count + 1, $countNew);
        $eventDispatcher->dispatch(ProductEvents::APPROVE, new ProductApproveEvent($product, $curator));
        $this->assertEquals($count, $em->getRepository('AppBundle:CuratorDecision')->countNew($curator));
        $this->assertTrue($product->getIsEnabled());
        $this->assertNull($product->getReservedAt());
        $decision = $em->getRepository('AppBundle:CuratorDecision')->find($decisions[0]->getId());
        $this->assertEquals(CuratorDecision::STATUS_APPROVE, $decision->getStatus());
        $this->assertNull($decision->getRejectReason());
    }

    public function testReserveOver()
    {
        $em = $this->doctrine->getManager();
        /** @var User $curator */
        $curator = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']);
        /** @var User $expert */
        $expert = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'expert']);
        $count = $em->getRepository('AppBundle:CuratorDecision')->countNew($curator);
        $filter = (new ProductFilter())->setCategory($expert->getCategories()[0])->setStatus(
            ProductFilter::STATUS_FREE
        );
        /** @var Product[] $freeProducts */
        $freeProducts = $em->getRepository('AppBundle:Product')->findByFilterQuery($filter)->getResult();
        $product = $freeProducts[0];
        $this->assertFalse($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));

        $eventDispatcher = $this->client->getContainer()->get('event_dispatcher');
        $eventDispatcher->dispatch(ProductEvents::RESERVATION, new ProductReservationEvent($product, $expert));

        $this->assertFalse($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));
        $this->assertEquals($product->getExpertUser(), $expert);
        $this->assertNotNull($product->getReservedAt());

        $eventDispatcher->dispatch(ProductEvents::RESERVATION_OVER, new ProductReservationOverEvent($product));
        $this->assertNull($product->getExpertUser());
        $this->assertNull($product->getReservedAt());
    }

    public function testReject()
    {
        $em = $this->doctrine->getManager();
        /** @var User $curator */
        $curator = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']);
        /** @var User $expert */
        $expert = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'expert']);
        $count = $em->getRepository('AppBundle:CuratorDecision')->countNew($curator);
        $filter = (new ProductFilter())->setCategory($expert->getCategories()[0])->setStatus(
            ProductFilter::STATUS_FREE
        );
        /** @var Product[] $freeProducts */
        $freeProducts = $em->getRepository('AppBundle:Product')->findByFilterQuery($filter)->getResult();
        $product = $freeProducts[0];
        $this->assertFalse($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));

        $eventDispatcher = $this->client->getContainer()->get('event_dispatcher');
        $eventDispatcher->dispatch(ProductEvents::RESERVATION, new ProductReservationEvent($product, $expert));

        $this->assertFalse($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));
        $this->assertEquals($product->getExpertUser(), $expert);
        $this->assertNotNull($product->getReservedAt());

        $eventDispatcher->dispatch(ProductEvents::PUBLISH_REQUEST, new ProductPublishRequestEvent($product));
        /** @var CuratorDecision[] $decisions */
        $decisions = $em->getRepository('AppBundle:CuratorDecision')->waitByCuratorByProduct($curator, $product)
            ->getResult();
        $this->assertCount(1, $decisions);
        $this->assertTrue($em->getRepository('AppBundle:CuratorDecision')->IsExists($product));
        $countNew = $em->getRepository('AppBundle:CuratorDecision')->countNew($curator);
        $this->assertEquals($count + 1, $countNew);
        $eventDispatcher->dispatch(
            ProductEvents::REJECT,
            new ProductRejectEvent($product, $curator, 'Плохое описание товара')
        );
        $this->assertEquals($count, $em->getRepository('AppBundle:CuratorDecision')->countNew($curator));
        $this->assertFalse($product->getIsEnabled());
        $this->assertNotNull($product->getReservedAt());
        $decision = $em->getRepository('AppBundle:CuratorDecision')->find($decisions[0]->getId());
        $this->assertEquals(CuratorDecision::STATUS_REJECT, $decision->getStatus());
        $this->assertEquals('Плохое описание товара', $decision->getRejectReason());
    }
}
