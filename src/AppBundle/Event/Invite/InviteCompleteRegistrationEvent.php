<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Event\Invite;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Event\Invite\InviteEventInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InviteCompleteRegistrationEvent extends Event
{
    /**
     * @var User
     */
    protected $expert;

    /**
     * InviteCompleteRegistrationEvent constructor.
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
