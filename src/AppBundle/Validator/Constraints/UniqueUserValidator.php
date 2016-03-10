<?php

/**
 * Date: 16.02.16
 * Time: 17:00.
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Invite;
use AppBundle\Entity\RatingSettings;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
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
     * @param Invite     $invite
     * @param Constraint $constraint
     *
     * @return bool
     */
    public function validate($invite, Constraint $constraint)
    {
        if ($this->em->getRepository('AppBundle:User')->findOneBy(['email' => $invite->getEmail()])) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
