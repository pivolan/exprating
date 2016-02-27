<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasCategory
 *
 * @ORM\Table(name="alias_category")
 * @ORM\Entity(repositoryClass="Exprating\ImportBundle\Repository\AliasCategoryRepository")
 */
class AliasCategory
{
    /**
     * @var string
     * @ORM\Column(name="category_exprating_id", type="string", length=255, unique=true)
     */
    private $categoryExpratingId;

    /**
     * @var Categories
     *
     * @ORM\ManyToOne(targetEntity="Exprating\ImportBundle\Entity\Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_irecommend_id", referencedColumnName="id")
     * })
     */
    private $categoryIrecommend;


    /**
     * Set categoryIrecommend
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
     * Get categoryIrecommend
     *
     * @return string
     */
    public function getCategoryIrecommend()
    {
        return $this->categoryIrecommend;
    }
}

