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
class XmlReaderActionpayCommand extends Command
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
            ->setName('import:xml_reader_ap')
            ->setDescription('Greet someone');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (glob('var/actionpay_product/*.xml') as $key => $xmlFilePath) {
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
                                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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

        foreach (glob('var/actionpay_product/*.csv') as $key => $csvFilePath) {
            $fileInfo = new \SplFileInfo($csvFilePath);
            if ($fileInfo->isFile()) {
                $output->writeln('file csv load ' . $csvFilePath);
                $pdo->exec('LOAD DATA INFILE "'.$fileInfo->getRealPath().'" ignore INTO TABLE key_product_ap FIELDS TERMINATED BY "," ENCLOSED BY \'"\' LINES TERMINATED BY "\n";');
                $output->writeln('file csv loaded ' . $csvFilePath);
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
