<?php
/**
 * Date: 16.03.16
 * Time: 15:24
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
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
     * @Route("/characteristic/create", name="characteristic_create")
     * @param Request $request
     * @Security("is_granted('ROLE_EXPERT')")
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(CharacteristicType::class);

        return $this->render('Characteristic/create.html.twig', [self::KEY_FORM => $form->createView()]);
    }
}