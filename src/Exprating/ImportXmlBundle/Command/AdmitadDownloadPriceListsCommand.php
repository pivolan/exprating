<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Filesystem\AdmitadPriceListFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\AdmitadAdvNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class AdmitadDownloadPriceListsCommand extends Command
{
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
     * @param AdmitadPriceListFiles $admitadPriceListFiles
     */
    public function setAdmitadPriceListFiles(AdmitadPriceListFiles $admitadPriceListFiles)
    {
        $this->admitadPriceListFiles = $admitadPriceListFiles;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:admitad:download:price_lists')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileInfoCsv = $this->admitadFiles->getFileInfoCsv();
        if (!$fileInfoCsv->isFile()) {
            $output->writeln(
                'No file('.$fileInfoCsv->getPathname().') found, please start import_xml:admitad:parse first'
            );
            return;
        }
        $output->writeln('Csv file found '.$fileInfoCsv->getPathname());
        $fileAdmitadCsv = New \SplFileObject($this->admitadFiles->getFileInfoCsv()->getPathname(), 'r');
        $forksCount = 0;
        while ($data = $fileAdmitadCsv->fgetcsv()) {
            $forksCount++;
            /** @var AdmitadAdv $admitadAdv */
            $admitadAdv = $this->serializer->denormalize($data, AdmitadAdv::class, AdmitadAdvNormalizer::FORMAT);
            $priceListXmlFileInfo = $this->admitadPriceListFiles->getFileInfoXml($admitadAdv);
            $output->writeln('start download '.$admitadAdv->original_products);
            exec("nohup curl '{$admitadAdv->original_products}'> {$priceListXmlFileInfo->getPathname()} 2>/dev/null &");
            $output->writeln('queued xml pricelist '.$priceListXmlFileInfo->getPathname());
        }
        $output->writeln('Successful. Result in '.$fileAdmitadCsv->getPathname());
    }
}