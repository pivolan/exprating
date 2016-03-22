<?php
/**
 * Date: 23.03.16
 * Time: 0:54
 */

namespace AppBundle\Event\Category;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CategoryCreateEvent extends Event
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var User
     */
    private $user;

    /**
     * CategoryCreateEvent constructor.
     *
     * @param Category $category
     * @param User     $user
     */
    public function __construct(Category $category, User $user)
    {
        $this->category = $category;
        $this->user = $user;
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
}
