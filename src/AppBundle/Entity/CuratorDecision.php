<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CuratorDecision
 *
 * @ORM\Table(name="curator_decision")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CuratorDecisionRepository")
 */
class CuratorDecision
{

    const STATUS_WAIT = 'wait';
    const STATUS_APPROVE = 'approve';
    const STATUS_REJECT = 'reject';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="myDecisions")
     * @ORM\JoinColumn(name="curator_id", referencedColumnName="id")
     */
    private $curator;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="curatorDecisions")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     * @Assert\Choice(choices = {"wait", "approve", "reject"}, message = "Choose a valid status.")
     */
    private $status = self::STATUS_WAIT;

    /**
     * @var string
     *
     * @ORM\Column(name="reject_reason", type="string", length=4000)
     */
    private $rejectReason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return CuratorDecision
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return CuratorDecision
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CuratorDecision
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set rejectReason
     *
     * @param string $rejectReason
     *
     * @return CuratorDecision
     */
    public function setRejectReason($rejectReason)
    {
        $this->rejectReason = $rejectReason;

        return $this;
    }

    /**
     * Get rejectReason
     *
     * @return string
     */
    public function getRejectReason()
    {
        return $this->rejectReason;
    }

    /**
     * Set curator
     *
     * @param \AppBundle\Entity\User $curator
     *
     * @return CuratorDecision
     */
    public function setCurator(\AppBundle\Entity\User $curator = null)
    {
        $this->curator = $curator;

        return $this;
    }

    /**
     * Get curator
     *
     * @return \AppBundle\Entity\User
     */
    public function getCurator()
    {
        return $this->curator;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return CuratorDecision
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
