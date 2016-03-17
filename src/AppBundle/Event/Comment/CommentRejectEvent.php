<?php
/**
 * Date: 17.03.16
 * Time: 23:38
 */

namespace AppBundle\Event\Comment;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

class CommentRejectEvent extends Event
{
    /**
     * @var Comment
     */
    private $comment;

    /**
     * CommentPublishEvent constructor.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}
