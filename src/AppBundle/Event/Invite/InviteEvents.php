<?php
/**
 * Date: 10.03.16
 * Time: 4:07
 */

namespace AppBundle\Event\Invite;

final class InviteEvents
{
    const SEND = 'send_invite';
    const ACTIVATE = 'activate_invite';
    const REQUEST_RIGHTS = 'request_rights_to_send_invites';
    const APPROVE_RIGHTS = 'approve_rights_by_curator';
    const COMPLETE_REGISTRATION = 'complete_registration';
}