<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity
 */
class Item
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryUrl", type="text", nullable=false)
     */
    private $categoryUrl;

    /**
     * @var Categories
     *
     * @ORM\ManyToOne(targetEntity="Exprating\ImportBundle\Entity\Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoryId", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var AliasItem
     *
     * @ORM\OneToOne(targetEntity="Exprating\ImportBundle\Entity\AliasItem", mappedBy="itemIrecommend")
     */
    private $aliasItem;

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="text", nullable=false)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="votesCount", type="text", nullable=false)
     */
    private $votesCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="urlCrc32", type="integer", nullable=false)
     */
    private $urlCrc32;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Parameters[]
     *
     * @ORM\ManyToMany(targetEntity="Exprating\ImportBundle\Entity\Parameters")
     * @ORM\JoinTable(name="item_parameters", joinColumns={@ORM\JoinColumn(name="itemId", referencedColumnName="id")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="parameterId", referencedColumnName="id")})
     */
    private $parameters;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
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
     * Set url
     *
     * @param string $url
     *
     * @return Item
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
     * Set categoryUrl
     *
     * @param string $categoryUrl
     *
     * @return Item
     */
    public function setCategoryUrl($categoryUrl)
    {
        $this->categoryUrl = $categoryUrl;

        return $this;
    }

    /**
     * Get categoryUrl
     *
     * @return string
     */
    public function getCategoryUrl()
    {
        return $this->categoryUrl;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Item
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set votesCount
     *
     * @param string $votesCount
     *
     * @return Item
     */
    public function setVotesCount($votesCount)
    {
        $this->votesCount = $votesCount;

        return $this;
    }

    /**
     * Get votesCount
     *
     * @return string
     */
    public function getVotesCount()
    {
        return $this->votesCount;
    }

    /**
     * Set urlCrc32
     *
     * @param integer $urlCrc32
     *
     * @return Item
     */
    public function setUrlCrc32($urlCrc32)
    {
        $this->urlCrc32 = $urlCrc32;

        return $this;
    }

    /**
     * Get urlCrc32
     *
     * @return integer
     */
    public function getUrlCrc32()
    {
        return $this->urlCrc32;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add parameter
     *
     * @param \Exprating\ImportBundle\Entity\Parameters $parameter
     *
     * @return Item
     */
    public function addParameter(\Exprating\ImportBundle\Entity\Parameters $parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Remove parameter
     *
     * @param \Exprating\ImportBundle\Entity\Parameters $parameter
     */
    public function removeParameter(\Exprating\ImportBundle\Entity\Parameters $parameter)
    {
        $this->parameters->removeElement($parameter);
    }

    /**
     * Get parameters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set category
     *
     * @param \Exprating\ImportBundle\Entity\Categories $category
     *
     * @return Item
     */
    public function setCategory(\Exprating\ImportBundle\Entity\Categories $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Exprating\ImportBundle\Entity\Categories
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return AliasItem
     */
    public function getAliasItem()
    {
        return $this->aliasItem;
    }

    /**
     * @param AliasItem $aliasItem
     */
    public function setAliasItem(AliasItem $aliasItem)
    {
        $this->aliasItem = $aliasItem;
    }
}
