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
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class RepairCategoryCommand
 * @package Exprating\ImportBundle\Command
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class XmlReaderCommand extends Command
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
            ->setName('import:xml_reader')
            ->setDescription('Greet someone')
            ->addArgument(self::ARG_FILE);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filepath = $input->getArgument(self::ARG_FILE);
        $fileinfo = new \SplFileInfo('var/admitad.xml');
        if (!$fileinfo->isFile()) {
            $fileinfo = new \SplFileInfo(
                'http://export.admitad.com/ru/webmaster/websites/40785/partners/export/?user=Antonlukk&code=7569a359ca&format=xml&filter=1&keyword=&region=00&action_type=&status=active&format=xml'
            );
        }
        //admitad, получим список компаний
        $fileAdmitadXml = new \SplFileObject('var/admitad.csv', 'w');
        file_put_contents('var/admitad.xml', file_get_contents($fileinfo->getPathname()));
        $output->writeln('admitad.xml saved');
        if (false) {
            foreach ($this->xmlReader->getElementsData($fileinfo, 'advcampaign') as $key => $data) {
                foreach ($data as $name => $value) {
                    if (is_array($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }
                    $fileAdmitadXml->fputcsv([$key, $name, trim($value)]);
                }
                $output->writeln($key.' admitad.csv saved');
                if (false && isset($data['original_products'])) {

                    $priceListUrl = $data['original_products'];
                    //Идем по списку, качаем файлы, имя файла как md5 от ссылки, сразу парсим
                    $companyName = $this->slugify->slugify($data['name']);
                    $priceListXmlFilePath = 'var/admitad/'.$companyName.'.xml';
                    $filePriceListXml = new \SplFileInfo($priceListXmlFilePath);
                    if (!$filePriceListXml->isFile()) {
                        $filePriceListXml = new \SplFileInfo($priceListUrl);
                    }
                    $filePriceListCsv = new \SplFileObject('var/admitad/'.md5($priceListUrl).'.csv', 'w');
                    //запишем этот список в папку.
                    //Создаем директорию для этого списка
                    file_put_contents(
                        $priceListXmlFilePath,
                        file_get_contents($filePriceListXml->getPathname())
                    );
                    $output->writeln('pricelist xml '.$priceListXmlFilePath.' saved');
                }
            }
        }
        foreach (glob('var/admitad/11934.xml') as $key => $xmlFilePath) {
            $filePriceListXml = new \SplFileInfo($xmlFilePath);
            $output->writeln('start parsing '.$filePriceListXml->getBasename());
            if ($filePriceListXml->isFile() && !is_file($xmlFilePath.'.csv')) {
                $filePriceListCsv = new \SplFileObject($xmlFilePath.'.csv', 'w');
                try {

                    foreach ($this->xmlReader->getElementsData(
                        $filePriceListXml,
                        'offkker'
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


        //Ошибки пишем рядом с файлом, добавляем в конце fail расширение
        //Если все ок, парсим файл, пишем csv key,name,value,company
        //Сохраняем имя csv в общем массиве.
        //Делаем то же самое для actionpay

        //Получаем общий массив файлов сsv, запускаем скрипт импорта. Ждем.
        $output->writeln($key);
    }
}
