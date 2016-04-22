<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Doctrine\ORM\EntityManager;
use Exprating\ImportXmlBundle\Entity\Offer;
use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Filesystem\AdmitadPriceListFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\OfferNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class OfferCsvToDbCommand extends Command
{
    /**
     * @var AdmitadPriceListFiles
     */
    private $admitadPriceListFiles;

    /**
     * @var EntityManager
     */
    private $emImportXml;

    /**
     * @param AdmitadPriceListFiles $admitadPriceListFiles
     */
    public function setAdmitadPriceListFiles(AdmitadPriceListFiles $admitadPriceListFiles)
    {
        $this->admitadPriceListFiles = $admitadPriceListFiles;
    }

    /**
     * @return EntityManager
     */
    public function getEmImportXml()
    {
        return $this->emImportXml;
    }

    /**
     * @param EntityManager $emImportXml
     */
    public function setEmImportXml($emImportXml)
    {
        $this->emImportXml = $emImportXml;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:offer:csv_to_db')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $globPattern = $this->admitadPriceListFiles->getFolder().'/*.csv';
        $emImportXml = $this->getEmImportXml();
        $connection = $emImportXml->getConnection();
        foreach (glob($globPattern) as $csvFilePath) {
            $fileInfo = new \SplFileInfo($csvFilePath);
            if ($fileInfo->isFile()) {
                $output->writeln('file csv load ' . $csvFilePath);
                $connection->exec('LOAD DATA LOCAL INFILE "'.$fileInfo->getRealPath().'" REPLACE INTO TABLE offer FIELDS TERMINATED BY "," ENCLOSED BY \'"\' LINES TERMINATED BY "\n";');
                $output->writeln('file csv loaded ' . $csvFilePath);
            }
        }
    }
}