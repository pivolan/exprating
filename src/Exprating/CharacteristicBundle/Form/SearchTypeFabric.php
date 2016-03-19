<?php

/**
 * Date: 09.02.16
 * Time: 15:06.
 */

namespace Exprating\CharacteristicBundle\Form;

use AppBundle\Entity\Category;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Symfony\Component\Form\FormFactoryInterface;

class SearchTypeFabric
{
    /**
     * @param FormFactoryInterface $formFactory
     * @param Category             $category
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function create(FormFactoryInterface $formFactory, Category $category)
    {
        $productSearch = new CommonProductSearch();
        foreach ($category->getCategoryCharacteristics() as $characteristics) {
            $params = new CharacteristicSearchParameter();
            $params->setName($characteristics->getSlug());
            $params->setType($characteristics->getType());
            $productSearch->addCharacteristics($params);
        }

        return $formFactory->create(CommonProductSearchType::class, $productSearch);
    }
}
