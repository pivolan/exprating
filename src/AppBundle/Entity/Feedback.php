<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeedbackRepository")
 */
class Feedback
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
     * @ORM\Column(name="advantages", type="string", length=4000, nullable=true, options={"comment":"Достоинства товара"})
     */
    private $advantages;

    /**
     * @var string
     *
     * @ORM\Column(name="disadvantages", type="string", length=4000, nullable=true, options={"comment":"Недостатки товара"})
     */
    private $disadvantages;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", options={"comment":"Комментарий"})
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_like", type="boolean", nullable=true, options={"comment":"Понравился ли товар"})
     */
    private $isLike;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, options={"comment":"Полное имя автора отзыва"})
     */
    private $fullName;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_published", type="boolean", options={"comment": "Опубликован ли фидбэк, или ожидает решения модератора", "default": false})
     */
    private $isPublished = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"comment":"Дата создания отзыва"})
     */
    private $createdAt;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

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
     * Set advantages
     *
     * @param string $advantages
     *
     * @return Feedback
     */
    public function setAdvantages($advantages)
    {
        $this->advantages = $advantages;

        return $this;
    }

    /**
     * Get advantages
     *
     * @return string
     */
    public function getAdvantages()
    {
        return $this->advantages;
    }

    /**
     * Set disadvantages
     *
     * @param string $disadvantages
     *
     * @return Feedback
     */
    public function setDisadvantages($disadvantages)
    {
        $this->disadvantages = $disadvantages;

        return $this;
    }

    /**
     * Get disadvantages
     *
     * @return string
     */
    public function getDisadvantages()
    {
        return $this->disadvantages;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Feedback
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set isLike
     *
     * @param boolean $isLike
     *
     * @return Feedback
     */
    public function setIsLike($isLike)
    {
        $this->isLike = $isLike;

        return $this;
    }

    /**
     * Get isLike
     *
     * @return bool
     */
    public function getIsLike()
    {
        return $this->isLike;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Feedback
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Feedback
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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Feedback
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

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return Feedback
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }
}
