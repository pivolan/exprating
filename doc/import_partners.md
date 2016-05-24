***Импорт партнерских товаров***

***ADMITAD***

Очистим папки для работы с импортом

    bin/console import_xml:clear admitad

Скачиваем список компаний, команда в несколько потоков скачивает xml файлы компаний партнеров

    bin/console import_xml:admitad:download
    
Парсим полулченные xml, ззаписываем их в csv файлы, работа в несколько потоков. 
Использованы csv для возможности использовать несколько потоков. В эти csv пишутся названия компаний и ссылка на получение 
прайс листа для каждой компании.
    
    bin/console import_xml:admitad:parse

Берем данные из csv, получаем ссылки, скачиваем прайс листы xml, это будут xml оформленные по yandex market lang.
    
    bin/console import_xml:download:price_lists admitad

Парсим xml прайс листов с товарами. Записываем это в csv файлики, формат файлов как таблица partner_products.
Заодно проверяем уже существующие записи и удаляем устаревшие товары. Т.к. здесь есть работа с базой данных, 
нет возможности распараллелить процесс
    
    bin/console import_xml:parse:price_lists

Просто перегоняем файлы csv в mysql с помощью самой быстрой командой инсертов, load data file. Используется параметр
replace.
    
    bin/console import_xml:offer:csv_to_db

***ACTIONPAY***

Очистим папки для работы с импортом

    bin/console import_xml:clear actionpay
    
Скачиваем скидочные предложения со списком компаний
    
    bin/console import_xml:actionpay:download
    
Парсим списки компаний

    bin/console import_xml:actionpay:parse
    
Скачиваем информацию о компаниях

    bin/console import_xml:actionpay:download:offers
    
Парсим информацию о компаниях

    bin/console import_xml:actionpay:parse:offers
    
Скачиваем прайс листы

    bin/console bin/console import_xml:download:price_lists actionpay
    
Парсим прайс листы

    bin/console import_xml:parse:price_lists
    
Перегоняем прайс листы в базу

    bin/console import_xml:offer:csv_to_db
    
