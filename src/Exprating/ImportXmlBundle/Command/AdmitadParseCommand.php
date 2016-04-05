<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdmitadParseCommand extends Command
{
    private $varDir;

    protected function configure()
    {
        $this
            ->setName('import_xml:admitad:parse')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $fileinfo = new \SplFileInfo();
        if (!$fileinfo->isFile()) {
            $fileinfo = new \SplFileInfo(
                self::URL_ADMITAD_XML
            );
        }
        $admitadDir = $this->varDir.'/admitad';
        if (!is_dir($admitadDir)) {
            $output->writeln('Create dir '.$admitadDir);
            mkdir($admitadDir);
        }

        file_put_contents($admitadDir.'/admitad.xml', file_get_contents($fileinfo->getPathname()));
        $output->writeln('Saved! '.$admitadDir.'/admitad.xml');
    }
}