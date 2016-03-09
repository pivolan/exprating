<?php
/**
 * Date: 04.03.16
 * Time: 13:49
 */

namespace AppBundle\Event\Listener;

use AppBundle\Entity\Image;
use AppBundle\PathFinder;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PreRemoveImageListener
{
    /**
     * @var PathFinder\ProductImage
     */
    protected $pathFinder;

    /**
     * PreRemoveImageListener constructor.
     *
     * @param PathFinder\ProductImage $pathFinder
     */
    public function __construct(PathFinder\ProductImage $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if ($entity instanceof Image && $entity->getProduct()) {
            if ($entity->getIsMain()) {
                $entity->getProduct()->setPreviewImage(null);
            }
            if (is_file($this->pathFinder->getWebDir().$entity->getFilename())) {
                unlink($this->pathFinder->getWebDir().$entity->getFilename());
            }
        }
    }
}
