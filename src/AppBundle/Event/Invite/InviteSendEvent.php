<?php
/**
 * Date: 10.03.16
 * Time: 4:09
 */

namespace AppBundle\Event\Invite;


use AppBundle\Entity\Invite;
use Symfony\Component\EventDispatcher\Event;

class InviteSendEvent extends Event implements InviteEventInterface
{
    /**
     * @var Invite
     */
    protected $invite;

    /**
     * InviteSendEvent constructor.
     *
     * @param Invite $invite
     */
    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * @return Invite
     */
    public function getInvite()
    {
        return $this->invite;
    }
}