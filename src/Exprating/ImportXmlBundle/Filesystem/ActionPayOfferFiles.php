<?php
/**
 * Date: 05.04.16
 * Time: 14:32
 */

namespace Exprating\ImportXmlBundle\Filesystem;


use Exprating\ImportXmlBundle\XmlDto\ActionPayOffer;

class ActionPayOfferFiles
{
    const FOLDER_ACTIONPAY = '/../var/import_xml/actionpay/actionpay_offers';
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param ActionPayOffer $actionPayOffer
     *
     * @return \SplFileInfo
     */
    public function getFileInfoCsv()
    {
        return new \SplFileInfo(
            $this->rootDir.self::FOLDER_ACTIONPAY.'/offers.csv'
        );
    }

    /**
     * @param ActionPayOffer $actionPayOffer
     *
     * @return \SplFileInfo
     */
    public function getFileInfoXml(ActionPayOffer $actionPayOffer)
    {
        return new \SplFileInfo(
            $this->rootDir.self::FOLDER_ACTIONPAY.'/'.$actionPayOffer->name.'-'.$actionPayOffer->id.'.xml'
        );
    }

    /**
     * @return \SplFileInfo
     */
    public function getFolder()
    {
        return new \SplFileInfo($this->rootDir.self::FOLDER_ACTIONPAY);
    }

    /**
     * @inheritdoc
     */
    public function __construct($varDir)
    {
        $this->rootDir = $varDir;
    }
}