<?php
/**
 * Date: 16.03.16
 * Time: 15:24
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Event\Characteristic\CharacteristicCreateEvent;
use AppBundle\Event\Characteristic\CharacteristicEditEvent;
use AppBundle\Event\Characteristic\CharacteristicEvents;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Form\CharacteristicType;
use Exprating\ImportBundle\Entity\AliasCategory;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class CharacteristicController extends BaseController
{
    /**
     * @Route("/characteristic/create/{category_slug}/{product_slug}", name="characteristic_create",
     *     defaults={"product_slug":null})
     * @param Request $request
     * @Security("is_granted('ROLE_EXPERT')")
     * @ParamConverter(name="category", class="AppBundle\Entity\Category",
     *     options={"mapping":{"category_slug":"slug"}})
     * @ParamConverter(name="product", class="AppBundle\Entity\Product",
     *     options={"mapping":{"product_slug":"slug"}})
     *
     * @return Response
     */
    public function createAction(Request $request, Category $category, Product $product = null)
    {
        $form = $this->createForm(CharacteristicType::class, null, ['action' => $request->getUri()]);

        $form->handleRequest($request);

        $status = 200;
        if ($form->isValid()) {
            /** @var Characteristic $entity */
            $entity = $form->getData();
            $this->getEm()->persist($entity);
            $this->get('event_dispatcher')->dispatch(
                CharacteristicEvents::CREATED,
                new CharacteristicCreateEvent($entity, $this->getUser(), $category, $product)
            );
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_MESSAGE, 'Новая характеристика успешно сохранена '.$entity->getName());
            $status = 201;
        }

        return $this->render(
            'Characteristic/create.html.twig',
            [self::KEY_FORM => $form->createView()],
            new Response('', $status)
        );
    }

    /**
     * @Route("/characteristic/edit/{slug}", name="characteristic_edit")
     * @param Request $request
     * @Security("is_granted('ROLE_ADMIN')")
     * @ParamConverter(name="characteristic", class="Exprating\CharacteristicBundle\Entity\Characteristic",
     *     options={"mapping":{"slug":"slug"}})
     *
     * @return Response
     */
    public function editAction(Request $request, Characteristic $characteristic)
    {
        $form = $this->createForm(CharacteristicType::class, $characteristic, ['action' => $request->getUri()]);

        $form->handleRequest($request);

        $status = 200;
        if ($form->isValid()) {
            /** @var Characteristic $entity */
            $entity = $form->getData();
            $this->getEm()->persist($entity);
            $this->get('event_dispatcher')->dispatch(
                CharacteristicEvents::EDITED,
                new CharacteristicEditEvent($entity, $this->getUser())
            );
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_MESSAGE, 'Характеристика успешно сохранена '.$entity->getName());

            return $this->redirectToRoute('characteristic_edit', ['slug' => $entity->getSlug()]);
        }

        return $this->render(
            'Characteristic/edit.html.twig',
            [self::KEY_FORM => $form->createView()],
            new Response('', $status)
        );
    }
}
