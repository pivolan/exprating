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

    /**
     * @var string
     */
    protected $message;

    /**
     * ProductCommentedEvent constructor.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;}

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}