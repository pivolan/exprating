<?php
/**
 * Date: 05.04.16
 * Time: 14:32
 */

namespace Exprating\ImportXmlBundle\Filesystem;


use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;

class AdmitadPriceListFiles
{
    const FOLDER_ADMITAD = '/../var/offers';

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param AdmitadAdv $admitadAdv
     *
     * @return \SplFileInfo
     */
    public function getFileInfoCsv(AdmitadAdv $admitadAdv)
    {
        return new \SplFileInfo(
            $this->rootDir.self::FOLDER_ADMITAD.'/'.$admitadAdv->name.'_'.$admitadAdv->id.'.csv'
        );
    }

    /**
     * @param AdmitadAdv $admitadAdv
     *
     * @return \SplFileInfo
     */
    public function getFileInfoXml(AdmitadAdv $admitadAdv)
    {
        return new \SplFileInfo(
            $this->rootDir.self::FOLDER_ADMITAD.'/'.$admitadAdv->name.'_'.$admitadAdv->id.'.xml'
        );
    }

    /**
     * @return \SplFileInfo
     */
    public function getFolder()
    {
        return new \SplFileInfo($this->rootDir.self::FOLDER_ADMITAD);
    }

    /**
     * AdmitadPriceListFiles constructor.
     *
     * @param string $varDir
     */
    public function __construct($varDir)
    {
        $this->rootDir = $varDir;
    }
}