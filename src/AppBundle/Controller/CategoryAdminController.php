<?php

/**
 * Date: 23.02.16
 * Time: 1:40.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
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
        if ($category) {
            $ratingSettings = $category->getRatingSettings();
        }

        $form = $this->createForm(RatingSettingsType::class, $ratingSettings);
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

        return $this->render(
            $template,
            [
                self::KEY_CATEGORIES => $this->getUser()->getAdminCategories(),
                self::KEY_CATEGORY   => $category,
                self::KEY_FORM       => $form->createView(),
            ]
        );
    }
}
