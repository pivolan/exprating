<?php
/**
 * Date: 04.03.16
 * Time: 10:01
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\Product;
use AppBundle\PathFinder;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\UploadEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Serializer;

class UploadSubscriber implements EventSubscriberInterface
{
    /**
     * @var PathFinder\ProductImage
     */
    protected $pathFinder;

    /**
     * UploadSubscriber constructor.
     *
     * @param PathFinder\ProductImage $pathFinder
     */
    public function __construct(PathFinder\ProductImage $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $params = $event->getRequest()->request->all();
        /** @var File $file */
        $file = $event->getFile();
        $response = $event->getResponse();
        $response['filename'] = $file->getFilename();
        if (isset($params['product_id']) && is_numeric($params['product_id'])) {
            $this->pathFinder->setProductId($params['product_id']);
            $file->move($this->pathFinder->findFolder());
            $response['filename'] = $this->pathFinder->relativeFolder().$file->getFilename();
        }

        return $response;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UploadEvents::POST_PERSIST => [['onUpload']],
        ];
    }
}