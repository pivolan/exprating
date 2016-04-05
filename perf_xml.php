#!/usr/local/opt/php70/bin/php
<?php

namespace TestPerf;

/**
 * Date: 03.04.16
 * Time: 0:08
 */
$file = new \SplFileObject('test.csv', 'w');
foreach (range(1, 1000000) as $i => $b) {
    echo "$i before\n";
    $pid = @pcntl_fork();
    if ($pid == 0) {
        echo "started fork, I am a fork\n";
        $file->fputcsv(['test', 'ololo', 'werasdf']);
        exit();
    } elseif ($pid > 0) {
        echo "$i after\n";
    } elseif ($pid == -1) {
        echo "no fork started\n".pcntl_wait($status, WNOHANG)."\n";
    }
}
echo memory_get_peak_usage() / 1024 / 1024;
