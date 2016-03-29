<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasCategory.
 *
 * @ORM\Table(name="alias_category")
 * @ORM\Entity(repositoryClass="Exprating\ImportBundle\Repository\AliasCategoryRepository")
 */
class AliasCategory
{
    const PEOPLE_GROUP_WOMAN = 'woman';
    const PEOPLE_GROUP_MAN = 'man';
    const PEOPLE_GROUP_CHILD = 'child';
    const PEOPLE_GROUP_ALL = 'all';
    /**
     * @var string
     * @ORM\Column(name="category_exprating_id", type="string", length=255, unique=false)
     */
    private $categoryExpratingId;

    /**
     * @var Categories
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Exprating\ImportBundle\Entity\Categories", inversedBy="aliasCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_irecommend_id", referencedColumnName="id")
     * })
     */
    private $categoryIrecommend;

    /**
     * Set categoryIrecommend.
     *
     * @param Categories $categoryIrecommend
     *
     * @return AliasCategory
     */
    public function setCategoryIrecommend(Categories $categoryIrecommend)
    {
        $this->categoryIrecommend = $categoryIrecommend;

        return $this;
    }

    /**
     * Get categoryIrecommend.
     *
     * @return Categories
     */
    public function getCategoryIrecommend()
    {
        return $this->categoryIrecommend;
    }

    /**
     * @return string
     */
    public function getCategoryExpratingId()
    {
        return $this->categoryExpratingId;
    }

    /**
     * @param string $categoryExpratingId
     *
     * @return $this
     */
    public function setCategoryExpratingId($categoryExpratingId)
    {
        $this->categoryExpratingId = $categoryExpratingId;

        return $this;
    }
}
