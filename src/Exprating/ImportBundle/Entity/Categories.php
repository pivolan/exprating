<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories.
 *
 * @ORM\Table(name="categories", indexes={@ORM\Index(name="categories_parentId", columns={"parentId"})})
 * @ORM\Entity(repositoryClass="Exprating\ImportBundle\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @var Categories
     *
     * @ORM\ManyToOne(targetEntity="Exprating\ImportBundle\Entity\Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parentId", referencedColumnName="id")
     * })
     */
    private $parent;

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
     * @var int
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
     * @ORM\JoinTable(name="categories_parameters", joinColumns={@ORM\JoinColumn(name="categoryId",
     *     referencedColumnName="id")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="parameterId", referencedColumnName="id")})
     */
    private $parameters;

    /**
     * @var AliasCategory
     *
     * @ORM\OneToOne(targetEntity="Exprating\ImportBundle\Entity\AliasCategory",
     *     mappedBy="categoryIrecommend", fetch="EAGER")
     */
    private $aliasCategory;

    /**
     * @var Item[]
     *
     * @ORM\OneToMany(targetEntity="Exprating\ImportBundle\Entity\Item", mappedBy="category")
     */
    private $items;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Categories
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
     * Set url.
     *
     * @param string $url
     *
     * @return Categories
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add parameter.
     *
     * @param \Exprating\ImportBundle\Entity\Parameters $parameter
     *
     * @return Categories
     */
    public function addParameter(\Exprating\ImportBundle\Entity\Parameters $parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Remove parameter.
     *
     * @param \Exprating\ImportBundle\Entity\Parameters $parameter
     */
    public function removeParameter(\Exprating\ImportBundle\Entity\Parameters $parameter)
    {
        $this->parameters->removeElement($parameter);
    }

    /**
     * Get parameters.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set parent.
     *
     * @param \Exprating\ImportBundle\Entity\Categories $parent
     *
     * @return Categories
     */
    public function setParent(\Exprating\ImportBundle\Entity\Categories $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \Exprating\ImportBundle\Entity\Categories
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return AliasCategory
     */
    public function getAliasCategory()
    {
        return $this->aliasCategory;
    }

    /**
     * @param AliasCategory $aliasCategory
     *
     * @return $this
     */
    public function setAliasCategory(AliasCategory $aliasCategory)
    {
        $this->aliasCategory = $aliasCategory;
        return $this;
    }

    /**
     * Add item.
     *
     * @param \Exprating\ImportBundle\Entity\Item $item
     *
     * @return Categories
     */
    public function addItem(\Exprating\ImportBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item.
     *
     * @param \Exprating\ImportBundle\Entity\Item $item
     */
    public function removeItem(\Exprating\ImportBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
