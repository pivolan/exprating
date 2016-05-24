<?php
/**
 * Date: 05.04.16
 * Time: 14:32
 */

namespace Exprating\ImportXmlBundle\Filesystem;


class AdmitadFiles
{
    const FILENAME_ADMINER_CSV = '/admitad.csv';
    const FOLDER_ADMINER = '/../var/import_xml/admitad';
    const FILENAME_ADMINER_XML = '/admitad.xml';
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoCsv()
    {
        return new \SplFileInfo($this->rootDir.self::FOLDER_ADMINER.self::FILENAME_ADMINER_CSV);
    }

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoXml()
    {
        return new \SplFileInfo($this->rootDir.self::FOLDER_ADMINER.self::FILENAME_ADMINER_XML);
    }

    /**
     * @inheritdoc
     */
    public function __construct($varDir)
    {
        $this->rootDir = $varDir;
    }
}