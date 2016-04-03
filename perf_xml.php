#!/usr/local/opt/php70/bin/php
<?php

namespace TestPerf;

/**
 * Date: 03.04.16
 * Time: 0:08
 */
$reader = new \XMLReader();
//    $reader->open('/Users/igorpecenikin/Downloads/blackfriday-2015-admitad_products_20160401_152255.xml');
$reader->open('/Users/igorpecenikin/Downloads/programms.pretty.xml');
// Move to the first <position /> node.
//    $elementForSearch = 'offer';
$elementForSearch = 'advcampaign';
do {
    $reader->read();
} while ($reader->localName !== $elementForSearch);

// Now that we're at the right depth, hop to the next <position/> until the end of the tree.
$i = 0;
while ($reader->localName === $elementForSearch) {
    $reader->readOuterXML();
    $reader->next($elementForSearch);
    $i++;
}
echo "$i \n\n";
echo memory_get_peak_usage() / 1024 / 1024;
