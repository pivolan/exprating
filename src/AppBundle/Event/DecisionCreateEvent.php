<?php
/**
 * Date: 20.02.16
 * Time: 13:32
 */

namespace AppBundle\Event;


use AppBundle\Entity\CuratorDecision;
use Symfony\Component\EventDispatcher\Event;

class DecisionCreateEvent extends Event
{
    /**
     * @var CuratorDecision
     */
    protected $decision;

    /**
     * DecisionCreateEvent constructor.
     *
     * @param CuratorDecision $decision
     */
    public function __construct(CuratorDecision $decision)
    {
        $this->decision = $decision;
    }

    /**
     * @return CuratorDecision
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * @param CuratorDecision $decision
     *
     * @return $this
     */
    public function setDecision(CuratorDecision $decision)
    {
        $this->decision = $decision;
        return $this;
    }
}