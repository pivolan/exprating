<?php

/**
 * Date: 19.02.16
 * Time: 19:24.
 */

namespace AppBundle\Tests\Event;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\PeopleGroup;
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
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ChainEventTest
 * @package AppBundle\Tests\Event
 */
class UserEventTest extends AbstractWebCaseTest
{
    public function testRequestCreateExpert()
    {

    }

    public function testApproveCreateExpert()
    {

    }


}
