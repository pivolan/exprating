#!/usr/local/opt/php70/bin/php
<?php

namespace TestPerf;

/**
 * Date: 03.04.16
 * Time: 0:08
 */
$csv = 'id, e_id,article,category_id,description,model,modified_time,name,price,old_price,is_topseller,url,vendor,vendor_code,params,pictures,hash';
$csv = '';
$time = time();
$file = new \SplFileObject('test2.csv', 'w');
$xmlReader = new \XMLReader();
$xmlReader->open('/Users/igorpecenikin/Downloads/blackfriday-2015-admitad_products_20160401_152255.xml');
$pdo = new \PDO('mysql:dbname=import;host=127.0.0.1', 'root', 'chease');
$query = $pdo->query("select hash from product", \PDO::FETCH_ASSOC);
$hashes = [];
foreach ($query as $row) {
    $hashes[$row['hash']] = true;
}
$hashes2 = $hashes;
$values = '';
for ($i = 0; $i < 600000; $i++) {
    if (!isset($hashes[$i])) {
        $csv = ";X15042088784$i; H6XMP005002001; 321654654; Состав: 69% полиэстер\,29% вискоза\,2% спандекс. Артикул: H6XMP005002001. Страна\\n                    производства: КИТАЙ. Коллекция: ФЕВРАЛЬ 2015. Цвет: черный. Сезон: весна-лето; Брюки Mexx H6XMP005002001; $time; Брюки; 1380; 4599; 1; 0; 1; 1;                 <param name=\"Состав:\">69% полиэстер\,29% вискоза\,2% спандекс</param>\\n                <param name=\"Артикул:\">H6XMP005002001</param>\\n                <param name=\"Страна производства:\">КИТАЙ</param>\\n                <param name=\"Коллекция:\">ФЕВРАЛЬ 2015</param>\\n                <param name=\"Цвет:\">черный</param>\\n                <param name=\"Сезон:\">весна-лето</param>\\n                <param name=\"Коллекция\">ИЮЛЬ 2015</param>\\n                <param name=\"Сезон\">Осень/Зима</param>\\n                <param name=\"Цвет\">черный</param>\\n                <param name=\"Размер\" quantity=\"2\" type=\"size\" unit=\"EU\">46</param>\\n                <param name=\"Размер\" quantity=\"1\" type=\"size\" unit=\"EU\">54</param>\\n;                 <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-1b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-2b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-3b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-4b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-5b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-6b.jpg</picture>\\n;$i\n";
//        $values .= "('','X15042088784$i',' H6XMP005002001',' 321654654',' Состав: 69% полиэстер\,29% вискоза\,2% спандекс. Артикул: H6XMP005002001. Страна\\n                    производства: КИТАЙ. Коллекция: ФЕВРАЛЬ 2015. Цвет: черный. Сезон: весна-лето',' Брюки Mexx H6XMP005002001',' $time',' Брюки',' 1380',' 4599',' 1',' 0',' 1',' 1','                 <param name=\"Состав:\">69% полиэстер\,29% вискоза\,2% спандекс</param>\\n                <param name=\"Артикул:\">H6XMP005002001</param>\\n                <param name=\"Страна производства:\">КИТАЙ</param>\\n                <param name=\"Коллекция:\">ФЕВРАЛЬ 2015</param>\\n                <param name=\"Цвет:\">черный</param>\\n                <param name=\"Сезон:\">весна-лето</param>\\n                <param name=\"Коллекция\">ИЮЛЬ 2015</param>\\n                <param name=\"Сезон\">Осень/Зима</param>\\n                <param name=\"Цвет\">черный</param>\\n                <param name=\"Размер\" quantity=\"2\" type=\"size\" unit=\"EU\">46</param>\\n                <param name=\"Размер\" quantity=\"1\" type=\"size\" unit=\"EU\">54</param>\\n','                 <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-1b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-2b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-3b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-4b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-5b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-6b.jpg</picture>\\n','$i'),";
        $file->fwrite($csv);
    }
    if (isset($hashes[$i])) {
        unset($hashes2[$i]);
    }
}
//$values .= "('','X15042088784$i',' H6XMP005002001',' 321654654',' Состав: 69% полиэстер\,29% вискоза\,2% спандекс. Артикул: H6XMP005002001. Страна\\n                    производства: КИТАЙ. Коллекция: ФЕВРАЛЬ 2015. Цвет: черный. Сезон: весна-лето',' Брюки Mexx H6XMP005002001',' $time',' Брюки',' 1380',' 4599',' 1',' 0',' 1',' 1','                 <param name=\"Состав:\">69% полиэстер\,29% вискоза\,2% спандекс</param>\\n                <param name=\"Артикул:\">H6XMP005002001</param>\\n                <param name=\"Страна производства:\">КИТАЙ</param>\\n                <param name=\"Коллекция:\">ФЕВРАЛЬ 2015</param>\\n                <param name=\"Цвет:\">черный</param>\\n                <param name=\"Сезон:\">весна-лето</param>\\n                <param name=\"Коллекция\">ИЮЛЬ 2015</param>\\n                <param name=\"Сезон\">Осень/Зима</param>\\n                <param name=\"Цвет\">черный</param>\\n                <param name=\"Размер\" quantity=\"2\" type=\"size\" unit=\"EU\">46</param>\\n                <param name=\"Размер\" quantity=\"1\" type=\"size\" unit=\"EU\">54</param>\\n','                 <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-1b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-2b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-3b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-4b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-5b.jpg</picture>\\n                <picture>http://mexx.ru/images/P0/02/30/11/86/brjuki-mexx-chernyjj-h6xmp005002001-6b.jpg</picture>\\n','$i')";
$file->fwrite($csv);
if (count($hashes2)) {
    $hashesToDelete = array_keys($hashes2);
    $param = implode("','", $hashesToDelete);
    echo $pdo->exec("delete from product where hash IN ('$param')")."\n\n";
}
//$pdo->exec('LOAD DATA INFILE "/Users/igorpecenikin/PhpstormProjects/exprating/test.csv" ignore INTO TABLE product FIELDS TERMINATED BY ";" LINES TERMINATED BY "\n";');
//file_put_contents('test.csv', $csv);
echo memory_get_peak_usage() / 1024 / 1024;