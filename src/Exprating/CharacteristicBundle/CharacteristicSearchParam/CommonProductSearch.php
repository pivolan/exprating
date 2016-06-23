<?php

/**
 * Date: 09.02.16
 * Time: 14:51.
 */

namespace Exprating\CharacteristicBundle\CharacteristicSearchParam;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class CommonProductSearch
{
    /**
     * @var CharacteristicSearchParameter[]|ArrayCollection
     */
    protected $characteristics;
    /**
     * @var string
     * @Assert\Type(type="scalar")
     */
    protected $name;
    /**
     * price less than ot equal.
     *
     * @var float
     * @Assert\Type(type="numeric")
     */
    protected $priceLTE;
    /**
     * price bigger than or equal.
     *
     * @var float
     * @Assert\Type(type="numeric")
     */
    protected $priceGTE;

    public function __construct()
    {
        $this->characteristics = new ArrayCollection();
    }

    /**
     * @return CharacteristicSearchParameter[]
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * @param CharacteristicSearchParameter[] $characteristics
     *
     * @return $this
     */
    public function setCharacteristics($characteristics)
    {
        $this->characteristics = $characteristics;
    }

    /**
     * @param CharacteristicSearchParameter $characteristic
     *
     * @return $this
     */
    public function addCharacteristics(CharacteristicSearchParameter $characteristic)
    {
        $this->characteristics[] = $characteristic;

        return $this;
    }

    /**
     * @param CharacteristicSearchParameter $characteristic
     */
    public function removeCharacteristics(CharacteristicSearchParameter $characteristic)
    {
        $this->characteristics->removeElement($characteristic);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriceGTE()
    {
        return $this->priceGTE;
    }

    /**
     * @param mixed $priceGTE
     *
     * @return $this
     */
    public function setPriceGTE($priceGTE)
    {
        $this->priceGTE = $priceGTE;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriceLTE()
    {
        return $this->priceLTE;
    }

    /**
     * @param float $priceLTE
     *
     * @return $this
     */
    public function setPriceLTE($priceLTE)
    {
        $this->priceLTE = $priceLTE;

        return $this;
    }
}
