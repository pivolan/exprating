<?php
/**
 * Date: 24.05.16
 * Time: 5:03
 */

namespace Exprating\ImportXmlBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    const ARG_TYPE = 'type';
    const TYPE_ADMITAD = 'admitad';
    const TYPE_ACTIONPAY = 'actionpay';

    private $rootDir;

    /**
     * @param mixed $rootDir
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }


    protected function configure()
    {
        $this
            ->setName('import_xml:clear')
            ->setDescription('Чистка папок с временными файлами для парсинга и скачивания товаров партнеров')
            ->addArgument(self::ARG_TYPE, null, 'maybe admitad or actionpay');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument(self::ARG_TYPE);
        if ($type == self::TYPE_ACTIONPAY) {
            exec("rm -R {$this->rootDir}/../var/import_xml/actionpay/");
        } elseif ($type == self::TYPE_ADMITAD) {
            exec("rm -R {$this->rootDir}/../var/import_xml/admitad/");
        } else {
            $output->writeln('Неверно указан тип');
            return;
        }
        exec("rm -R {$this->rootDir}/../var/import_xml/offers/");
    }
}