<?php
/**
 * Date: 12.02.16
 * Time: 19:26
 */

namespace Exprating\ImportBundle\Command;


use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
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
use Symfony\Component\Finder\SplFileInfo;

class ImportItemCommand extends ContainerAwareCommand
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
     * @var string
     */
    protected $rootDir;

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

    /**
     * @param string $rootDir
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    protected function configure()
    {
        $this
            ->setName('import:item')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->emImport->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        //Получаем Итем для импорта
        $itemIterate = $this->emImport->getRepository('ExpratingImportBundle:Item')->getAllQuery()->iterate();

        foreach ($itemIterate as $key => $row) {
            /** @var Item $item */
            $item = $row[0];

            //Определяем категорию через alias таблицу категорий
            $categoryId = $item->getCategory()->getAliasCategory()->getCategoryExpratingId();
            $category = $this->em->getReference(Category::class, $categoryId);

            //Проверяем таблицу aliasItem
            //Проверяем что есть, просто по ID
            if ($item->getAliasItem()) {
                //Если есть, смотрим по aliasCategory и обновляем категорию
                $product = $this->em->getRepository('AppBundle:Product')->findOneBy(['slug' => $item->getAliasItem()->getItemExpratingSlug()]);
                if ($product) {
                    $product->setCategory($category);
                }
                //Поиск картинки
                $dir_iterator = new \RecursiveDirectoryIterator($this->rootDir . '/../var/pics');
                $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
                /** @var \SplFileInfo $file */
                if (!$product->getPreviewImage()) {
                    foreach ($iterator as $file) {
                        if ($file->isFile() && $product->getId() == $file->getBasename('.' . $file->getExtension())) {
                            //Генерация пути для картинки
                            $picsFolder = $this->rootDir . '/../web';
                            $targetWebFolder = '/pics/products/' . ($product->getId() % 10000) . '/';
                            $targetFolder = $picsFolder . $targetWebFolder;
                            $targetFilename = $file->getBasename();
                            //Копирование картинки
                            $targetAbsolutePath = $targetFolder . $targetFilename;
                            $targetFile = new \SplFileInfo($targetAbsolutePath);
                            if (!$targetFile->getPathInfo()->isDir()) {
                                mkdir($targetFile->getPath(), 0777, true);
                            }
                            copy($file->getRealPath(), $targetFile->getPath().$targetFile->getFilename());
                            //сохранение картинки
                            $product->setPreviewImage($targetWebFolder . $targetFilename);
                            $image = $this->em->getRepository('AppBundle:Image')->find($targetWebFolder . $targetFilename);
                            if (!$image) {
                                $image = new Image();
                                $image->setProduct($product)
                                    ->setName($targetFilename)
                                    ->setIsMain(true)
                                    ->setFilename($targetWebFolder . $targetFilename);

                            }
                            $product->addImage($image);
                            $this->em->persist($image);
                        }
                    }
                }
            } else {
                /**
                 * Если нет, определяем по aliasCategory категорию, остальные параметры импортируем как есть.
                 * slug генерируем по имени, параметры импортируем, создаем если нет. Записываем в aliasItem соответствие
                 */
                $product = new Product();
                $product->setCategory($category)
                    ->setName($item->getName())
                    ->setSlug(pathinfo($item->getUrl())['filename'])
                    ->setRating4(round($item->getRating() / 5 * 100));

                $aliasItem = new AliasItem();
                $aliasItem->setItemIrecommend($item)
                    ->setItemExpratingName($product->getName())
                    ->setItemExpratingSlug($product->getSlug());
                $item->setAliasItem($aliasItem);
                $parameterSlugUnique = [];
                //импорт параметров (характеристик)
                foreach ($item->getParameters() as $parameter) {
                    $slug = $this->slugify->slugify($parameter->getGroupName());
                    if (in_array($slug, $parameterSlugUnique)) {
                        continue;
                    }
                    $parameterSlugUnique[] = $slug;
                    /** @var Characteristic $characteristic */
                    $characteristic = $this->em->getRepository('CharacteristicBundle:Characteristic')->find($slug);
                    if (!$characteristic) {
                        $characteristic = new Characteristic();
                        $characteristic
                            ->setSlug($slug)
                            ->setName($parameter->getGroupName())
                            ->setLabel($parameter->getGroupName())
                            ->setType(Characteristic::TYPE_STRING);
                    }
                    $productCharacteristic = new ProductCharacteristic();
                    $productCharacteristic->setCharacteristic($characteristic)
                        ->setProduct($product)
                        ->setValue($parameter->getName());
                    $this->em->persist($productCharacteristic);
                    $this->em->persist($characteristic);
                }
                $output->writeln($product->getId() . ' ' . $product->getName());
                $this->em->persist($product);
                $this->emImport->persist($aliasItem);
            }

            if ($key % 5000 === 0) {
                $this->emImport->flush();
                $this->em->flush();
                $this->emImport->clear();
                $this->em->clear();
            }
        }
    }
}