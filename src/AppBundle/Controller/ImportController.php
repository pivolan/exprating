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


class ImportController extends BaseController
{
    /**
     * @Route("/import/partner/{slug}", name="import_partner_product")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function importPicturesAction(Request $request, Product $product)
    {
        $test = $request;
        $pathService = $this->get('app.path_finder.product_image');
        $pathService->setProductId($product->getId());
        $path = $pathService->findFolder();
        $srcs = $request->request->get('url', []);
        foreach ($srcs as $src) {
            $pathParts = pathinfo($src);
            $targetFileFull = $path . $pathParts['basename'];

            $ch = curl_init($src);
            $fp = fopen($targetFileFull, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            $product->addImportedImages($src);
            $this->getEm()->persist($product);

//            $source = file_get_contents($src);
//            file_put_contents($targetFileFull , $source );
        }
        $this->getEm()->flush();

        return new JsonResponse($test);
    }

}
