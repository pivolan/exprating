<?php
/**
 * Date: 09.02.16
 * Time: 14:51
 */

namespace Exprating\CharacteristicBundle\CharacteristicSearchParam;

use Symfony\Component\Validator\Constraints as Assert;

class CharacteristicSearchParameter
{
    /**
     * @var string
     * @Assert\Type(type="scalar")
     */
    protected $name;
    /**
     * @var string
     * @Assert\Type(type="scalar")
     */
    protected $value;
    /**
     * @var float
     * @Assert\Type(type="numeric")
     */
    protected $valueLTE;

    /**
     * @var float
     * @Assert\Type(type="numeric")
     */
    protected $valueGTE;

    /**
     * @var string
     *
     * @Assert\Choice(choices = {"string", "integer", "decimal"}, message = "Choose a valid type.")
     */
    protected $type;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueGTE()
    {
        return $this->valueGTE;
    }

    /**
     * @param float $valueGTE
     *
     * @return $this
     */
    public function setValueGTE($valueGTE)
    {
        $this->valueGTE = $valueGTE;
        return $this;
    }

    /**
     * @return float
     */
    public function getValueLTE()
    {
        return $this->valueLTE;
    }

    /**
     * @param float $valueLTE
     */
    public function setValueLTE($valueLTE)
    {
        $this->valueLTE = $valueLTE;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
} 