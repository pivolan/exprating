<?php
/**
 * Date: 24.05.16
 * Time: 6:29
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class NotificationController extends BaseController
{
    /**
     * @Route("/notification/remove/{id}", name="notification_remove")
     * @ParamConverter(name="notification", class="AppBundle\Entity\Notification", options={"mapping":{"id":"id"}})
     *
     * @Security("is_granted('DELETE', notification)")
     */
    public function removeAction(Notification $notification)
    {
        $this->getEm()->remove($notification);
        $this->getEm()->flush();

        return new JsonResponse(self::SUCCESS_RESPONSE_OK);
    }
}