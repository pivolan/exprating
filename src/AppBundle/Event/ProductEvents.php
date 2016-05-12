<?php

/**
 * Date: 16.02.16
 * Time: 22:23.
 */

namespace AppBundle\Event;

final class ProductEvents
{
    const RESERVATION = 'reserve_product';
    const PUBLISH_REQUEST = 'publish_request_product';
    const APPROVE = 'approve_product';
    const REJECT = 'reject_product';
    const PUBLISH = 'publish_product';
    const CHANGE_EXPERT = 'change_expert_product';
    const RESERVATION_OVER = 'reserve_over_product';
    const COMMENTED = 'commented_product';
    const DECISION = 'curator_decision';
    const VISIT = 'product_visited';
    const EDITED = 'product_edited';
    const IMPORT_PICTURES = 'partner_product_pictures';
    const SAVE_QUERY_STRING = 'save_query_string';
}
