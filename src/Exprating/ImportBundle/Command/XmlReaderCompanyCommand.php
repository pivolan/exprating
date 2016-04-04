<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\RatingSettings;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Exprating\ImportBundle\Xml\XmlReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RepairCategoryCommand
 * @package Exprating\ImportBundle\Command
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class XmlReaderCompanyCommand extends Command
{
    const ARG_FILE = 'file';
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var XmlReader
     */
    private $xmlReader;

    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param XmlReader $xmlReader
     */
    public function setXmlReader($xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * @param Slugify $slugify
     */
    public function setSlugify($slugify)
    {
        $this->slugify = $slugify;
    }

    protected function configure()
    {
        $this
            ->setName('import:xml_reader_company')
            ->setDescription('Greet someone');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileinfo = new \SplFileInfo('var/actionpay.xml');
        if (!$fileinfo->isFile()) {
            $fileinfo = new \SplFileInfo(
                'https://api.actionpay.ru/ru/apiWmMyOffers/?key=E1RBQymTBLV53g92yjZc&format=xml'
            );
            $output->writeln('will download '.$fileinfo->getPathname());
        }
        //actionpay, получим список компаний
        $fileactionpayXml = new \SplFileObject('var/actionpay.csv', 'w');
        file_put_contents('var/actionpay.xml', file_get_contents($fileinfo->getPathname()));
        $output->writeln('actionpay.xml saved');
        if (false) {
            foreach ($this->xmlReader->getElementsData($fileinfo, 'favouriteOffer') as $key => $data) {
                foreach ($data as $name => $value) {
                    if (is_array($value)) {
                        if (isset($value['#'])) {
                            $value = $value['#'];
                        } else {
                            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                        }
                    }
                    $fileactionpayXml->fputcsv([$key, $name, trim($value)]);
                }
                if (isset($data['offer']['id']['#'])) {
                    $url = 'https://api.actionpay.ru/ru/apiWmOffers/?key=E1RBQymTBLV53g92yjZc&format=xml&offer='.$data['offer']['id']['#'];
                    $fileactionpayXml->fputcsv([$key, 'url', trim($url)]);
                }
                $output->writeln($key.' actionpay.csv saved');
            }
        }
        foreach (glob('var/actionpay/*.xml') as $key => $xmlFilePath) {
            $filePriceListXml = new \SplFileInfo($xmlFilePath);
            $output->writeln('start parsing '.$filePriceListXml->getBasename());
            if ($filePriceListXml->isFile() && !is_file($xmlFilePath.'.csv')) {
                $filePriceListCsv = new \SplFileObject($xmlFilePath.'.csv', 'w');
                try {

                    foreach ($this->xmlReader->getElementsData(
                        $filePriceListXml,
                        'offer'
                    ) as $offerNumber => $offerData) {
                        foreach ($offerData as $name => $value) {
                            if (is_array($value)) {
                                if (isset($value['#'])) {
                                    $value = $value['#'];
                                } elseif ($name == 'Ymls' && isset($value['Yml']['#'])) {
                                    $filePriceListCsv->fputcsv(
                                        [$offerNumber, 'urls', $value['Yml']['#'], $filePriceListXml->getBasename()]
                                    );
                                } elseif($name == 'Ymls' && isset($value['Yml'][0]['#'])) {
                                    foreach($value['Yml'] as $ymlData){
                                        $filePriceListCsv->fputcsv(
                                            [$offerNumber, 'urls', $ymlData['#'], $filePriceListXml->getBasename()]
                                        );
                                    }
                                } else {
                                    $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                                }
                            }
                            $filePriceListCsv->fputcsv([$offerNumber, $name, $value, $filePriceListXml->getBasename()]);
                        }
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

        $pdo = new \PDO('mysql:dbname=import;host=127.0.0.1', 'root', 'chease');

        foreach (glob('var/actionpay/*.csv') as $key => $csvFilePath) {
            $fileInfo = new \SplFileInfo($csvFilePath);
            if ($fileInfo->isFile()) {
                $output->writeln('file csv load '.$csvFilePath);
                $pdo->exec(
                    'LOAD DATA INFILE "'.$fileInfo->getRealPath(
                    ).'" ignore INTO TABLE key_offer_ap FIELDS TERMINATED BY "," ENCLOSED BY \'"\' LINES TERMINATED BY "\n";'
                );
                $output->writeln('file csv loaded '.$csvFilePath);
            }
        }


        //Ошибки пишем рядом с файлом, добавляем в конце fail расширение
        //Если все ок, парсим файл, пишем csv key,name,value,company
        //Сохраняем имя csv в общем массиве.
        //Делаем то же самое для actionpay

        //Получаем общий массив файлов сsv, запускаем скрипт импорта. Ждем.
        $output->writeln($key);
    }
}
