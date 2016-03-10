<?php

/**
 * Date: 19.02.16
 * Time: 23:33.
 */

namespace Exprating\FakerBundle\Faker;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Faker\Generator;

class FakeEntitiesGenerator
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     * FakeEntitiesGenerator constructor.
     *
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function user()
    {
        /** @var User $user */
        $user = new User();
        $user->setUsername($this->faker->userName)
            ->setUsernameCanonical($this->faker->userName)
            ->setEmail($this->faker->email)
            ->setEmailCanonical($this->faker->email)
            ->setPlainPassword('qwerty')
            ->setSuperAdmin(false)
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1987-02-08'))
            ->setFullName($this->faker->lastName.' '.$this->faker->firstName)
            ->setCity($this->faker->city)
            ->setCaption($this->faker->title)
            ->addRole(User::ROLE_USER);

        return $user;
    }

    public function product()
    {
        $product = new Product();
        $product->setName($this->faker->title)
            ->setMinPrice($this->faker->randomFloat(2, 100, 10000))
            ->setRating($this->faker->numberBetween(1, 99))
            ->setRating1($this->faker->numberBetween(1, 99))
            ->setRating2($this->faker->numberBetween(1, 99))
            ->setRating3($this->faker->numberBetween(1, 99))
            ->setRating4($this->faker->numberBetween(1, 99))
            ->setIsEnabled(true)
            ->setVisitsCount($this->faker->numberBetween(0, 100))
            ->setEnabledAt(new \DateTime('2016-02-05'))
            ->setSlug($this->faker->slug)
            ->setCategory(null)
            ->setManufacturer(null)
            ->setExpertUser(null)
            ->setExpertComment($this->faker->paragraph(3))
            ->setPreviewImage('http://placehold.it/280x250')
            ->setDisadvantages($this->faker->sentences(5))
            ->setAdvantages($this->faker->sentences(5))
            ->setExpertOpinion($this->faker->paragraphs(6));

        return $product;
    }

    public function category()
    {
        $category = new Category();
        $category->setName($this->faker->title)
            ->setSlug($this->faker->slug);

        return $category;
    }
}
