<?php
namespace Exprating\ImportXmlBundle\Serialize\Normalizer;

use Exprating\ImportXmlBundle\XmlDto\ActionPayOffer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ActionPayOfferNormalizer extends ObjectNormalizer
{
    const FORMAT = 'xml';
    const PREFIX_URL = 'https://api.actionpay.ru/ru/apiWmOffers/?key=E1RBQymTBLV53g92yjZc&format=xml&offer=';

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $actionPayOffer = new ActionPayOffer();
        if (is_array($data)) {
            if (isset($data['id']['#'])) {
                $actionPayOffer->id = $data['id']['#'];
                $actionPayOffer->url = self::PREFIX_URL.$actionPayOffer->id;
            }
            if (isset($data['name']['#'])) {
                $actionPayOffer->name = $data['name']['#'];
            }
        }

        return $actionPayOffer;

    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return ($type == ActionPayOffer::class) && $format == self::FORMAT;
    }

    public function supportsNormalization($data, $format = null)
    {
        return false;
    }
}