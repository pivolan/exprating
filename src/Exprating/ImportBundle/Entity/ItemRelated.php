<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemRelated
 *
 * @ORM\Table(name="item_related")
 * @ORM\Entity
 */
class ItemRelated
{
    /**
     * @var Item
     *
     * @ORM\Column(name="relatedItemId", type="integer", nullable=true)
     * @ORM\ManyToOne(targetEntity="Exprating\ImportBundle\Entity\Item")
     * @ORM\JoinColumn(name="relatedItemId", referencedColumnName="id")
     * @ORM\Id
     */
    private $relatedItem;

    /**
     * @var Item
     *
     * @ORM\Column(name="itemId", type="integer")
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Exprating\ImportBundle\Entity\Item")
     * @ORM\JoinColumn(name="itemId", referencedColumnName="id")
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="relatedItemUrl", type="text")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $relatedItemUrl;

    /**
     * Set relatedItem
     *
     * @param integer $relatedItem
     *
     * @return ItemRelated
     */
    public function setRelatedItem($relatedItem)
    {
        $this->relatedItem = $relatedItem;

        return $this;
    }

    /**
     * Get relatedItem
     *
     * @return integer
     */
    public function getRelatedItem()
    {
        return $this->relatedItem;
    }

    /**
     * Set item
     *
     * @param integer $item
     *
     * @return ItemRelated
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return integer
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set relatedItemUrl
     *
     * @param string $relatedItemUrl
     *
     * @return ItemRelated
     */
    public function setRelatedItemUrl($relatedItemUrl)
    {
        $this->relatedItemUrl = $relatedItemUrl;

        return $this;
    }

    /**
     * Get relatedItemUrl
     *
     * @return string
     */
    public function getRelatedItemUrl()
    {
        return $this->relatedItemUrl;
    }
}
