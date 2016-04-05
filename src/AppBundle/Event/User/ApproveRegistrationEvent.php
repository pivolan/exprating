<?php
/**
 * Date: 10.03.16
 * Time: 3:59
 */

namespace AppBundle\Event\User;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\RegistrationRequest;

class ApproveRegistrationEvent extends Event
{
    /** @var  RegistrationRequest */
    protected $registrationRequest;

    /**
     * RegistrationRequestEvent constructor.
     *
     * @param RegistrationRequest $registrationRequest
     */
    public function __construct(RegistrationRequest $registrationRequest)
    {
        $this->registrationRequest = $registrationRequest;
    }

    /**
     * @return RegistrationRequest
     */
    public function getRegistrationRequest()
    {
        return $this->registrationRequest;
    }
}
