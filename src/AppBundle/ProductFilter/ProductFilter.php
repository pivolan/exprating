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

    const STATUS_WAIT = 'STATUS_WAIT';
    const STATUS_FREE = 'STATUS_FREE';
    const STATUS_ALL = 'STATUS_ALL';

    const LIST_TYPE_ICON = 'icon';
    const LIST_TYPE_LINE = 'line';

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
     * @Assert\Choice(choices = {"STATUS_FREE", "STATUS_WAIT", "STATUS_ALL"}, message = "Выберите верный фильтр")
     */
    protected $status = self::STATUS_ALL;

    /**
     * @var string
     * @Assert\Choice(choices = {"ASC", "DESC"}, message = "Выберите верное направление сортировки")
     * @Assert\NotBlank()
     */
    protected $sortDirection = self::DIRECTION_ASC;

    /**
     * @var string
     * @Assert\Choice(choices = {"icon", "line"}, message = "Выберите верное отображение списка")
     * @Assert\NotBlank()
     */
    protected $listType = self::LIST_TYPE_ICON;

    /**
     * @var string
     */
    protected $searchString;

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

    /**
     * @return string
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @param string $listType
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @param string $searchString
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
    }
}
