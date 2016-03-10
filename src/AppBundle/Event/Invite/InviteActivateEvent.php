<?php
/**
 * Date: 10.03.16
 * Time: 4:09
 */

namespace AppBundle\Event\Invite;


use AppBundle\Entity\Invite;
use Symfony\Component\EventDispatcher\Event;

class InviteActivateEvent extends Event implements InviteEventInterface
{

    /**
     * @return Invite
     */
    public function getInvite()
    {
        // TODO: extends Event implement getInvite() method.
    }
}