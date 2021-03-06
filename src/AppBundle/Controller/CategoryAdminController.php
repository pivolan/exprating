<?php

/**
 * Date: 23.02.16
 * Time: 1:40.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\RegistrationRequest;
use AppBundle\Event\Category\CategoryCreateEvent;
use AppBundle\Event\Category\CategoryEvents;
use AppBundle\Form\CategoryCreateType;
use AppBundle\Form\CategoryType;
use AppBundle\Form\RegistrationRequestApproveType;
use AppBundle\Form\RatingSettingsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CategoryAdminController.
 *
 * @Security("is_granted('ROLE_EXPERT_CATEGORY_ADMIN')")
 */
class CategoryAdminController extends BaseController
{
    const FLASH_CATEGORY_SAVED = 'flash.category_saved';
    const KEY_REGISTRATION_REQUESTS = 'requests';
    const KEY_REGISTRATION_REQUEST = 'registrationRequest';

    /**
     * @Route("/category_admin/categories/{slug}", name="category_admin_categories", defaults={"slug": null})
     *
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction(Request $request, Category $category = null)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_CATEGORY_SAVED, 'Категория успешно сохранена.');

            return $this->redirect($request->getUri());
        }
        $template = 'CategoryAdmin/categories.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'CategoryAdmin/categoriesPart.html.twig';
        }
        $categories = $this->getEm()->getRepository('AppBundle:Category')->getForJsTree(null, $this->getUser());

        return $this->render(
            $template,
            [
                self::KEY_CATEGORIES => $categories,
                self::KEY_CATEGORY   => $category,
                self::KEY_FORM       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/category_admin/requests/{id}", name="category_admin_requests", defaults={"id":null})
     * @ParamConverter(name="registrationRequest", class="AppBundle\Entity\RegistrationRequest")
     */
    public function requestsAction(Request $request, RegistrationRequest $registrationRequest = null)
    {
        $em = $this->getEm();
        $query = $em->getRepository('AppBundle:RegistrationRequest')->queryByCurator($this->getUser());
        /** @var RegistrationRequest[] $registrationRequests */
        $registrationRequests = $query->getResult();

        $form = null;
        if ($registrationRequest) {
            $form = $this->createForm(
                RegistrationRequestApproveType::class,
                $registrationRequest,
                ['action' => $this->generateUrl('approve_create_expert', ['id' => $registrationRequest->getId()])]
            )->createView();
        }

        $template = 'CategoryAdmin/requests.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'CategoryAdmin/requestsPart.html.twig';
        }

        return $this->render(
            $template,
            [
                self::KEY_REGISTRATION_REQUESTS => $registrationRequests,
                self::KEY_REGISTRATION_REQUEST  => $registrationRequest,
                self::KEY_FORM                  => $form,
            ]
        );
    }

    /**
     * @Route("/category_admin/create/category", name="category_admin_create_category")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategoryCreateType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $event = new CategoryCreateEvent($category, $this->getUser());
            $this->get('event_dispatcher')->dispatch(CategoryEvents::CATEGORY_CREATE, $event);
            $this->addFlash(self::FLASH_MESSAGE, 'Категория успешно создана ('.$category->getName().')');

            return $this->redirectToRoute('category_admin_categories', ['slug' => $category->getSlug()]);
        }

        return $this->render('CategoryAdmin/create.html.twig', [self::KEY_FORM => $form->createView()]);
    }
}
