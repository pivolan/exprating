<?php
/**
 * Date: 05.04.16
 * Time: 14:32
 */

namespace Exprating\ImportXmlBundle\Filesystem;


class ActionPayFiles
{
    const FILENAME_ACTIONPAY_CSV = '/actionpay.csv';
    const FOLDER_ACTIONPAY = '/../var/actionpay';
    const FILENAME_ACTIONPAY_XML = '/actionpay.xml';
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoCsv()
    {
        return new \SplFileInfo($this->rootDir.self::FOLDER_ACTIONPAY.self::FILENAME_ACTIONPAY_CSV);
    }

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoXml()
    {
        return new \SplFileInfo($this->rootDir.self::FOLDER_ACTIONPAY.self::FILENAME_ACTIONPAY_XML);
    }

    /**
     * @inheritdoc
     */
    public function __construct($varDir)
    {
        $this->rootDir = $varDir;
    }
}