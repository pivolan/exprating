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
            ['/rubric/avtozapchasti-2/1/filter'],
            ['/rubric/avtozapchasti-2/2/filter'],
            ['/tovar/product_10'],
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
            ['/rubric/avtozapchasti-2/1/filter/minPrice/ASC/free'],
            ['/rubric/avtozapchasti-2/1/filter/minPrice/ASC/wait'],
            ['/tovar/product_10/choose_category'],
            ['/tovar/product_10/edit'],
            ['/tovar/product_10/change_expert'],
            ['/expert/edit/admin'],
            ['/expert/edit/curator'],
            ['/expert/edit/expert'],
            ['/invite'],
            ['/profile/expert/categories'],
            ['/profile/expert/categories/1/avtozapchasti-2'],
            ['/profile/expert/products'],
            ['/wait_list'],
            ['/curator/decisions'],
            ['/curator/experts'],
            ['/category_admin/create/category'],
            ['/category_admin/categories'],
            ['/category_admin/categories/avtozapchasti-2'],
            ['/category_admin/requests'],
            ['/moderator/comments'],
            ['/moderator/feedbacks'],
            ['/admin/experts'],
            ['/admin/all_products'],
            ['/admin/all_products/2'],
            ['/admin/experts/1/expert'],
        ];
    }
}
