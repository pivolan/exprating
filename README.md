exprating
=========

A Symfony project created on February 1, 2016, 9:11 am.

git clone

composer install

bin/console doctrine:database:create

bin/console doctrine:migrations:migrate

bin/console doctrine:fixtures:load

Тестирование:

vendor/bin/phpunit
запускать находясь в корне проекта. Автоматически подхватится конфиг phpunit.xml.dist

Подробнее:
База для тестов и для сайта должна отличаться. Перед запуском тестов, необходимо создать базу данных. Базу создавать
с типом таблиц utf8_unicode_ci