<?php
/**
 * Date: 17.03.16
 * Time: 0:43
 */

namespace AppBundle\Event\Characteristic;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Symfony\Component\EventDispatcher\Event;

class CharacteristicCreateEvent extends Event
{
    /**
     * @var Characteristic
     */
    protected $characteristic;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var Product
     */
    protected $product;

    /**
     * CharacteristicCreateEvent constructor.
     *
     * @param Characteristic $characteristic
     * @param User           $user
     * @param Category       $category
     * @param Product        $product
     */
    public function __construct(Characteristic $characteristic, User $user, Category $category, Product $product = null)
    {
        $this->characteristic = $characteristic;
        $this->user = $user;
        $this->category = $category;
        $this->product = $product;
    }

    /**
     * @return Characteristic
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
