<?php
namespace Exprating\ImportXmlBundle\Serialize\Normalizer;

use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AdmitadAdvNormalizer extends ObjectNormalizer
{
    const FORMAT = 'admitad_csv';

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $admitadAdv = new AdmitadAdv();
        if (is_array($data) && count($data) == 3 && isset($data[0], $data[1], $data[2])) {
            $admitadAdv->name = $data[0];
            $admitadAdv->id = $data[1];
            $admitadAdv->original_products = $data[2];
        }

        return $admitadAdv;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return ($type == AdmitadAdv::class) && ($format == self::FORMAT);
    }

    public function supportsNormalization($data, $format = null)
    {
        return false;
    }
}