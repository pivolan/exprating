<?php

namespace Exprating\CharacteristicBundle\Entity;

use AppBundle\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductCharacteristic.
 *
 * @UniqueEntity(fields={"product", "characteristic"})
 * @ORM\Table(name="product_characteristic", uniqueConstraints={@ORM\uniqueConstraint(name="product_characteristic", columns={"product_id", "characteristic_id"})})
 * @ORM\Entity(repositoryClass="Exprating\CharacteristicBundle\Repository\ProductCharacteristicRepository")
 */
class ProductCharacteristic
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="bigint")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value_string", type="string", length=255, nullable=true, options={"comment":"Значение характеристики товара. Строка"})
     */
    private $valueString;

    /**
     * @var int
     *
     * @ORM\Column(name="value_int", type="integer", nullable=true, options={"comment":"Значение характеристики товара. целочисленное"})
     * @Assert\Type(type="numeric")
     */
    private $valueInt;

    /**
     * @var string
     *
     * @ORM\Column(name="value_decimal", type="decimal", precision=10, scale=2, nullable=true, options={"comment":"Значение характеристики товара. Цифра 0,00"})
     * @Assert\Type(type="numeric")
     */
    private $valueDecimal;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="productCharacteristics")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var Characteristic
     *
     * @ORM\ManyToOne(targetEntity="Exprating\CharacteristicBundle\Entity\Characteristic", fetch="EAGER")
     * @ORM\JoinColumn(name="characteristic_id", referencedColumnName="slug")
     */
    private $characteristic;

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return ProductCharacteristic
     */
    public function setValueString($value)
    {
        $this->valueString = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValueString()
    {
        return $this->valueString;
    }

    /**
     * Set valueInt.
     *
     * @param int $valueInt
     *
     * @return ProductCharacteristic
     */
    public function setValueInt($valueInt)
    {
        $this->valueInt = $valueInt;

        return $this;
    }

    /**
     * Get valueInt.
     *
     * @return int
     */
    public function getValueInt()
    {
        return $this->valueInt;
    }

    /**
     * Set valueDecimal.
     *
     * @param string $valueDecimal
     *
     * @return ProductCharacteristic
     */
    public function setValueDecimal($valueDecimal)
    {
        $this->valueDecimal = $valueDecimal;

        return $this;
    }

    /**
     * Get valueDecimal.
     *
     * @return string
     */
    public function getValueDecimal()
    {
        return $this->valueDecimal;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return ProductCharacteristic
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set characteristic.
     *
     * @param \Exprating\CharacteristicBundle\Entity\Characteristic $characteristic
     *
     * @return ProductCharacteristic
     */
    public function setCharacteristic(\Exprating\CharacteristicBundle\Entity\Characteristic $characteristic = null)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic.
     *
     * @return \Exprating\CharacteristicBundle\Entity\Characteristic
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if (empty($this->getCharacteristic())) {
            return $this->getValueString();
        }

        switch ($this->getCharacteristic()->getType()) {
            case Characteristic::TYPE_STRING:
                return (string)$this->getValueString();
            case Characteristic::TYPE_INT:
                return (string)$this->getValueInt();
            case Characteristic::TYPE_DECIMAL:
                return (string)$this->getValueDecimal();
            default:
                throw new CharacteristicTypeException($this->getCharacteristic()->getType());
        }
    }

    /**
     * @return self
     */
    public function setValue($value)
    {
        if (empty($this->getCharacteristic())) {
            $this->setValueString($value);

            return $this;
        }
        switch ($this->getCharacteristic()->getType()) {
            case Characteristic::TYPE_STRING:
                $this->setValueString($value);
                break;
            case Characteristic::TYPE_INT:
                $this->setValueInt($value);
                break;
            case Characteristic::TYPE_DECIMAL:
                $this->setValueDecimal($value);
                break;
            default:
                throw new CharacteristicTypeException($this->getCharacteristic()->getType());
        }

        return $this;
    }

    /**
     * Set id.
     *
     * @param string $id
     *
     * @return ProductCharacteristic
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->characteristic->getName();
    }
}
