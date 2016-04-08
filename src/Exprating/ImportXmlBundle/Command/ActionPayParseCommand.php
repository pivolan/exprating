<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\ActionPayFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayAdvNormalizer;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayOfferCsvNormalizer;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayOfferNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Exprating\ImportXmlBundle\XmlDto\ActionPayAdv;
use Exprating\ImportXmlBundle\XmlDto\ActionPayOffer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class ActionPayParseCommand extends Command
{
    const TAG_NAME = 'offer';
    /**
     * @var ActionPayFiles
     */
    private $actionpayFiles;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param ActionPayFiles $actionpayFiles
     */
    public function setActionPayFiles(ActionPayFiles $actionpayFiles)
    {
        $this->actionpayFiles = $actionpayFiles;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:actionpay:parse')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileXmlInfo = $this->actionpayFiles->getFileInfoXml();
        if (!$fileXmlInfo->isFile()) {
            $output->writeln(
                'No file('.$fileXmlInfo->getPathname().') found, please start import_xml:actionpay:download first'
            );

            return;
        }
        $output->writeln('File found, start parsing '.$fileXmlInfo->getPathname());
        $fileActionPayCsv = New \SplFileObject($this->actionpayFiles->getFileInfoCsv()->getPathname(), 'w');
        $reader = new \XMLReader();
        $reader->open($fileXmlInfo->getPathname());
        while ($reader->read()) {
            if ($reader->localName == self::TAG_NAME) {
                /** @var ActionPayOffer $actionPayOffer */
                $actionPayOffer = $this->serializer->deserialize(
                    $reader->readOuterXml(),
                    ActionPayOffer::class,
                    ActionPayOfferNormalizer::FORMAT
                );
                if ($actionPayOffer->url) {
                    $fileActionPayCsv->fputcsv(
                        $this->serializer->normalize($actionPayOffer, ActionPayOfferCsvNormalizer::FORMAT)
                    );
                }
            }
        }
        $output->writeln('Successful. Result in '.$fileActionPayCsv->getPathname());
    }
}