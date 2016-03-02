<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image.
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="filename", type="string", length=255,
     *     options={"comment":"Уникальное имя файла без пути"})
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true,
     *     options={"comment":"Заполнения для тега в картинке"})
     */
    private $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, options={"comment":"Имя картинки"})
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_main", type="boolean", options={"default": 0},
     *     options={"comment":"На странице товара эта картинка будет отображаться первой в списке"})
     */
    private $isMain = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"comment":"Дата создания картинки"})
     */
    private $createdAt;

    /**
     * @var User
     *
     * /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Set filename.
     *
     * @param string $filename
     *
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Image
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Image
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param bool $isMain
     *
     * @return $this
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;

        return $this;
    }

    public function __toString()
    {
        return $this->filename;
    }

    /**
     * Get isMain.
     *
     * @return bool
     */
    public function getIsMain()
    {
        return $this->isMain;
    }
}
