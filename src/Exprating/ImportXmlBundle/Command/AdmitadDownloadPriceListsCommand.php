<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\ActionPayOfferFiles;
use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Filesystem\AdmitadPriceListFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\AdmitadAdvNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;
use Ivory\CKEditorBundle\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class AdmitadDownloadPriceListsCommand extends Command
{
    const ARG_TYPE = 'type';
    const TYPE_ADMITAD = 'admitad';
    const TYPE_ACTIONPAY = 'actionpay';
    /**
     * @var AdmitadFiles
     */
    private $admitadFiles;


    /**
     * @var ActionPayOfferFiles
     */
    private $actionPayOfferFiles;

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
     * @param ActionPayOfferFiles $actionPayOfferFiles
     */
    public function setActionPayOfferFiles(ActionPayOfferFiles $actionPayOfferFiles)
    {
        $this->actionPayOfferFiles = $actionPayOfferFiles;
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
            ->setName('import_xml:download:price_lists')
            ->setDescription('Greet someone')
            ->addArgument(self::ARG_TYPE, null, 'maybe admitad or actionpay');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument(self::ARG_TYPE);

        if ($type == self::TYPE_ADMITAD) {
            $fileInfoCsv = $this->admitadFiles->getFileInfoCsv();
            if (!$fileInfoCsv->isFile()) {
                $output->writeln(
                    'No file('.$fileInfoCsv->getPathname().') found, please start import_xml:admitad:parse first'
                );

                return;
            }
            $output->writeln('Csv file found '.$fileInfoCsv->getPathname());
            $fileCsv = New \SplFileObject($fileInfoCsv->getPathname(), 'r');
        } elseif ($type == self::TYPE_ACTIONPAY) {
            $fileInfoCsv = $this->actionPayOfferFiles->getFileInfoCsv();
            if (!$fileInfoCsv->isFile()) {
                $output->writeln(
                    'No file('
                    .$fileInfoCsv->getPathname()
                    .') found, please start import_xml:actionpay:parse:offers first'
                );

                return;
            }
            $output->writeln('Csv file found '.$fileInfoCsv->getPathname());
            $fileCsv = New \SplFileObject($fileInfoCsv->getPathname(), 'r');
        } else {
            throw new \Exception('Invalid type argument');
        }
        $forksCount = 0;
        while ($data = $fileCsv->fgetcsv()) {
            $forksCount++;
            /** @var AdmitadAdv $admitadAdv */
            $admitadAdv = $this->serializer->denormalize($data, AdmitadAdv::class, AdmitadAdvNormalizer::FORMAT);
            $priceListXmlFileInfo = $this->admitadPriceListFiles->getFileInfoXml($admitadAdv);
            $output->writeln('start download '.$admitadAdv->original_products);
            //Сделаем закачку в 4 потока
            $pid = pcntl_fork();
            if ($pid == 0) {
                try {
                    $file = fopen($priceListXmlFileInfo->getPathname(), 'w');
                    $ch = curl_init($admitadAdv->original_products);
                    curl_setopt($ch, CURLOPT_FILE, $file);
                    curl_exec($ch);
                    $curlError = curl_error($ch);
                    if ($curlError) {
                        file_put_contents($priceListXmlFileInfo->getPathname().'.error', $curlError);
                        $output->writeln($curlError);
                    } else {
                        $output->writeln('saved xml pricelist '.$priceListXmlFileInfo->getPathname());
                    }
                    curl_close($ch);
                } catch (\Exception $e) {
                    file_put_contents($priceListXmlFileInfo->getPathname().'.error', $e->getTraceAsString());
                }
                exit();
            }
            if ($forksCount >= 4) {
                while (pcntl_wait($status) == 0) {
                    sleep(5);
                }
                $forksCount--;
                $output->writeln('fork removed, start new fork. Forks: '.$forksCount);
            }
        }
        $output->writeln('Successful. Result in '.$fileCsv->getPathname());
    }
}