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
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * InviteCompleteRegistrationEvent constructor.
     *
     * @param User $expert
     */
    public function __construct(User $expert, Request $request, Response $response)
    {
        $this->expert = $expert;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return User
     */
    public function getExpert()
    {
        return $this->expert;
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
