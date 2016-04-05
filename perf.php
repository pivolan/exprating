#!/usr/local/opt/php70/bin/php
<?php

namespace TestPerf;

/**
 * Date: 03.04.16
 * Time: 0:08
 */

$file = new \SplFileObject('test.csv', 'w');
for ($i = 0; $i < 1000; $i++) {
    $csv = ['asd' => "X15042088784$i", 'asdsd' => "H6XMP005002001", '1' => "321654654"];
    $file->fputcsv($csv);
}
$csv = ["X15042088784$i", "H6XMP005002001", "321654654"];
$file->fputcsv($csv);
echo memory_get_peak_usage() / 1024 / 1024;