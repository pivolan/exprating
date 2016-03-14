<?php
/**
 * Date: 10.03.16
 * Time: 4:08
 */

namespace AppBundle\Event\Invite;

use AppBundle\Entity\Invite;

interface InviteEventInterface
{
    /**
     * @return Invite
     */
    public function getInvite();
}
