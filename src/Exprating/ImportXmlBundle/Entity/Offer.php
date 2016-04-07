<?php

namespace Exprating\ImportXmlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * offer
 *
 * @ORM\Table(name="offer",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="id_company_unique", columns={"id", "company"})})
 * @ORM\Entity(repositoryClass="Exprating\ImportXmlBundle\Repository\offerRepository")
 */
class Offer
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="string")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string")
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=1000)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="oldPrice", type="integer", nullable=true)
     */
    private $oldPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true)
     */
    private $categoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=255, nullable=true)
     */
    private $categoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="category_path", type="string", length=4000, nullable=true)
     */
    private $categoryPath;

    /**
     * @var string
     *
     * @ORM\Column(name="market_category", type="string", length=4000, nullable=true)
     */
    private $marketCategory;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount;

    /**
     * @var bool
     *
     * @ORM\Column(name="available", type="boolean")
     */
    private $available = true;

    /**
     * @var array
     *
     * @ORM\Column(name="params", type="json_array", nullable=true)
     */
    private $params;

    /**
     * @var array
     *
     * @ORM\Column(name="pictures", type="json_array", nullable=true)
     */
    private $pictures;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=255, nullable=true)
     */
    private $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_code", type="string", length=255, nullable=true)
     */
    private $vendorCode;

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Offer
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Offer
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Offer
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Offer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Offer
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set oldPrice
     *
     * @param integer $oldPrice
     *
     * @return Offer
     */
    public function setOldPrice($oldPrice)
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    /**
     * Get oldPrice
     *
     * @return integer
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return Offer
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return Offer
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set categoryPath
     *
     * @param string $categoryPath
     *
     * @return Offer
     */
    public function setCategoryPath($categoryPath)
    {
        $this->categoryPath = $categoryPath;

        return $this;
    }

    /**
     * Get categoryPath
     *
     * @return string
     */
    public function getCategoryPath()
    {
        return $this->categoryPath;
    }

    /**
     * Set marketCategory
     *
     * @param string $marketCategory
     *
     * @return Offer
     */
    public function setMarketCategory($marketCategory)
    {
        $this->marketCategory = $marketCategory;

        return $this;
    }

    /**
     * Get marketCategory
     *
     * @return string
     */
    public function getMarketCategory()
    {
        return $this->marketCategory;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Offer
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set available
     *
     * @param boolean $available
     *
     * @return Offer
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return boolean
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set params
     *
     * @param array $params
     *
     * @return Offer
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set pictures
     *
     * @param array $pictures
     *
     * @return Offer
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get pictures
     *
     * @return array
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Offer
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set vendor
     *
     * @param string $vendor
     *
     * @return Offer
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set vendorCode
     *
     * @param string $vendorCode
     *
     * @return Offer
     */
    public function setVendorCode($vendorCode)
    {
        $this->vendorCode = $vendorCode;

        return $this;
    }

    /**
     * Get vendorCode
     *
     * @return string
     */
    public function getVendorCode()
    {
        return $this->vendorCode;
    }
}
