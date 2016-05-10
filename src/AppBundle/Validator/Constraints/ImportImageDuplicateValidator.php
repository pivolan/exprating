<?php

/**
 * Date: 16.02.16
 * Time: 17:00.
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use AppBundle\Dto\ImportPictures\ImportImage;

class ImportImageDuplicateValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param ImportImage $importImage The value that should be validated
     * @param Constraint  $constraint  The constraint for the validation
     *
     * @return null
     */
    public function validate($importImage, Constraint $constraint)
    {
        $urls = $importImage->getUrls();
        $importedImages = $importImage->getProduct()->getImportedImages();
        if (!$importedImages) {
            $importedImages = [];
        }

        foreach ($urls as $key => $url) {
            if (in_array($url, $importedImages)) {
                $this->context->buildViolation('Дублирование файла: '.$url)
                    ->atPath("importedImages[$key].url")
                    ->addViolation();
            }
            $importedImages[] = $url;
        }
    }
}
