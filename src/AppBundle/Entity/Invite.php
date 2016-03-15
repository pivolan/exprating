<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Validator\Constraints\UniqueUser;

/**
 * Invite
 *
 * @ORM\Table(name="invite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InviteRepository")
 * @UniqueEntity(fields={"curator", "email"}, message="Вы уже отправляли приглашение на этот адрес")
 * @UniqueUser
 */
class Invite
{
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $hash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActivated", type="boolean")
     */
    private $isActivated = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_from_feedback", type="boolean")
     */
    private $isFromFeedback = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated_at", type="datetime", nullable=true)
     */
    private $activatedAt;

    /**
     * @var string
     * @Assert\Email
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="invites")
     * @ORM\JoinColumn(name="curator_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $curator;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="invites")
     * @ORM\JoinColumn(name="expert_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $expert;

    public function __construct()
    {
        $this->hash = uniqid();
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Invite
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
     * Set isActivated
     *
     * @param boolean $isActivated
     *
     * @return Invite
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return bool
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Set activatedAt
     *
     * @param \DateTime $activatedAt
     *
     * @return Invite
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Get activatedAt
     *
     * @return \DateTime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Invite
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param boolean $isFromFeedback
     *
     * @return $this
     */
    public function setIsFromFeedback($isFromFeedback)
    {
        $this->isFromFeedback = $isFromFeedback;

        return $this;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Get isFromFeedback
     *
     * @return boolean
     */
    public function getIsFromFeedback()
    {
        return $this->isFromFeedback;
    }

    /**
     * Set curator
     *
     * @param \AppBundle\Entity\User $curator
     *
     * @return Invite
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
     * Set expert
     *
     * @param \AppBundle\Entity\User $expert
     *
     * @return Invite
     */
    public function setExpert(\AppBundle\Entity\User $expert = null)
    {
        $this->expert = $expert;

        return $this;
    }

    /**
     * Get expert
     *
     * @return \AppBundle\Entity\User
     */
    public function getExpert()
    {
        return $this->expert;
    }

    public function __toString()
    {
        return $this->getEmail();
    }
}
