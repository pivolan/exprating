<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Event\User;

use AppBundle\Entity\CreateExpertRequest;
use Symfony\Component\EventDispatcher\Event;

class CreateExpertRequestEvent extends Event
{
    /** @var  CreateExpertRequest */
    protected $createExpertRequest;

    /**
     * CreateExpertRequestEvent constructor.
     *
     * @param CreateExpertRequest $createExpertRequest
     */
    public function __construct(CreateExpertRequest $createExpertRequest)
    {
        $this->createExpertRequest = $createExpertRequest;
    }

    /**
     * @return CreateExpertRequest
     */
    public function getCreateExpertRequest()
    {
        return $this->createExpertRequest;
    }
}
