<?php

/**
 * Date: 12.02.16
 * Time: 19:26.
 */

namespace Exprating\ImportBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\RatingSettings;
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
        $fileinfo = new \SplFileInfo($filepath);
        $file = new \SplFileObject('key_product.csv', 'w');
        foreach ($this->xmlReader->getElementsData($fileinfo, 'offer') as $key => $data) {
            foreach ($data as $name => $value) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                }
                $file->fputcsv([$fileinfo->getBasename().$key, $name, trim($value)]);
            }
        }
        $output->writeln($key);
    }
}
