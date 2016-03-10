<?php
/**
 * Date: 10.03.16
 * Time: 4:11
 */

namespace AppBundle\Event\Invite;

use AppBundle\Entity\User;
use AppBundle\Event\User\UserEventInterface;
use Symfony\Component\EventDispatcher\Event;

class InviteRequestRightsEvent extends Event
{
    /**
     * @var User
     */
    protected $expert;

    /**
     * InviteRequestRightsEvent constructor.
     *
     * @param User $expert
     */
    public function __construct(User $expert)
    {
        $this->expert = $expert;
    }

    /**
     * @return User
     */
    public function getExpert()
    {
        return $this->expert;
    }
}
