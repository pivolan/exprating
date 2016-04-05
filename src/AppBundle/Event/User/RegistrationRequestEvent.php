<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Event\User;

use AppBundle\Entity\RegistrationRequest;
use Symfony\Component\EventDispatcher\Event;

class RegistrationRequestEvent extends Event
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
