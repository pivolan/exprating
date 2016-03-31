<?php

/**
 * Date: 16.02.16
 * Time: 17:00.
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Category;
use AppBundle\Entity\Invite;
use AppBundle\Entity\RatingSettings;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueCategoryCharacteristicValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * UniqueUserValidator constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     * @param Category   $category
     * @param Constraint $constraint
     *
     * @return bool
     */
    public function validate($category, Constraint $constraint)
    {
        $characteristics = [];
        foreach ($category->getCategoryCharacteristics() as $key=>$categoryCharacteristic) {
            $slug = $categoryCharacteristic->getCharacteristic()->getSlug();
            if (in_array($slug, $characteristics)) {
                $this->context->buildViolation('Дублирование характеристики: ' . $categoryCharacteristic->getCharacteristic()->getName())
                    ->atPath("categoryCharacteristics[$key].characteristic")
                    ->addViolation();
            }
            $characteristics[] = $slug;
        }
    }
}
