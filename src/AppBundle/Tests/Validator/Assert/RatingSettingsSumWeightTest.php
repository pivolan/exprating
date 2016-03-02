<?php

/**
 * Date: 22.02.16
 * Time: 21:10.
 */

namespace AppBundle\Tests\Validator\Assert;

use AppBundle\Entity\Category;
use AppBundle\Entity\RatingSettings;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class RatingSettingsSumWeightTest extends WebTestCase
{
    public function testAssertMin()
    {
        $category = new Category();
        $ratingSettings = new RatingSettings();
        $ratingSettings->setCategory($category)
            ->setRating1weight(9)
            ->setRating2weight(9)
            ->setRating3weight(9)
            ->setRating4weight(9);
        $client = static::createClient();
        $validator = $client->getContainer()->get('validator');
        /** @var ConstraintViolation[] $errors */
        $errors = $validator->validate($ratingSettings);
        $this->assertGreaterThan(0, count($errors));
        foreach ($errors as $error) {
            $this->assertContains('Значение должно быть 10 или больше.', $error->getMessage());
        }

        $this->assertGreaterThan(0, count($errors));
    }

    public function testAssertMax()
    {
        $category = new Category();
        $ratingSettings = new RatingSettings();
        $ratingSettings->setCategory($category)
            ->setRating1weight(888)
            ->setRating2weight(888)
            ->setRating3weight(888)
            ->setRating4weight(888);
        $client = static::createClient();
        $validator = $client->getContainer()->get('validator');
        /** @var ConstraintViolation[] $errors */
        $errors = $validator->validate($ratingSettings);
        $this->assertGreaterThan(0, count($errors));
        foreach ($errors as $error) {
            $this->assertContains('Значение должно быть 50 или меньше.', $error->getMessage());
        }

        $this->assertGreaterThan(0, count($errors));
    }

    public function testAssertSum()
    {
        $category = new Category();
        $ratingSettings = new RatingSettings();
        $ratingSettings->setCategory($category)
            ->setRating1weight(25)
            ->setRating2weight(25)
            ->setRating3weight(25)
            ->setRating4weight(10);
        $client = static::createClient();
        $validator = $client->getContainer()->get('validator');
        /** @var ConstraintViolation[] $errors */
        $errors = $validator->validate($ratingSettings);
        $this->assertGreaterThan(0, count($errors));
        foreach ($errors as $error) {
            $this->assertContains('Сумма всех весов для оценки должна быть равна 100', $error->getMessage());
        }
        $ratingSettings->setRating4weight(25);
        $errors = $validator->validate($ratingSettings);
        $this->assertCount(0, $errors);
    }
}
