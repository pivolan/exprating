exprating
=========

A Symfony project created on February 1, 2016, 9:11 am.

***Установка***:

git clone

composer install

bin/console doctrine:database:create

bin/console doctrine:migrations:migrate

bin/console doctrine:fixtures:load

***Тестирование***:

vendor/bin/phpunit
запускать находясь в корне проекта. Автоматически подхватится конфиг phpunit.xml.dist

***Подробнее***:

База для тестов и для сайта должна отличаться. Перед запуском тестов, необходимо создать базу данных. Базу создавать
с типом таблиц utf8_unicode_ci

***После обновления из репы***:

Если что-то сломалось - запускать:
1. composer install

2. bin/console doctrine:migrations:migrate

3. bin/console doctrine:fixtures:load

Первая команда обновит все зависимости и почистит кэш. Мы не храним все зависимости в репе.
Вторая команда обновит структуру базы данных.
Третья команда обновит данные в базе, точнее загрзуит все заного.
