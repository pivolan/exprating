<?php
/**
 * Date: 08.02.16
 * Time: 12:58
 */

namespace AppBundle\ProductFilter;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AcmeAssert;

/**
 * Class ProductFilter
 * @package AppBundle\ProductFilter
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

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"minPrice", "enabledAt", "rating"}, message = "Выберите верный тип сортировки")
     * @Assert\NotBlank()
     */
    protected $fieldName = self::FIELD_MIN_PRICE;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"free", "wait"}, message = "Выберите верный фильтр")
     */
    protected $status;

    /**
     * @var string
     * @Assert\Choice(choices = {"ASC", "DESC"}, message = "Выберите верное направление сортировки")
     * @Assert\NotBlank()
     */
    protected $direction = self::DIRECTION_ASC;

    /**
     * @var User
     */
    protected $user;

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

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

} 