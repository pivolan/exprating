<?php
/**
 * Date: 03.04.16
 * Time: 3:04
 */

namespace Exprating\ImportBundle\Xml;


use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Class XmlReader
 */
class XmlReader
{
    /** @var XmlEncoder */
    private $xmlEncoder;

    /**
     * XmlReader constructor.
     *
     * @param DecoderInterface $xmlEncoder Декодит xml в массив.
     */
    public function __construct(DecoderInterface $xmlEncoder)
    {
        $this->xmlEncoder = $xmlEncoder;
    }

    /**
     * @inheritdoc
     */
    public function getElementsData(\SplFileInfo $file, $elementForSearch)
    {
        $reader = new \XMLReader();
        $reader->open($file->getPathname());
        // Move to the first <position /> node.
        do {
            $reader->read();
        } while ($reader->localName !== $elementForSearch);

        // Now that we're at the right depth, hop to the next <position/> until the end of the tree.
        while ($reader->localName === $elementForSearch) {
            yield $this->xmlEncoder->decode($reader->readOuterXML(), 'xml');
            $reader->next($elementForSearch);
        }
    }
}
