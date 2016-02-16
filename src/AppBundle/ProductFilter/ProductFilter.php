<?php
/**
 * Date: 08.02.16
 * Time: 12:58
 */

namespace AppBundle\ProductFilter;

use AppBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFilter
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    const FIELD_MIN_PRICE = 'minPrice';
    const FIELD_ENABLED_AT = 'enabledAt';
    const FIELD_RATING = 'rating';

    const STATUS_WAIT = 'wait';
    const STATUS_FREE = 'free';

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"minPrice", "enabledAt", "rating"}, message = "Выберите верный тип сортировки")
     */
    protected $fieldName;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"free", "wait"}, message = "Выберите верный фильтр")
     */
    protected $status;

    /**
     * @var string
     * @Assert\Choice(choices = {"ASC", "DESC"}, message = "Выберите верное направление сортировки")
     */
    protected $direction;

    /**
     * @var Category
     * @Assert\NotBlank()
     */
    protected $category;

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     *
     * @return $this
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     *
     * @return $this
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
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

} 