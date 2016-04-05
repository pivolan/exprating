<?php
/**
 * Date: 05.04.16
 * Time: 14:32
 */

namespace Exprating\ImportXmlBundle\Filesystem;


use Exprating\ImportXmlBundle\XmlDto\AdmitadAdv;

class AdmitadPriceListFiles
{
    const FOLDER_ADMINER = '/admitad';

    /**
     * @var string
     */
    private $varDir;

    /**
     * @param AdmitadAdv $admitadAdv
     *
     * @return \SplFileInfo
     */
    public function getFileInfoCsv(AdmitadAdv $admitadAdv)
    {
        return new \SplFileInfo(
            $this->varDir.self::FOLDER_ADMINER.'/'.$admitadAdv->name.'_'.$admitadAdv->id.'.csv'
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
            $this->varDir.self::FOLDER_ADMINER.'/'.$admitadAdv->name.'_'.$admitadAdv->id.'.xml'
        );
    }

    /**
     * AdmitadPriceListFiles constructor.
     *
     * @param string $varDir
     */
    public function __construct($varDir)
    {
        $this->varDir = $varDir;
    }
}