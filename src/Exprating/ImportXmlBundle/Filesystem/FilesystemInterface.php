<?php
/**
 * Date: 05.04.16
 * Time: 14:29
 */

namespace Exprating\ImportXmlBundle\Filesystem;


interface FilesystemInterface
{
    /**
     * @return \SplFileInfo
     */
    public function getFileInfoCSV();

    /**
     * @return \SplFileInfo
     */
    public function getFileInfoXml();

    /**
     * FilesystemInterface constructor.
     *
     * @param string $varDir
     */
    public function __construct($varDir);
}