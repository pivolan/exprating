<?php

/**
 * Date: 08.02.16
 * Time: 12:58.
 */

namespace AppBundle\ProductFilter;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AcmeAssert;

/**
 * Class ProductFilter.
 *
 * @Assert\GroupSequence({"ProductFilter", "After"})
 * @AcmeAssert\FilterAccessRights(groups={"After"})
 */
class ProductFilter
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    const FIELD_MIN_PRICE = 'minPrice';
    const FIELD_ENABLED_AT = 'enabledAt';
    const FIELD_RATING = 'rating';

    const STATUS_WAIT = 'wait';
    const STATUS_FREE = 'free';
    const STATUS_ALL = 'all';

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"minPrice", "enabledAt", "rating"}, message = "Выберите верный тип сортировки")
     * @Assert\NotBlank()
     */
    protected $sortField = self::FIELD_MIN_PRICE;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"free", "wait", "all"}, message = "Выберите верный фильтр")
     */
    protected $status;

    /**
     * @var string
     * @Assert\Choice(choices = {"ASC", "DESC"}, message = "Выберите верное направление сортировки")
     * @Assert\NotBlank()
     */
    protected $sortDirection = self::DIRECTION_ASC;

    public $page;

    /**
     * @var User
     */
    protected $curator;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @return mixed
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * @param mixed $sortDirection
     *
     * @return $this
     */
    public function setSortDirection($sortDirection)
    {
        $this->sortDirection = $sortDirection;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return $this->sortField;
    }

    /**
     * @param string $sortField
     *
     * @return $this
     */
    public function setSortField($sortField)
    {
        $this->sortField = $sortField;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User
     */
    public function getCurator()
    {
        return $this->curator;
    }

    /**
     * @param User $curator
     *
     * @return $this
     */
    public function setCurator(User $curator = null)
    {
        $this->curator = $curator;

        return $this;
    }
}
