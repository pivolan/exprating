<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Category
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, unique=false, options={"comment":"Название категории"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="slug", type="string", length=255, options={"comment":"уникальное название на латинице. Используется для ссылки"})
     */
    private $slug;


    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", options={"comment":"Поле формируется автоматически для дерева."})
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", options={"comment":"Уровень вложенности внутри дерева. Формируется автоматически."})
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", options={"comment":"Полу формируется автоматически для дерева."})
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="slug", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="slug", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var RatingSettings
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\RatingSettings", mappedBy="category")
     */
    private $ratingSettings;

    /**
     * @var Characteristic[]
     *
     * @ORM\ManyToMany(targetEntity="Exprating\CharacteristicBundle\Entity\Characteristic")
     * @ORM\JoinTable(name="category_characteristic", joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="slug")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="characteristic_id", referencedColumnName="slug")})
     */
    private $characteristics;

    /**
     * @var User[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="adminCategories")
     */
    private $admins;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->characteristics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param \AppBundle\Entity\Category $root
     *
     * @return Category
     */
    public function setRoot(\AppBundle\Entity\Category $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\AppBundle\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Category $child
     */
    public function removeChild(\AppBundle\Entity\Category $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection|self[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add characteristic
     *
     * @param \Exprating\CharacteristicBundle\Entity\Characteristic $characteristic
     *
     * @return Category
     */
    public function addCharacteristic(\Exprating\CharacteristicBundle\Entity\Characteristic $characteristic)
    {
        $this->characteristics[] = $characteristic;

        return $this;
    }

    /**
     * Remove characteristic
     *
     * @param \Exprating\CharacteristicBundle\Entity\Characteristic $characteristic
     */
    public function removeCharacteristic(\Exprating\CharacteristicBundle\Entity\Characteristic $characteristic)
    {
        $this->characteristics->removeElement($characteristic);
    }

    /**
     * Get characteristics
     *
     * @return \Doctrine\Common\Collections\Collection | Characteristic[]
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * Set ratingSettings
     *
     * @param \AppBundle\Entity\RatingSettings $ratingSettings
     *
     * @return Category
     */
    public function setRatingSettings(\AppBundle\Entity\RatingSettings $ratingSettings = null)
    {
        $this->ratingSettings = $ratingSettings;

        return $this;
    }

    /**
     * Get ratingSettings
     *
     * @return \AppBundle\Entity\RatingSettings
     */
    public function getRatingSettings()
    {
        return $this->ratingSettings;
    }

    /**
     * Add admin
     *
     * @param \AppBundle\Entity\User $admin
     *
     * @return Category
     */
    public function addAdmin(\AppBundle\Entity\User $admin)
    {
        $this->admins[] = $admin;

        return $this;
    }

    /**
     * Remove admin
     *
     * @param \AppBundle\Entity\User $admin
     */
    public function removeAdmin(\AppBundle\Entity\User $admin)
    {
        $this->admins->removeElement($admin);
    }

    /**
     * Get admins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdmins()
    {
        return $this->admins;
    }
}
