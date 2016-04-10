<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\ActionPayFiles;
use Exprating\ImportXmlBundle\Filesystem\ActionPayOfferFiles;
use Exprating\ImportXmlBundle\Filesystem\FilesystemInterface;
use Exprating\ImportXmlBundle\Serialize\Normalizer\ActionPayOfferCsvNormalizer;
use Exprating\ImportXmlBundle\XmlDto\ActionPayOffer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class ActionPayDownloadOffersCommand extends Command
{
    const URL_ACTIONPAY_XML = 'https://api.actionpay.ru/ru/apiWmMyOffers/?key=E1RBQymTBLV53g92yjZc&format=xml';

    /**
     * @var ActionPayFiles
     */
    private $actionPayFiles;

    /**
     * @var ActionPayOfferFiles
     */
    private $actionPayOfferFiles;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ActionPayFiles $actionPayFiles
     */
    public function setActionPayFiles(ActionPayFiles $actionPayFiles)
    {
        $this->actionPayFiles = $actionPayFiles;
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
            ->setName('import_xml:actionpay:download:offers')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actionPayFile = $this->actionPayFiles->getFileInfoCsv();
        if (!$actionPayFile->isFile()) {
            $output->writeln(
                'No file('.$actionPayFile->getPathname().') found, please start import_xml:actionpay:parse first'
            );

            return;
        }
        $output->writeln('Csv file found '.$actionPayFile->getPathname());
        if (!is_dir($this->actionPayOfferFiles->getFolder())) {
            mkdir($this->actionPayOfferFiles->getFolder(), 0777, true);
        }
        $splFileObject = New \SplFileObject($this->actionPayFiles->getFileInfoCsv()->getPathname(), 'r');
        $forksCount = 0;
        while ($data = $splFileObject->fgetcsv()) {
            $forksCount++;
            /** @var ActionPayOffer $actionPayOffer */
            $actionPayOffer = $this->serializer->denormalize(
                $data,
                ActionPayOffer::class,
                ActionPayOfferCsvNormalizer::FORMAT
            );
            $fileInfoXml = $this->actionPayOfferFiles->getFileInfoXml($actionPayOffer);
            $output->writeln('start download '.$actionPayOffer->url);
            //Сделаем закачку в 4 потока
            $pid = pcntl_fork();
            if ($pid == 0) {
                try {
                    $file = fopen($fileInfoXml->getPathname(), 'w');
                    $ch = curl_init($actionPayOffer->url);
                    curl_setopt($ch, CURLOPT_FILE, $file);
                    curl_exec($ch);
                    $curlError = curl_error($ch);
                    if ($curlError) {
                        file_put_contents($fileInfoXml->getPathname().'.error', $curlError);
                        $output->writeln($curlError);
                    } else {
                        $output->writeln('saved xml offer '.$fileInfoXml->getPathname());
                    }
                    curl_close($ch);
                } catch (\Exception $e) {
                    file_put_contents($fileInfoXml->getPathname().'.error', $e->getTraceAsString());
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
        $output->writeln('Successful. Result in '.$splFileObject->getPathname());

    }
}