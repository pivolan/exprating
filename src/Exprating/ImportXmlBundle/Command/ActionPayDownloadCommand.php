<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\ActionPayFiles;
use Exprating\ImportXmlBundle\Filesystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActionPayDownloadCommand extends Command
{
    const URL_ACTIONPAY_XML = 'https://api.actionpay.ru/ru/apiWmMyOffers/?key=E1RBQymTBLV53g92yjZc&format=xml';

    /**
     * @var ActionPayFiles
     */
    private $actionPayFiles;

    /**
     * @param ActionPayFiles $actionPayFiles
     */
    public function setActionPayFiles(ActionPayFiles $actionPayFiles)
    {
        $this->ActionPayFiles = $actionPayFiles;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:actionpay:download')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileinfo = new \SplFileInfo(self::URL_ACTIONPAY_XML);
        $fileXml = $this->actionPayFiles->getFileInfoXml();
        if (!is_dir($fileXml->getPath())) {
            $output->writeln('Create dir '.$fileXml->getPath());
            mkdir($fileXml->getPath(), 0777, true);
        }
        $output->writeln('Start download '.$fileinfo->getPathname());

        file_put_contents($fileXml->getPathname(), file_get_contents($fileinfo->getPathname()));
        $output->writeln('Saved! '.$fileXml->getPathname());
    }
}