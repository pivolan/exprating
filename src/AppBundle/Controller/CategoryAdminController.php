<?php

/**
 * Date: 23.02.16
 * Time: 1:40.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\CreateExpertRequest;
use AppBundle\Form\CategoryType;
use AppBundle\Form\CreateExpertRequestApproveType;
use AppBundle\Form\RatingSettingsType;
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
        $ratingSettings = null;

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
     * @ParamConverter(name="createExpertRequest", class="AppBundle\Entity\CreateExpertRequest")
     */
    public function requestsAction(Request $request, CreateExpertRequest $createExpertRequest = null)
    {
        $em = $this->getEm();
        $query = $em->getRepository('AppBundle:CreateExpertRequest')->queryByCurator($this->getUser());
        /** @var CreateExpertRequest[] $createExpertRequests */
        $createExpertRequests = $query->getResult();

        $form = null;
        if ($createExpertRequest) {
            $form = $this->createForm(
                CreateExpertRequestApproveType::class,
                $createExpertRequest,
                ['action' => $this->generateUrl('approve_create_expert', ['id' => $createExpertRequest->getId()])]
            )->createView();
        }

        $template = 'CategoryAdmin/requests.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'CategoryAdmin/requestsPart.html.twig';
        }

        return $this->render(
            $template,
            [
                self::KEY_REGISTRATION_REQUESTS => $createExpertRequests,
                self::KEY_REGISTRATION_REQUEST  => $createExpertRequest,
                self::KEY_FORM                  => $form,
            ]
        );
    }
}
