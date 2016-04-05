<?php
/**
 * Date: 05.04.16
 * Time: 14:32
 */

namespace Exprating\ImportXmlBundle\Filesystem;


class AdmitadFiles implements FilesystemInterface
{
    const FILENAME_ADMINER_CSV = '/admitad.csv';
    const FOLDER_ADMINER = '/admitad';
    const FILENAME_ADMINER_XML = '/admitad.xml';
    /**
     * @var string
     */
    private $varDir;

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoCSV()
    {
        return new \SplFileInfo($this->varDir.self::FOLDER_ADMINER.self::FILENAME_ADMINER_CSV);
    }

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoXml()
    {
        return new \SplFileInfo($this->varDir.self::FOLDER_ADMINER.self::FILENAME_ADMINER_XML);
    }

    /**
     * @inheritdoc
     */
    public function __construct($varDir)
    {
        $this->varDir = $varDir;
    }
}