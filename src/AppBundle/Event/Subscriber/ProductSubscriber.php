<?php

/**
 * Date: 19.02.16
 * Time: 18:43.
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Image;
use AppBundle\Entity\ProductEditHistory;
use AppBundle\Entity\User;
use AppBundle\Event\DecisionCreateEvent;
use AppBundle\Event\ProductApproveEvent;
use AppBundle\Event\ProductChangeExpertEvent;
use AppBundle\Event\ProductEditedEvent;
use AppBundle\Event\ProductEventInterface;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductRejectEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Event\ProductReservationOverEvent;
use AppBundle\Event\ProductSaveQueryStringEvent;
use AppBundle\Event\ProductVisitEvent;
use AppBundle\Humanize\ProductHistoryDiffHumanize;
use AppBundle\Dto\ImportPictures\ImportImage;
use AppBundle\Event\ProductImportPicturesEvent;
use AppBundle\PathFinder\ProductImage;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ProductSubscriber
 * @package AppBundle\Event\Subscriber
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var ProductHistoryDiffHumanize
     */
    protected $humanize;
    /**
     * @var ProductImage
     */
    protected $pathService;

    public function __construct(
        \Swift_Mailer $mailer,
        EntityManager $em,
        \Twig_Environment $twig,
        ProductHistoryDiffHumanize $humanize,
        ProductImage $pathService
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->twig = $twig;
        $this->humanize = $humanize;
        $this->pathService = $pathService;
    }

    public static function getSubscribedEvents()
    {
        return [
            ProductEvents::RESERVATION       => [['reserveProduct', 0], ['flush']],
            ProductEvents::PUBLISH_REQUEST   => [
                ['publishRequestProduct', 1],
                ['notifyCurator'],
                ['flush'],
            ],
            ProductEvents::APPROVE           => [['approveProduct', 1], ['onApproveNotifyExpert'], ['flush']],
            ProductEvents::REJECT            => [['rejectProduct', 1], ['onRejectNotifyExpert'], ['flush']],
            ProductEvents::PUBLISH           => [['publishProduct', 1], ['onPublishNotifyExpert'], ['flush']],
            ProductEvents::CHANGE_EXPERT     => [['changeExpert', 1], ['onChangeExpertNotify'], ['flush']],
            ProductEvents::RESERVATION_OVER  => [['reserveOver', 0], ['onReserveOverNotifyExpert', 1], ['flush']],
            ProductEvents::COMMENTED         => [['flush']],
            ProductEvents::DECISION          => [['curatorDecision', 1]],
            ProductEvents::VISIT             => [['productVisited', 1], ['flush']],
            ProductEvents::EDITED            => [['productEdited', 1]],
            ProductEvents::IMPORT_PICTURES   => [['importPictures', 1], ['flush']],
            ProductEvents::SAVE_QUERY_STRING => [['saveString', 1], ['flush']],
        ];
    }

    public function reserveProduct(ProductReservationEvent $event)
    {
        $expert = $event->getExpert();
        $product = $event->getProduct();
        $product->setExpertUser($expert)
            ->setReservedAt(new \DateTime());
    }

    public function publishRequestProduct(
        ProductPublishRequestEvent $event,
        $eventName,
        EventDispatcherInterface $dispatcher
    ) {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();

        if ($expert->getCurator()) {
            $curatorDecision = new CuratorDecision();
            $curatorDecision->setCurator($expert->getCurator())
                ->setProduct($product)
                ->setUpdatedAt(new \DateTime());
            $this->em->persist($curatorDecision);
        } elseif ($expert->hasRole(User::ROLE_EXPERT_CURATOR)) {
            $dispatcher->dispatch(ProductEvents::PUBLISH, $event);
            $event->stopPropagation();
        } else {
            throw new \LogicException('Невозможно опубликовать товар, у эксперта нет куратора. Некому проверить.');
        }
    }

    public function notifyCurator(ProductPublishRequestEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Новая публикация от Эксперта '.$expert->getFullName().' '.$product->getName())
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyCuratorPublishRequestProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function approveProduct(
        ProductApproveEvent $event,
        $eventName,
        EventDispatcherInterface $dispatcher
    ) {
        $product = $event->getProduct();

        foreach ($product->getCuratorDecisions() as $curatorDecision) {
            if ($curatorDecision->getStatus() == CuratorDecision::STATUS_WAIT) {
                $curatorDecision->setStatus(CuratorDecision::STATUS_APPROVE)
                    ->setUpdatedAt(new \DateTime());
            }
        }
        $dispatcher->dispatch(ProductEvents::PUBLISH, $event);
    }

    public function onApproveNotifyExpert(ProductApproveEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была одобрена Куратором '.$curator->getFullName().' - '.$product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertApproveProduct.html.twig',
                    ['product' => $product, 'curator' => $curator]
                )
            );
        $this->mailer->send($message);
    }

    public function rejectProduct(ProductRejectEvent $event)
    {
        $product = $event->getProduct();

        foreach ($product->getCuratorDecisions() as $curatorDecision) {
            if ($curatorDecision->getStatus() == CuratorDecision::STATUS_WAIT) {
                $curatorDecision->setStatus(CuratorDecision::STATUS_REJECT)
                    ->setRejectReason($event->getReason())
                    ->setUpdatedAt(new \DateTime());
            }
        }
    }

    public function onRejectNotifyExpert(ProductRejectEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была отвергнута Куратором '.$curator->getFullName().' - '.$product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertRejectProduct.html.twig',
                    ['product' => $product, 'curator' => $curator, 'rejectReason' => $event->getReason()]
                )
            );
        $this->mailer->send($message);
    }

    public function publishProduct(ProductEventInterface $event)
    {
        $product = $event->getProduct();

        $product->setReservedAt(null)
            ->setIsEnabled(true)->setEnabledAt(new \DateTime());
    }

    public function onPublishNotifyExpert(ProductEventInterface $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была опубликована '.$product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertPublishedProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function changeExpert(ProductChangeExpertEvent $event)
    {
        $nextExpert = $event->getNewExpert();
        $product = $event->getProduct();
        $product->setExpertUser($nextExpert);
    }

    public function onChangeExpertNotify(ProductChangeExpertEvent $event)
    {
        $product = $event->getProduct();
        $nextExpert = $event->getNewExpert();
        $prevExpert = $event->getPreviousExpert();
        $curator = $event->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваш товар был передан другому эксперту '.$product->getName())
            ->setTo($prevExpert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/ChangeExpertPrevNotify.html.twig',
                    ['product' => $product, 'curator' => $curator]
                )
            );
        $this->mailer->send($message);

        $message = \Swift_Message::newInstance()
            ->setSubject('Вам был передан товар на обзор '.$product->getName())
            ->setTo($nextExpert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/ChangeExpertNextNotify.html.twig',
                    ['product' => $product, 'curator' => $curator]
                )
            );
        $this->mailer->send($message);
    }

    public function reserveOver(ProductReservationOverEvent $event)
    {
        $product = $event->getProduct();
        $product->setExpertUser(null)
            ->setReservedAt(null);
    }

    public function onReserveOverNotifyExpert(ProductReservationOverEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $message = \Swift_Message::newInstance()
            ->setSubject('Время резервирования товара '.$product->getName().' закончено')
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertReservationOverProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function productEdited(ProductEditedEvent $event)
    {
        $product = $event->getProduct();
        $user = $event->getUser();
        $history = new ProductEditHistory();
        $history->setProduct($product)
            ->setUser($user);
        $uow = $this->em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeset = $uow->getEntityChangeSet($product);
        $history->setDiff($changeset);
        $history->setText($this->humanize->humanize($history));
        $this->em->persist($history);
    }

    public function productVisited(ProductVisitEvent $event)
    {

    }

    public function curatorDecision(
        DecisionCreateEvent $event,
        $eventName,
        EventDispatcherInterface $dispatcher
    ) {
        /** @var CuratorDecision $decision */
        $decision = $event->getDecision();
        if ($decision->getStatus() == CuratorDecision::STATUS_APPROVE) {
            $dispatcher->dispatch(
                ProductEvents::APPROVE,
                new ProductApproveEvent(
                    $decision->getProduct(),
                    $decision->getCurator()
                )
            );
        } elseif ($decision->getStatus() == CuratorDecision::STATUS_REJECT) {
            $dispatcher->dispatch(
                ProductEvents::REJECT,
                new ProductRejectEvent(
                    $decision->getProduct(),
                    $decision->getCurator(),
                    $decision->getRejectReason()
                )
            );
        } else {
            throw new \LogicException('Invalid status for create decision about product, maybe approve or reject only');
        }
    }

    public function importPictures(ProductImportPicturesEvent $event)
    {
        $importImage = $event->getImportImage();
        $product = $importImage->getProduct();
        $urls = $importImage->getUrls();
        $this->pathService->setProductId($product->getId());
        $absoluteFolderPath = $this->pathService->findFolder();
        if (!is_dir($absoluteFolderPath)) {
            mkdir($absoluteFolderPath, 0777, true);
        }
        $importedUrls = [];
        foreach ($urls as $src) {
            $filename = $this->pathService->getRandomFilename();
            $targetFileFull = $absoluteFolderPath.$filename;
            $ch = curl_init($src);
            $fp = fopen($targetFileFull, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            $product->addImportedImage($src);
            $image = (new Image())->setProduct($product)
                ->setFilename($this->pathService->relativeFolder().$filename)
                ->setName($filename);
            $product->addImage($image);
            $importedUrls[] = $image->getFilename();
            $this->em->persist($image);
        }
        $importImage->setImportedUrls($importedUrls);
    }

    public function saveString(ProductSaveQueryStringEvent $event)
    {
        $product = $event->getProduct();
        $product->setSameProductsQueryString($event->getSearchParams()->getString());
    }

    public function flush()
    {
        $this->em->flush();
    }
}
