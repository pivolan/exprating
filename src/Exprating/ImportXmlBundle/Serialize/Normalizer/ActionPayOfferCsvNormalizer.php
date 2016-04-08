<?php
namespace Exprating\ImportXmlBundle\Serialize\Normalizer;

use Exprating\ImportXmlBundle\XmlDto\ActionPayOffer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ActionPayOfferCsvNormalizer extends ObjectNormalizer
{
    const FORMAT = 'csv';

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $actionPayOffer = new ActionPayOffer();
        if (is_array($data) && count($data) == 3 && isset($data[0], $data[1], $data[2])) {
            $actionPayOffer->id = $data[0];
            $actionPayOffer->name = $data[1];
            $actionPayOffer->url = $data[2];
        }

        return $actionPayOffer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        /** @var ActionPayOffer $actionPayOffer */
        $actionPayOffer = $object;

        return [$actionPayOffer->id, $actionPayOffer->name, $actionPayOffer->url];
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return ($type == ActionPayOffer::class) && $format == self::FORMAT;
    }

    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof ActionPayOffer) && $format == self::FORMAT;
    }
}