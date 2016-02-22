<?php
/**
 * Date: 23.02.16
 * Time: 2:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Exprating\CharacteristicBundle\Tests\Entity\ProductCharacteristicTest;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends BaseController
{
    public function listExpertAction(Request $request, $page = 1)
    {
        $experts = $this->getUser()->getExperts();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $experts,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $this->render('Admin/listExpert.html.twig', [self::KEY_PAGINATION => $pagination]);
    }

    public function userEditAction(Request $request, User $user)
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_MESSAGE, 'Изменения успешно сохранены');
        }
        return $this->render('Admin/userEditAction.html.twig', ['user' => $user, self::KEY_FORM => $form]);
    }
}