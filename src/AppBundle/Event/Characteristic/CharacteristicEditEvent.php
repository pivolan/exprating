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

class CharacteristicEditEvent
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
     * CharacteristicCreateEvent constructor.
     *
     * @param Characteristic $characteristic
     * @param User           $user
     */
    public function __construct(Characteristic $characteristic, User $user)
    {
        $this->characteristic = $characteristic;
        $this->user = $user;
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
}
