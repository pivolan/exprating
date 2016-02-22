<?php
/**
 * Date: 23.02.16
 * Time: 1:40
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Event\Category\CategoryEvents;
use AppBundle\Form\RatingSettingsType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CategoryAdminController
 *
 * @Security("is_granted('ROLE_EXPERT_CATEGORY_ADMIN')")
 *
 * @package AppBundle\Controller
 */
class CategoryAdminController extends BaseController
{
    const KEY_CATEGORIES = 'categories';

    const FLASH_CATEGORY_SAVED = 'flash.category_saved';

    public function categoryListAction()
    {
        return $this->render('CategoryAdmin/list.html.twig', [self::KEY_CATEGORIES => $this->getUser()->getAdminCategories()]);
    }

    /**
     * @param Request  $request
     * @param Category $category
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     * @Security("is_granted('EDIT', category)")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryEditAction(Request $request, Category $category)
    {
        $form = $this->createForm(RatingSettingsType::class, $category->getRatingSettings());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(CategoryEvents::CATEGORY_UPDATE, $category);
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_CATEGORY_SAVED, 'Категория успешно сохранена.');
        }

        $template = 'CategoryAdmin/edit.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'CategoryAdmin/editPart.html.twig';
        }
        return $this->render($template, [self::KEY_CATEGORY => $category, self::KEY_FORM => $form]);
    }
}