<?php
namespace Exprating\ImportXmlBundle\Serialize\Normalizer;

use Exprating\ImportXmlBundle\Entity\PartnerProduct;
use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class OfferNormalizer extends ObjectNormalizer
{
    const FORMAT = 'offer_xml';

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        /** @var PartnerProduct $offer */
        $offer = parent::denormalize($data, $class, $format, $context);

        //Fill old price, if no old_price key found
        $oldPriceKeys = ['wprice', 'oldprice', 'old_price', 'prev_price', 'price_old', 'baseprice'];
        if (!$offer->getOldPrice()) {
            foreach ($oldPriceKeys as $key) {
                if (isset($data[$key]) && !empty($data[$key])) {
                    $offer->setOldPrice($data[$key]);
                    break;
                }
            }
        }
        //Fill images
        $pictureKeys = ['picture', 'additional_imageurl', 'image'];
        $pictures = [];
        if (isset($data['picture']) && is_array($data['picture'])) {
            $pictures = $data['picture'];
        }
        foreach ($pictureKeys as $key) {
            if (isset($data[$key]) && is_string($data[$key]) && !empty($data[$key])) {
                $pictures[] = $data[$key];
            }
        }
        $offer->setPictures($pictures);
        //Fill params
        $params = [];
        if (isset($data['param']['@name'])) {
            $params[$data['param']['@name']] = $data['param']['#'];
        } elseif (isset($data['param']) && is_array($data['param'])) {
            foreach ($data['param'] as $param) {
                if (isset($param['@name'], $param['#'])) {
                    $params[$param['@name']] = $param['#'];
                }
            }
        }
        $offer->setParams($params);
        //id
        if (isset($data['@id'])) {
            $offer->setId($data['@id']);
        }
        //category id
        if (ctype_digit($offer->getCategoryId())) {
            $offer->setCategoryId((int)$offer->getCategoryId());
        }

        return $offer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);
        if (is_array($data['pictures'])) {
            $data['pictures'] = json_encode($data['pictures'], JSON_UNESCAPED_UNICODE);
        }
        if (is_array($data['params'])) {
            $data['params'] = json_encode($data['params'], JSON_UNESCAPED_UNICODE);
        }

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return ($type == PartnerProduct::class) && ($format == self::FORMAT);
    }

    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof PartnerProduct) && ($format == self::FORMAT);
    }
}