<?php
/**
 * Date: 05.04.16
 * Time: 14:19
 */

namespace Exprating\ImportXmlBundle\Command;


use Exprating\ImportXmlBundle\Filesystem\AdmitadFiles;
use Exprating\ImportXmlBundle\Serialize\Normalizer\AdmitadAdvNormalizer;
use Exprating\ImportXmlBundle\Xml\XmlReader;
use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

class AdmitadParseCommand extends Command
{
    const TAG_NAME = 'advcampaign';
    /**
     * @var AdmitadFiles
     */
    private $admitadFiles;

    /**
     * @var XmlReader
     */
    private $xmlReader;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param AdmitadFiles $admitadFiles
     */
    public function setAdmitadFiles(AdmitadFiles $admitadFiles)
    {
        $this->admitadFiles = $admitadFiles;
    }

    /**
     * @param XmlReader $xmlReader
     */
    public function setXmlReader(XmlReader $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    protected function configure()
    {
        $this
            ->setName('import_xml:admitad:parse')
            ->setDescription('Greet someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileXmlInfo = $this->admitadFiles->getFileInfoXml();
        if (!$fileXmlInfo->isFile()) {
            $output->write(
                'No file('.$fileXmlInfo->getPathname().') found, please start import_xml:admitad:download first'
            );
        }
        $output->writeln('File found, start parsing '.$fileXmlInfo->getPathname());
        $fileAdmitadCsv = New \SplFileObject($this->admitadFiles->getFileInfoCsv()->getPathname(), 'w');
        foreach ($this->xmlReader->getElementsData($fileXmlInfo, self::TAG_NAME) as $key => $data) {
            /** @var AdmitadAdv $admitadAdv */
            $admitadAdv = $this->serializer->denormalize($data, AdmitadAdv::class);
            if ($admitadAdv->original_products) {
                $fileAdmitadCsv->fputcsv($this->serializer->normalize($admitadAdv));
            }
        }
        $output->writeln('Successful. Result in '.$fileAdmitadCsv->getPathname());
    }
}