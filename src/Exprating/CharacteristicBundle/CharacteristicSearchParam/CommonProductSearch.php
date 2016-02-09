<?php
/**
 * Date: 09.02.16
 * Time: 14:51
 */

namespace Exprating\CharacteristicBundle\CharacteristicSearchParam;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class CommonProductSearch
{
    /**
     * @var CharacteristicSearchParameter[]|ArrayCollection
     * @Assert\Type(type="array")
     *
     */
    protected $characteristics;
    /**
     * @var string
     * @Assert\Type(type="scalar")
     */
    protected $name;
    /**
     * price less than ot equal
     * @var float
     * @Assert\Type(type="numeric")
     */
    protected $priceLTE;
    /**
     * price bigger than or equal
     * @var float
     * @Assert\Type(type="numeric")
     */
    protected $priceGTE;

    function __construct()
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
     */
    public function addCharacteristics(CharacteristicSearchParameter $characteristic)
    {
        $this->characteristics[] = $characteristic;
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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     */
    public function setPriceGTE($priceGTE)
    {
        $this->priceGTE = $priceGTE;
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
     */
    public function setPriceLTE($priceLTE)
    {
        $this->priceLTE = $priceLTE;
    }
}