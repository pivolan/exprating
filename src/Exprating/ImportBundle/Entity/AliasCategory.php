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
     * @var string
     *
     * @ORM\Column(name="people_group", type="string", length=255, nullable=true)
     */
    private $peopleGroup = self::PEOPLE_GROUP_ALL;

    /**
     * Set categoryIrecommend.
     *
     * @param string $categoryIrecommend
     *
     * @return AliasCategory
     */
    public function setCategoryIrecommend($categoryIrecommend)
    {
        $this->categoryIrecommend = $categoryIrecommend;

        return $this;
    }

    /**
     * Get categoryIrecommend.
     *
     * @return string
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
     */
    public function setCategoryExpratingId($categoryExpratingId)
    {
        $this->categoryExpratingId = $categoryExpratingId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPeopleGroup()
    {
        return $this->peopleGroup;
    }

    /**
     * @param string $peopleGroup
     *
     * @return $this
     */
    public function setPeopleGroup($peopleGroup)
    {
        $this->peopleGroup = $peopleGroup;

        return $this;
    }
}
