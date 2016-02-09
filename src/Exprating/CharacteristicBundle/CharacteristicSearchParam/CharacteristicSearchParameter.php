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
    }

    /**
     * @return mixed
     */
    public function getValueGTE()
    {
        return $this->valueGTE;
    }

    /**
     * @param mixed $valueGTE
     */
    public function setValueGTE($valueGTE)
    {
        $this->valueGTE = $valueGTE;
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
    }
} 