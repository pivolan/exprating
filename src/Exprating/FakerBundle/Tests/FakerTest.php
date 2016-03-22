<?php
/**
 * Date: 22.03.16
 * Time: 5:22
 */

namespace Exprating\FakerBundle\Tests;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Exprating\FakerBundle\Faker\FakeEntitiesGenerator;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FakerTest extends WebTestCase
{
    public function testFaker()
    {
        $client = static::createClient();
        $faker = $client->getContainer()->get('exprating_faker.faker.fake_entities_generator');
        $this->assertInstanceOf(User::class, $faker->user());
        $this->assertInstanceOf(Category::class, $faker->category());
        $this->assertInstanceOf(Product::class, $faker->product());
    }
}