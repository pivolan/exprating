<?php

/**
 * Date: 16.02.16
 * Time: 17:00.
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Category;
use AppBundle\Entity\Invite;
use AppBundle\Entity\RatingSettings;
use AppBundle\Entity\RequestCuratorRights;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RequestCuratorRightsValidator extends ConstraintValidator
{
    const PERIOD = 7;
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
     * @param RequestCuratorRights $requestCuratorRights
     * @param Constraint           $constraint
     *
     * @return bool
     */
    public function validate($requestCuratorRights, Constraint $constraint)
    {
        $requestCuratorRightsList = $this->em->getRepository('AppBundle:RequestCuratorRights')->findLastByPeriod(
            $requestCuratorRights->getExpert(),
            self::PERIOD
        );
        if (count($requestCuratorRightsList)) {
            $this->context->buildViolation(
                'Вы уже отправляли заявку '.$requestCuratorRightsList[0]->getCreatedAt()->format(
                    \DateTime::W3C
                ).' числа.'
            )->addViolation();
        }
    }
}
