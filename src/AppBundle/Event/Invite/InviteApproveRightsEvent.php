<?php
/**
 * Date: 10.03.16
 * Time: 4:12
 */

namespace AppBundle\Event\Invite;

use AppBundle\Entity\User;
use AppBundle\Event\User\UserEventInterface;
use Symfony\Component\EventDispatcher\Event;

class InviteApproveRightsEvent extends Event
{
    /** @var  User */
    protected $expert;

    /** @var  User */
    protected $curator;

    /**
     * InviteApproveRightsEvent constructor.
     *
     * @param User $expert
     * @param User $curator
     */
    public function __construct(User $expert, User $curator)
    {
        $this->expert = $expert;
        $this->curator = $curator;
    }

    /**
     * @return User
     */
    public function getExpert()
    {
        return $this->expert;
    }

    /**
     * @return User
     */
    public function getCurator()
    {
        return $this->curator;
    }
}
