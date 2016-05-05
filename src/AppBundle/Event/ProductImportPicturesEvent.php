<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 03.05.16
 * Time: 23:35
 */

namespace AppBundle\Event;

use AppBundle\Dto\ImportPictures\ImportImage;
use Symfony\Component\EventDispatcher\Event;

class ProductImportPicturesEvent extends Event
{
    /**
     * @var ImportImage
     */
    protected $importImage;
    /**
     * ProductImportPicturesEvent constructor.
     *
     * @param ImportImage $importImage
     */
    public function __construct(ImportImage $importImage = null)
    {
        $this->importImage = $importImage;
    }

    /**
     * @return ImportImage
     */
    public function getImportImage()
    {
        return $this->importImage;
    }

    /**
     * @param ImportImage $importImage
     */
    public function setImportImage($importImage)
    {
        $this->importImage = $importImage;
    }

}