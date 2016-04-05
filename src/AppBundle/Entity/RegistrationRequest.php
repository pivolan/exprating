<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Validator\Constraints\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RegistrationRequest
 *
 * @ORM\Table(name="registration_request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistrationRequestRepository")
 * @UniqueEntity(fields={"email"}, message="Вы уже отправляли приглашение на этот адрес")
 * @UniqueUser
 */
class RegistrationRequest
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
     * @var string
     *
     * @Assert\Email
     * @Assert\NotBlank
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_approved", type="boolean",
     *     options={"comment": "Одобрено, отправили инвайт", "default": false})
     */
    private $isApproved = false;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $curator;

    /**
     * @var Category
     *
     * @Assert\NotBlank
     * @Assert\Count(min="1", minMessage="Необходимо выбрать хотя бы одну категорию")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinTable(name="registration_request_category",
     *     joinColumns={@ORM\JoinColumn(name="registration_request_id", referencedColumnName="id",
     *      onDelete="CASCADE")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="slug",
     *      onDelete="CASCADE")})
     */
    private $categories;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->categories = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     *
     * @return RegistrationRequest
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
     * Set message
     *
     * @param string $message
     *
     * @return RegistrationRequest
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RegistrationRequest
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
     * Set curator
     *
     * @param \AppBundle\Entity\User $curator
     *
     * @return RegistrationRequest
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
     * Add category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return RegistrationRequest
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return RegistrationRequest
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }
}
