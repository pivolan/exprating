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
    const KEY_FILENAME = 'filename';
    const FOLDER_UPLOADS_USER = '/uploads/user/';
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

    public function onUploadProductImage(PostPersistEvent $event)
    {
        if ($event->getRequest()->get('product_id')) {
            $params = $event->getRequest()->request->all();
            /** @var File $file */
            $file = $event->getFile();
            $response = $event->getResponse();
            $response[self::KEY_FILENAME] = $file->getFilename();
            if (isset($params['product_id']) && is_numeric($params['product_id'])) {
                $this->pathFinder->setProductId($params['product_id']);
                $file->move($this->pathFinder->findFolder());
                $response[self::KEY_FILENAME] = $this->pathFinder->relativeFolder().$file->getFilename();
            }
        }
    }

    public function onUploadUserImage(PostPersistEvent $event)
    {
        if ($event->getRequest()->get('username')) {
            /** @var File $file */
            $file = $event->getFile();
            $response = $event->getResponse();
            $username = $event->getRequest()->get('username');
            $newFilename = $username.time().'.'.$file->getExtension();
            $file->move($this->pathFinder->getWebDir().self::FOLDER_UPLOADS_USER, $newFilename);
            $relativePathImage = self::FOLDER_UPLOADS_USER.$newFilename;
            $response[self::KEY_FILENAME] = $relativePathImage;
        }
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
            UploadEvents::POST_PERSIST => [['onUploadProductImage'], ['onUploadUserImage'],],
        ];
    }
}
