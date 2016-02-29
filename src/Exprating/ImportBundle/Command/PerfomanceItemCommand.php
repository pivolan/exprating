<?php
/**
 * Date: 12.02.16
 * Time: 19:26
 */

namespace Exprating\ImportBundle\Command;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\ImportBundle\Entity\AliasItem;
use Exprating\ImportBundle\Entity\Categories;
use Exprating\ImportBundle\Entity\Item;
use Exprating\ImportBundle\Entity\SiteProductRubrics;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PerfomanceItemCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityManager
     */
    protected $emImport;

    /**
     * @var Slugify
     */
    protected $slugify;

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Slugify $slugify
     */
    public function setSlugify(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @param EntityManager $emImport
     */
    public function setEmImport(EntityManager $emImport)
    {
        $this->emImport = $emImport;
    }

    protected function configure()
    {
        $this
            ->setName('import:test_item')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Получаем Итем для импорта
        $itemIterate = $this->emImport->getRepository('ExpratingImportBundle:Item')->getAllQuery()->iterate();
        $this->emImport->getConnection()->getConfiguration()->setSQLLogger(null);
        $i = 0;
        foreach ($itemIterate as $row) {
            /** @var Item $item */
            $item = $row[0];
            echo ++$i . " \n";
            $this->emImport->clear();
        }
    }
}