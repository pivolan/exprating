<?php

namespace Exprating\CharacteristicBundle\Entity;

use AppBundle\Entity\Category;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryCharacteristic
 *
 * @ORM\Table(name="category_characteristic", uniqueConstraints={@ORM\uniqueConstraint(name="category_characteristic",
 *     columns={"category_id", "characteristic_id"})})
 * @ORM\Entity(repositoryClass="Exprating\CharacteristicBundle\Repository\CategoryCharacteristicRepository")
 */
class CategoryCharacteristic
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="bigint")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="head_group", type="string", length=255, nullable=true)
     */
    private $headGroup = 'Основные характеристики';

    /**
     * @var int
     *
     * @ORM\Column(name="order_index", type="integer")
     */
    private $orderIndex = 0;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="categoryCharacteristics")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="slug", onDelete="CASCADE", nullable=false)
     */
    private $category;

    /**
     * @var Characteristic
     * @ORM\ManyToOne(targetEntity="Exprating\CharacteristicBundle\Entity\Characteristic", fetch="EAGER")
     * @ORM\JoinColumn(name="characteristic_id", referencedColumnName="slug", onDelete="CASCADE", nullable=false)
     */
    private $characteristic;

    /**
     * Set headGroup
     *
     * @param string $headGroup
     *
     * @return CategoryCharacteristic
     */
    public function setHeadGroup($headGroup)
    {
        $this->headGroup = $headGroup;

        return $this;
    }

    /**
     * Get headGroup
     *
     * @return string
     */
    public function getHeadGroup()
    {
        return $this->headGroup;
    }

    /**
     * Set orderIndex
     *
     * @param integer $orderIndex
     *
     * @return CategoryCharacteristic
     */
    public function setOrderIndex($orderIndex)
    {
        $this->orderIndex = $orderIndex;

        return $this;
    }

    /**
     * Get orderIndex
     *
     * @return int
     */
    public function getOrderIndex()
    {
        return $this->orderIndex;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return CategoryCharacteristic
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set characteristic
     *
     * @param \Exprating\CharacteristicBundle\Entity\Characteristic $characteristic
     *
     * @return CategoryCharacteristic
     */
    public function setCharacteristic(\Exprating\CharacteristicBundle\Entity\Characteristic $characteristic = null)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic
     *
     * @return \Exprating\CharacteristicBundle\Entity\Characteristic
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getHeadGroup();
    }
}
