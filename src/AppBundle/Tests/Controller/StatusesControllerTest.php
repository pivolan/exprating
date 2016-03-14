<?php
/**
 * Date: 14.03.16
 * Time: 12:03
 */

namespace AppBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusesControllerTest extends WebTestCase
{
    /**
     * @param $url
     *
     * @dataProvider getAnonUrls
     */
    public function testStatus200($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
    }

    public function getAnonUrls()
    {
        return [
            ['/'],
            ['/login'],
            ['/experts'],
            ['/experts/2'],
            ['/expert/admin'],
            ['/expert/curator'],
            ['/expert/expert'],
            ['/rubric/dlya-vseh/elektronika'],
            ['/rubric/dlya-vseh/elektronika/2'],
            ['/tovar/search'],
            ['/tovar/search?search_params[string]=titl'],
            ['/tovar/search/3?search_params[string]=titl'],
            ['/want-to-become-an-expert'],
            ['/resetting/request'],
        ];
    }

    /**
     * @param $url
     *
     * @dataProvider getAdminUrls
     */
    public function testStatus200Admin($url)
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $url,
            [],
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
    }

    public function getAdminUrls()
    {
        return [
            ['/rubric/dlya-vseh/elektronika/1/minPrice/ASC/free'],
            ['/rubric/dlya-vseh/elektronika/1/minPrice/ASC/wait'],
            ['/expert/edit/admin'],
            ['/expert/edit/curator'],
            ['/expert/edit/expert'],
            ['/invite'],
            ['/profile/expert/published_items'],
            ['/profile/expert/published_items/1/elektronika'],
            ['/profile/expert/not_published_items'],
            ['/profile/expert/not_published_items/1/elektronika'],
            ['/profile/expert/categories'],
            ['/profile/expert/categories/1/elektronika'],
            ['/wait_list'],
            ['/curator/decisions'],
            ['/curator/experts'],
            ['/category_admin/categories'],
            ['/category_admin/categories/elektronika'],
            ['/category_admin/requests'],
            ['/moderator/comments'],
            ['/moderator/feedbacks'],
            ['/admin/experts'],
            ['/admin/experts/1/expert'],
            ['/admin/import_settings'],
        ];
    }
}