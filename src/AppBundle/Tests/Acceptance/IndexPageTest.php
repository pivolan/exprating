<?php

/**
 * Date: 17.02.16
 * Time: 15:47.
 */

namespace AppBundle\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexPageTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();

        //Проверяем главную страницу
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $aLink = $crawler->filter('div.footer-wrap a:contains("Логин")');
        $this->assertEquals(1, $aLink->count());
        //Ищем ссылку входа
        $link = $crawler->selectLink('Логин')->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Войти', $client->getResponse()->getContent());
        //Авторизуемся
        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'expert';
        $form['_password'] = 'qwerty';
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $crawler = $client->followRedirect();
        //Проверяем главную авторизованными
        $this->assertEquals(0, $crawler->filter('div.footer-wrap a:contains("Логин")')->count());
        $this->assertEquals(1, $crawler->filter('div.footer-wrap a:contains("Выход")')->count());

        //Входим на страницу категорий
        $link = $crawler->selectLink('Электроника')->link();
        $crawler = $client->click($link);
        $this->assertContains('Посмотреть свободные товары', $client->getResponse()->getContent());
        $linkExpert = $crawler->selectLink('Посмотреть свободные товары')->link();

        //Находим свободный товар
        $crawler = $client->click($linkExpert);
        $aTovarLink = $crawler->filter('div.rubric-wrapper li a')->first();
        $this->assertEquals(1, $aTovarLink->count());
        $href = $aTovarLink->attr('href');
        $tovatTitle = $aTovarLink->filter('div.sticker-title')->text();
        $this->assertContains('/tovar/', $href);
        $crawler = $client->request('GET', $href);
        //Берем на редактирование
        $takeEditlink = $crawler->selectLink('Взять на редактирование')->link();
        $crawler = $client->click($takeEditlink);
        $this->assertContains('Сохранить', $client->getResponse()->getContent());
        $this->assertContains('До окончания резервирования осталось 30 дней', $crawler->filter('span.label')->text());

        //Проверяем что другим товар больше не доступен
        $crawler = $client->click($linkExpert);
        $this->assertEquals(0, $crawler->filter("a[href='$href']")->count());
        //return to edit page
        $crawler = $client->click($takeEditlink);
        $form = $crawler->selectButton('Сохранить')->form();
        $form['product[rating1]'] = 50;
        $client->submit($form);
        $crawler = $client->followRedirect();
        //Сохраняем проверяем что изменения сохранены
        $this->assertContains('Изменения сохранены', $client->getResponse()->getContent());
        $this->assertEquals(50, $crawler->filter('input[name="product[rating1]"]')->attr('value'));
        //Посмотрим на страницу товара, проверим что изменение сохранилось
        $link = $crawler->selectLink('Просмотр')->link();
        $crawler = $client->click($link);
        $this->assertContains('50', $crawler->filter('ul.media-list')->text());
        //Зайдем на страницу товара и попробуем опубликовать
        $crawler = $client->click($takeEditlink);
        $form = $crawler->selectButton('Опубликовать')->form();
        $client->submit($form, ['product[publish]' => 'Опубликовать']);
        $crawler = $client->followRedirect();
        $this->assertContains('Ваш обзор отправлен на премодерацию куратором. О его решении вы будете уведомлены по email', $crawler->filter('span.label')->parents()->text());
        //Проверим что повторно опубликовать обзор нельзя
        $crawler = $client->click($takeEditlink);
        $buttonPublish = $crawler->filter('#product_publish');
        $this->assertContains('disabled', $buttonPublish->attr('disabled'));
        //Логинимся под Куратором
        $client->restart();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'curator';
        $form['_password'] = 'qwerty';
        $client->submit($form);
        $crawler = $client->followRedirect();
        //Смотрим в списке ожидающих
        $link = $crawler->selectLink('Куратор')->link();
        $crawler = $client->click($link);
        $link = $crawler->selectLink('Ожидающие товары')->link();
        $crawler = $client->click($link);
        $tovarWait = $crawler->filter('div.rubric-wrapper li a')->first();
        $this->assertContains($tovatTitle, $tovarWait->text());
        $crawler = $client->request('GET', $tovarWait->attr('href'));
        //Проверяем наличие кнопок одобрить и отклонить
        $this->assertEquals(1, $crawler->filter('button:contains("Одобрить")')->count());
        $approveForm = $crawler->selectButton('Одобрить')->form();
        //Одобряем
        $client->submit($approveForm, ['decision[approve]' => 'Одобрить']);
        $client->followRedirect();
        $this->assertContains('Обзор успешно опубликован', $client->getResponse()->getContent());
        $link = $crawler->selectLink('Куратор')->link();
        $crawler = $client->click($link);
        $link = $crawler->selectLink('Ожидающие товары')->link();
        $crawler = $client->click($link);
        //Проверим что в списке ожидающих товара больше нет
        $this->assertNotContains($tovatTitle, $client->getResponse()->getContent());

        //Проверим что товар появился в общем списке c фильтром по дате по убывающей
        $link = $crawler->selectLink('Электроника')->link();
        $crawler = $client->click($link);
        $link = $crawler->selectLink('по дате')->link();
        $crawler = $client->click($link);
        $link = $crawler->selectLink('по убыванию')->link();
        $client->click($link);

        $this->assertContains($tovatTitle, $client->getResponse()->getContent());
        //Проверим что товар доступен не авторизованным
        $client->request('GET', '/logout');

        $client->request('GET', '/');
        //Сразу войдем по ссылке с фильтром по убывающей по дате.
        $client->click($link);
        $this->assertContains($tovatTitle, $client->getResponse()->getContent());
    }
}
