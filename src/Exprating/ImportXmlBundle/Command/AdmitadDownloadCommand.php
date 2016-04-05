<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Filesystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdmitadDownloadCommand extends Command
{
    /**
     * @var AdmitadFiles
     */
    private $admitadFiles;

    const URL_ADMITAD_XML = 'http://export.admitad.com/ru/webmaster/websites/40785/partners/export/?user=Antonlukk&code=7569a359ca&format=xml&filter=1&keyword=&region=00&action_type=&status=active&format=xml';

    /**
     * @param AdmitadFiles $admitadFiles
     */
    public function setAdmitadFiles(AdmitadFiles $admitadFiles)
    {
        $this->admitadFiles = $admitadFiles;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:admitad:download')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileinfo = new \SplFileInfo(self::URL_ADMITAD_XML);
        $fileXmlInfo = $this->admitadFiles->getFileInfoXml();
        if (!is_dir($fileXmlInfo->getPath())) {
            $output->writeln('Create dir '.$fileXmlInfo->getPath());
            mkdir($fileXmlInfo->getPath(), 0777, true);
        }
        $output->writeln('Start download '.$fileinfo->getPathname());
        $ch = curl_init($fileinfo->getPathname());
        if ($fileXmlInfo->isFile()) {
            $from = $fileXmlInfo->getSize();
            $output->writeln($from.' continue download file '.$fileXmlInfo->getPathname());
            curl_setopt($ch, CURLOPT_RANGE, $from.'-');
        }
        $fileXmlStream = fopen($fileXmlInfo->getPathname(), 'a');
        curl_setopt($ch, CURLOPT_FILE, $fileXmlStream);
        curl_exec($ch);
        $output->writeln('Saved! '.$fileXmlInfo->getPathname());
        fclose($fileXmlStream);
    }
}