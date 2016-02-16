<?php
/**
 * Date: 16.02.16
 * Time: 22:42
 */

namespace AppBundle\Event;


use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

class ProductCommentedEvent extends Event
{
    /**
     * @var Comment
     */
    protected $comment;
}