<?php
/**
 * Date: 16.02.16
 * Time: 22:23
 */

namespace AppBundle\Event;


final class ProductEvents
{
    const RESERVATION = 'reserve_product';
    const REJECT = 'reject_product';
    const APPROVE = 'approve_product';
    const PUBLISH_REQUEST = 'publish_request_product';
    const PUBLISH = 'publish_product';
    const CHANGE_EXPERT = 'change_expert_product';
    const RESERVATION_OVER = 'reserve_over_product';
    const COMMENTED = 'commented_product';
} 