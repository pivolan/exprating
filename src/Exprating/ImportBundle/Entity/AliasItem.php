<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasItem.
 *
 * @ORM\Table(name="alias_item")
 * @ORM\Entity(repositoryClass="Exprating\ImportBundle\Repository\AliasItemRepository")
 */
class AliasItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="item_exprating_id", type="bigint", unique=true)
     */
    private $itemExpratingId;

    /**
     * @var string
     *
     * @ORM\Column(name="item_exprating_name", type="string", length=255)
     */
    private $itemExpratingName;

    /**
     * @var string
     *
     * @ORM\Column(name="item_exprating_slug", type="string", length=255)
     */
    private $itemExpratingSlug;

    /**
     * @var Item
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Exprating\ImportBundle\Entity\Item", inversedBy="aliasItem", cascade={"ALL"})
     * @ORM\JoinColumn(name="item_irecommend_id", referencedColumnName="id")
     */
    private $itemIrecommend;

    /**
     * Set itemExpratingId.
     *
     * @param int $itemExpratingId
     *
     * @return AliasItem
     */
    public function setItemExpratingId($itemExpratingId)
    {
        $this->itemExpratingId = $itemExpratingId;

        return $this;
    }

    /**
     * Get itemExpratingId.
     *
     * @return int
     */
    public function getItemExpratingId()
    {
        return $this->itemExpratingId;
    }

    /**
     * Set itemExpratingName.
     *
     * @param string $itemExpratingName
     *
     * @return AliasItem
     */
    public function setItemExpratingName($itemExpratingName)
    {
        $this->itemExpratingName = $itemExpratingName;

        return $this;
    }

    /**
     * Get itemExpratingName.
     *
     * @return string
     */
    public function getItemExpratingName()
    {
        return $this->itemExpratingName;
    }

    /**
     * Set itemExpratingSlug.
     *
     * @param string $itemExpratingSlug
     *
     * @return AliasItem
     */
    public function setItemExpratingSlug($itemExpratingSlug)
    {
        $this->itemExpratingSlug = $itemExpratingSlug;

        return $this;
    }

    /**
     * Get itemExpratingSlug.
     *
     * @return string
     */
    public function getItemExpratingSlug()
    {
        return $this->itemExpratingSlug;
    }

    /**
     * Set itemIrecommend.
     *
     * @param string $itemIrecommend
     *
     * @return AliasItem
     */
    public function setItemIrecommend($itemIrecommend)
    {
        $this->itemIrecommend = $itemIrecommend;

        return $this;
    }

    /**
     * Get itemIrecommend.
     *
     * @return string
     */
    public function getItemIrecommend()
    {
        return $this->itemIrecommend;
    }
}
