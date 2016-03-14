<?php
/**
 * Date: 10.03.16
 * Time: 4:09
 */

namespace AppBundle\Event\Invite;

use AppBundle\Entity\Invite;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InviteActivateEvent extends Event implements InviteEventInterface
{
    /**
     * @var Invite
     */
    protected $invite;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * InviteActivateEvent constructor.
     *
     * @param Invite   $invite
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(Invite $invite, Request $request, Response $response)
    {
        $this->invite = $invite;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Invite
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
