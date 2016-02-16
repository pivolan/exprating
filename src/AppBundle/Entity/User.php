<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_EXPERT = 'ROLE_EXPERT';
    const ROLE_EXPERT_CATEGORY_ADMIN = 'ROLE_EXPERT_CATEGORY_ADMIN';
    const ROLE_EXPERT_CURATOR = 'ROLE_EXPERT_CURATOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MODERATOR = 'ROLE_MODERATOR';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", unique=false, nullable=true, options={"comment":"Полное имя Эксперта"})
     */
    private $fullName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true, options={"comment":"Дата рождения эксперта"})
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true, options={"comment":"город. Отображается на странице эксперта"})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", nullable=true, unique=false, options={"comment":"Подпись эксперта, показывается в списке экспертов"})
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_image", type="string", unique=true, nullable=true, options={"comment":"Аватар картинка эксперта"})
     */
    private $avatarImage;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="experts")
     * @ORM\JoinColumn(name="curator_id", referencedColumnName="id")
     */
    private $curator;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="curator")
     */
    private $experts;

    /**
     * @var Product[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="expertUser", cascade={"persist", "detach"})
     */
    private $products;

    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinTable(name="user_category", joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="slug")})
     */
    private $categories;

    public function __construct()
    {
        parent::__construct();

        $this->products = new ArrayCollection();
        $this->experts = new ArrayCollection();
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return User
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarImage()
    {
        return $this->avatarImage;
    }

    /**
     * @param string $avatarImage
     *
     * @return $this
     */
    public function setAvatarImage($avatarImage)
    {
        $this->avatarImage = $avatarImage;
        return $this;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return User
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    public function getAge()
    {
        $date = new \DateTime();
        $diff = $date->diff($this->getBirthday());
        return $diff->y;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return User
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set curator
     *
     * @param \AppBundle\Entity\User $curator
     *
     * @return User
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
     * Add expert
     *
     * @param \AppBundle\Entity\User $expert
     *
     * @return User
     */
    public function addExpert(\AppBundle\Entity\User $expert)
    {
        $this->experts[] = $expert;

        return $this;
    }

    /**
     * Remove expert
     *
     * @param \AppBundle\Entity\User $expert
     */
    public function removeExpert(\AppBundle\Entity\User $expert)
    {
        $this->experts->removeElement($expert);
    }

    /**
     * Get experts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExperts()
    {
        return $this->experts;
    }
}
