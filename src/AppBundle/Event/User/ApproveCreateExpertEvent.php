<?php
/**
 * Date: 10.03.16
 * Time: 3:59
 */

namespace AppBundle\Event\User;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\CreateExpertRequest;

class ApproveCreateExpertEvent extends Event
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
