<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints\RequestCuratorRightsSpam;

/**
 * RequestCuratorRights
 *
 * @ORM\Table(name="request_curator_rights")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RequestCuratorRightsRepository")
 * @RequestCuratorRightsSpam
 */
class RequestCuratorRights
{
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $expert;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $curator;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * RequestCuratorRights constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RequestCuratorRights
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
     * Set expert
     *
     * @param \AppBundle\Entity\User $expert
     *
     * @return RequestCuratorRights
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

    /**
     * Set curator
     *
     * @param \AppBundle\Entity\User $curator
     *
     * @return RequestCuratorRights
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
}
