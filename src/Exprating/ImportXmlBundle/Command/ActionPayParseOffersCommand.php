<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\ActionPayFiles;
use Exprating\ImportXmlBundle\Filesystem\ActionPayOfferFiles;
use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayAdvNormalizer;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayOfferCsvNormalizer;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayOfferNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Exprating\ImportXmlBundle\XmlDto\ActionPayAdv;
use Exprating\ImportXmlBundle\XmlDto\ActionPayOffer;
use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class ActionPayParseOffersCommand extends Command
{
    const TAG_NAME = 'Yml';

    /**
     * @var AdmitadFiles
     */
    private $admitadFiles;

    /**
     * @var ActionPayOfferFiles
     */
    private $actionPayOfferFiles;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param AdmitadFiles $admitadFiles
     */
    public function setAdmitadFiles(AdmitadFiles $admitadFiles)
    {
        $this->admitadFiles = $admitadFiles;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ActionPayOfferFiles $actionPayOfferFiles
     */
    public function setActionPayOfferFiles(ActionPayOfferFiles $actionPayOfferFiles)
    {
        $this->actionPayOfferFiles = $actionPayOfferFiles;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:actionpay:parse:offers')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pattern = $this->actionPayOfferFiles->getFolder().'/*.xml';
        $fileCsvInfo = $this->actionPayOfferFiles->getFileInfoCsv();

        $fileOffersCsv = New \SplFileObject($fileCsvInfo->getPathname(), 'w');

        foreach (glob($pattern) as $key => $path) {
            if (is_file($path)) {
                $fileInfo = new \SplFileInfo($path);
                $reader = new \XMLReader();
                $reader->open($path);
                try {
                    while ($reader->read()) {
                        if ($reader->name == self::TAG_NAME) {
                            $url = $reader->readString();
                            if ($url) {
                                $admitadAdv = new AdmitadAdv();
                                $admitadAdv->id = $key;
                                $admitadAdv->name = $fileInfo->getBasename();
                                $admitadAdv->original_products = $url;
                                $fileOffersCsv->fputcsv($this->serializer->normalize($admitadAdv));
                            }
                        }
                    }

                } catch (\Exception $e) {
                    file_put_contents($path.'.error', $e->getMessage());
                }
            }
        }
    }
}