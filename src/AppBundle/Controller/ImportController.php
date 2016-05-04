<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\DTO\ImportPictures\ImportImage;
use Symfony\Component\EventDispatcher\EventDispatcher;
use AppBundle\Event\ProductImportPicturesEvent;
use AppBundle\Event\ProductEvents;
class ImportController extends BaseController
{
    const FLASH_IMPORT_ERRORS = 'partner.product.error';

    /**
     * @Route("/import/product/{slug}/pictures", name="import_partner_product")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importPicturesAction(Request $request, Product $product)
    {
        /** @var ImportImage $importImage */
        $importImage = $this->get('serializer')->denormalize($request->request->all(), ImportImage::class);
        $importImage->setProduct($product);
        $validator = $this->get('validator');
        $errors = $validator->validate($importImage);
        if (count($errors) > 0) {
            $this->addFlash(self::FLASH_IMPORT_ERRORS, (string)$errors);
            throw new HttpException(403, (string)$errors);
        }

        $this->get('event_dispatcher')->dispatch(
            ProductEvents::IMPORT_PICTURES,
            new ProductImportPicturesEvent($importImage)
        );
        return new JsonResponse('true');
    }
}
