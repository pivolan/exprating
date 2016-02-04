<?php
/**
 * Date: 05.02.16
 * Time: 0:39
 */

namespace AppBundle\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\CssRewriteFilter;
use Assetic\Filter\FilterInterface;
use Assetic\Filter\LessFilter;
use Symfony\Component\Filesystem\Filesystem;

/*
 * Более подробно:
 *     https://gist.github.com/theodorDiaconu/ca20a4fbacc289b98237
 *     http://www.symfony.club/symfony2-deploying-assets-to-cdn/
 *     https://github.com/symfony/AsseticBundle/issues/50
 *
 */

class AsseticCdnFilter implements FilterInterface
{
    protected $cdnLayoutHost;
    protected $cdnStorageHost;
    protected $assetVersion;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    protected $webDir;

    /**
     * @param string     $assetVersion
     * @param string     $cdnLayoutHost
     * @param string     $cdnStorageHost
     * @param Filesystem $fileSystem
     * @param string     $webDir
     */
    function __construct($assetVersion, $cdnLayoutHost, $cdnStorageHost, Filesystem $fileSystem, $webDir)
    {
        $this->assetVersion = $assetVersion;
        $this->cdnLayoutHost = $cdnLayoutHost;
        $this->cdnStorageHost = $cdnStorageHost;
        $this->fileSystem = $fileSystem;
        $this->webDir = $webDir;
    }

    public function filterLoad(AssetInterface $asset)
    {
    }

    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();

        // Действует только для CSS:
        // $content = preg_replace ("#url\s*\(\s*(['\"]?)/bundles/#u", "url($1//{$this->cdnLayoutHost}/bundles/", $content);

        // Действует только для статики из бандлов, от Raritetus (от сторонних - не действует!)
        /*
         * url("/bundles/raritetusapp/css/jquery-ui-lightness/images/ui-bg_highlight-soft_100_eeeeee_1x100.png")
         * ->
         * url("//raritetus.cdnvideo.ru/bundles/raritetusapp/css/jquery-ui-lightness/images/ui-bg_highlight-soft_100_eeeeee_1x100.png?10")
         *
         * url('/bundles/raritetusapp/fonts/Exo2.0-Regular-webfont.svg#exo_2.0regular')
         * ->
         * url('//raritetus.cdnvideo.ru/bundles/raritetusapp/fonts/Exo2.0-Regular-webfont.svg?10#exo_2.0regular')
         */
        /*
            $content = preg_replace ("#([('\"])\s*(/bundles/raritetus[a-zA-Z0-9_/.-]+)(\#[a-zA-Z0-9_/.-]+)?\s*([('\"])#u",
            "$1//" . $this->cdnLayoutHost . "$2?" . $this->assetVersion . "$3$4", $content);
        */
        $pregPattern = "#([('\"])\s*(/bundles/raritetus[a-zA-Z0-9_/.-]+)(\#[a-zA-Z0-9_/.-]+)?\s*([('\"])#u";
        $content = preg_replace_callback($pregPattern, [$this, 'pregReplaceCallback'], $content);

        $asset->setContent($content);
    }

    protected function pregReplaceCallback($matches)
    {
        $version = $this->assetVersion;

        $localPath = $this->webDir . $matches[2];
        if ($this->fileSystem->isExists($localPath) and $this->fileSystem->is_file($localPath)) {
            $version = $this->fileSystem->filemtime($localPath);
        }

        return $matches[1] . '//' . $this->cdnLayoutHost . $matches[2] . '?' . $version . $matches[3] . $matches[4];
    }
}
