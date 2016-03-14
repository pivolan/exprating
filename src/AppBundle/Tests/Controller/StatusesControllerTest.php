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
     * @param $urls
     *
     * @dataProvider getAnonUrls
     */
    public function testStatus200($urls)
    {
        $client = static::createClient();
        $client->request('GET', $urls[0]);

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
}