<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Entity\Offer;
use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Filesystem\AdmitadPriceListFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\OfferNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class AdmitadParsePriceListsCommand extends Command
{
    const XML_KEY_OFFER = 'offer';
    const XML_KEY_CATEGORY = 'category';
    const XML_KEY_COMPANY = 'company';
    /**
     * @var AdmitadFiles
     */
    private $admitadFiles;

    /**
     * @var AdmitadPriceListFiles
     */
    private $admitadPriceListFiles;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var XmlReader
     */
    private $xmlReader;

    /**
     * @param AdmitadFiles $admitadFiles
     */
    public function setAdmitadFiles(AdmitadFiles $admitadFiles)
    {
        $this->admitadFiles = $admitadFiles;
    }

    /**
     * @param XmlReader $xmlReader
     */
    public function setXmlReader(XmlReader $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param AdmitadPriceListFiles $admitadPriceListFiles
     */
    public function setAdmitadPriceListFiles(AdmitadPriceListFiles $admitadPriceListFiles)
    {
        $this->admitadPriceListFiles = $admitadPriceListFiles;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:admitad:parse:price_lists')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xmlReader = $this->xmlReader;

        $globPattern = $this->admitadPriceListFiles->getFolder().'/*.xml';
        foreach (glob($globPattern) as $key => $xmlFilePath) {
            $filePriceListXml = new \SplFileInfo($xmlFilePath);
            $output->writeln('start parsing '.$filePriceListXml->getBasename());
            if ($filePriceListXml->isFile() && !is_file($xmlFilePath.'.csv')) {
                $filePriceListCsv = new \SplFileObject($xmlFilePath.'.csv', 'w');
                try {
                    $categories = [];
                    $categoriesPath = [];
                    $company = '';
                    foreach ($xmlReader->getElementsData($filePriceListXml, self::XML_KEY_COMPANY) as $company) {
                        break;
                    }
                    foreach ($xmlReader->getElementsData($filePriceListXml, self::XML_KEY_CATEGORY) as $category) {
                        if (isset($category['@id'], $category['#'])) {
                            $categories[$category['@id']] = $category['#'];
                            if (isset($category['@parentId'])) {
                                $categoriesPath[$category['@id']] = $category['@parentId'];
                            }
                        }
                    }
                    foreach ($xmlReader->getElementsData($filePriceListXml, self::XML_KEY_OFFER) as $offerData) {
                        $hash = md5(serialize($offerData));
                        /** @var Offer $offer */
                        $offer = $this->serializer->denormalize($offerData, Offer::class, OfferNormalizer::FORMAT);
                        $offer->setHash($hash);
                        $offer->setCategoryName($categories[$offer->getCategoryId()]);
                        $categoryPath = $offer->getCategoryName();
                        $categoryId = $offer->getCategoryId();
                        while (isset($categoriesPath[$categoryId])) {
                            $categoryId = $categoriesPath[$categoryId];
                            $categoryPath .= $categories[$categoryId];
                        }
                        $offer->setCategoryPath($categoryPath);
                        $offer->setCompany($company);
                        $offerNormalized = $this->serializer->normalize($offer, OfferNormalizer::FORMAT);
                        $filePriceListCsv->fputcsv($offerNormalized);
                    }
                } catch (\Exception $e) {
                    $output->writeln($e->getMessage());
                    file_put_contents($filePriceListXml->getPathname().'.error', $e->getMessage());
                }
                $output->writeln('pricelist parsed, csv saved '.$filePriceListCsv->getPathname());
            } else {
                $output->writeln('skipped '.$filePriceListXml->getPathname());
            }
        }
    }
}