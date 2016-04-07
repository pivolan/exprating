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

class OfferCsvToDbCommand extends Command
{
    /**
     * @var AdmitadPriceListFiles
     */
    private $admitadPriceListFiles;

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
            ->setName('import_xml:offer:csv_to_db')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pdo = new \PDO('mysql:dbname=exprating_import;host=127.0.0.1', 'root', 'chease');
        $globPattern = $this->admitadPriceListFiles->getFolder().'/*.csv';
        foreach (glob($globPattern) as $csvFilePath) {
            $fileInfo = new \SplFileInfo($csvFilePath);
            if ($fileInfo->isFile()) {
                $output->writeln('file csv load ' . $csvFilePath);
                $pdo->exec('LOAD DATA INFILE "'.$fileInfo->getRealPath().'" REPLACE INTO TABLE offer FIELDS TERMINATED BY "," ENCLOSED BY \'"\' LINES TERMINATED BY "\n";');
                $output->writeln('file csv loaded ' . $csvFilePath);
            }
        }
    }
}