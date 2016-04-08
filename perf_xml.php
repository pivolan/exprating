#!/usr/local/opt/php70/bin/php
<?php

namespace TestPerf;

/**
 * Date: 03.04.16
 * Time: 0:08
 */
$forksCount = 0;
foreach (glob('/Users/igorpecenikin/PhpstormProjects/exprating/var/pricelists/*.xml') as $path) {
    $forksCount++;
    $pid = 0;//pcntl_fork();
    if ($pid == 0) {
        echo $path."\n";
        $reader = new \XMLReader();
        $reader->open($path);
        $elementForSearch = 'offer';
        do {
            $read = $reader->read();
            $localName = $reader->localName;
        } while ($read && $localName !== 'company');
        $company = $reader->readInnerXml();
        echo $company."\n";

        $category = 'category';
        do {
            $read = $reader->read();
            $localName = $reader->localName;
        } while ($read && $localName !== $category);

        while ($read && $localName === $category) {
            $out = $reader->readOuterXML()."\n";
            $next = $reader->next();
            $localName = $reader->localName;
        }


        do {
            $next = $reader->read();
            $localName = $reader->localName;
        } while ($next && $localName !== $elementForSearch);

// Now that we're at the right depth, hop to the next <position/> until the end of the tree.
        $i = 0;
        while ($localName === $elementForSearch && $reader->next($elementForSearch)) {


            $localName = $reader->localName;
            $i++;
        }
        echo "$i \n\n";
        exit();
    }
    if ($forksCount >= 8) {
        while (pcntl_wait($status) == 0) {
            sleep(5);
        }
        $forksCount--;
        echo 'fork removed, start new fork. Forks: '.$forksCount;
    }
}
while (pcntl_wait($status) != -1) {
    sleep(5);
}
echo memory_get_peak_usage() / 1024 / 1024;
