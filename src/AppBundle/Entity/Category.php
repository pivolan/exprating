<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exprating\CharacteristicBundle\Entity\CategoryCharacteristic;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category.
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    const ROOT_SLUG = 'root';

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, unique=false, options={"comment":"Название категории"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="slug", type="string", length=255,
     *     options={"comment":"уникальное название на латинице. Используется для ссылки"})
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", options={"comment":"Поле формируется автоматически для дерева."})
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer",
     *     options={"comment":"Уровень вложенности внутри дерева. Формируется автоматически."})
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
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="slug", onDelete="SET NULL")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="slug", onDelete="SET NULL")
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
     * @Assert\Valid
     */
    private $ratingSettings;

    /**
     * @var Seo
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Seo", mappedBy="category", cascade="ALL")
     * @Assert\Valid
     */
    private $seo;

    /**
     * @var CategoryCharacteristic[]
     *
     * @ORM\OneToMany(targetEntity="Exprating\CharacteristicBundle\Entity\CategoryCharacteristic", mappedBy="category")
     */
    private $categoryCharacteristics;

    /**
     * @var User[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="adminCategories")
     */
    private $admins;

    /**
     * @var PeopleGroup[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\PeopleGroup")
     * @ORM\JoinTable(name="category_people_group", joinColumns={@ORM\JoinColumn(name="category_id",
     *     referencedColumnName="slug", onDelete="CASCADE")},
     *                    inverseJoinColumns={@ORM\JoinColumn(name="people_group_id", referencedColumnName="slug",
     * onDelete="CASCADE")})
     */
    private $peopleGroups;

    /**
     * @var @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="category", fetch="EXTRA_LAZY")
     */
    private $products;

    /**
     * @var User
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="categories")
     */
    private $experts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoryCharacteristics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleGroups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->experts = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set name.
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
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug.
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
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set lft.
     *
     * @param int $lft
     *
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft.
     *
     * @return int
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl.
     *
     * @param int $lvl
     *
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl.
     *
     * @return int
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt.
     *
     * @param int $rgt
     *
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt.
     *
     * @return int
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root.
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
     * Add child.
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
     * Remove child.
     *
     * @param \AppBundle\Entity\Category $child
     */
    public function removeChild(\AppBundle\Entity\Category $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection|self[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->getSlug();
    }

    /**
     * Add characteristic.
     *
     * @param \Exprating\CharacteristicBundle\Entity\CategoryCharacteristic $characteristic
     *
     * @return Category
     */
    public function addCharacteristic(\Exprating\CharacteristicBundle\Entity\CategoryCharacteristic $characteristic)
    {
        $this->categoryCharacteristics[] = $characteristic;

        return $this;
    }

    /**
     * Remove characteristic.
     *
     * @param \Exprating\CharacteristicBundle\Entity\CategoryCharacteristic $characteristic
     */
    public function removeCharacteristic(\Exprating\CharacteristicBundle\Entity\CategoryCharacteristic $characteristic)
    {
        $this->categoryCharacteristics->removeElement($characteristic);
    }

    /**
     * Get characteristics.
     *
     * @return \Doctrine\Common\Collections\Collection | CategoryCharacteristic[]
     */
    public function getCategoryCharacteristics()
    {
        return $this->categoryCharacteristics;
    }

    /**
     * Set ratingSettings.
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
     * Get ratingSettings.
     *
     * @return \AppBundle\Entity\RatingSettings
     */
    public function getRatingSettings()
    {
        return $this->ratingSettings;
    }

    /**
     * Add admin.
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
     * Remove admin.
     *
     * @param \AppBundle\Entity\User $admin
     */
    public function removeAdmin(\AppBundle\Entity\User $admin)
    {
        $this->admins->removeElement($admin);
    }

    /**
     * Get admins.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    /**
     * Add peopleGroup.
     *
     * @param \AppBundle\Entity\PeopleGroup $peopleGroup
     *
     * @return Category
     */
    public function addPeopleGroup(\AppBundle\Entity\PeopleGroup $peopleGroup)
    {
        $this->peopleGroups[] = $peopleGroup;

        return $this;
    }

    /**
     * Remove peopleGroup.
     *
     * @param \AppBundle\Entity\PeopleGroup $peopleGroup
     */
    public function removePeopleGroup(\AppBundle\Entity\PeopleGroup $peopleGroup)
    {
        $this->peopleGroups->removeElement($peopleGroup);
    }

    /**
     * Get peopleGroups.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeopleGroups()
    {
        return $this->peopleGroups;
    }

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add expert
     *
     * @param \AppBundle\Entity\User $expert
     *
     * @return Category
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

    /**
     * @return Seo
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * @param Seo $seo
     *
     * @return $this
     */
    public function setSeo(Seo $seo)
    {
        $this->seo = $seo;

        return $this;
    }
}
