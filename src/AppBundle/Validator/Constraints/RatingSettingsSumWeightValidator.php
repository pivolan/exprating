<?php
/**
 * Date: 16.02.16
 * Time: 17:00
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\RatingSettings;
use AppBundle\Entity\User;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Security\ProductFilterVoter;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Asset\Exception\LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class RatingSettingsSumWeightValidator extends ConstraintValidator
{
    /**
     * @param RatingSettings $ratingSettings
     * @param Constraint     $constraint
     *
     * @return bool
     */
    public function validate($ratingSettings, Constraint $constraint)
    {
        $sumRating = $ratingSettings->getRating1weight()
                     + $ratingSettings->getRating2weight()
                     + $ratingSettings->getRating3weight()
                     + $ratingSettings->getRating4weight();
        if ($sumRating != 100) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}