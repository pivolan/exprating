<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", unique=false, nullable=true)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_image", type="string", unique=true, nullable=true)
     */
    private $avatarImage;

    /**
     * @var Product[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="expertUser", cascade={"persist", "detach"})
     */
    private $products;

    public function __construct()
    {
        parent::__construct();

        $this->products = new ArrayCollection();
        // your own logic
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
}
