<?php
/**
 * Date: 29.03.16
 * Time: 15:41
 */

namespace Exprating\ImportBundle\Parser;


use PHPHtmlParser\Dom;

class OtzyvProParser
{
    public function __construct()
    {
    }

    public function parse($html)
    {
        $dom = new Dom();
        $dom->load($html);
        $categories = [];
        foreach ($dom->find('.blockboxcat li.list') as $key => $list) {
            /** @var Dom $list */
            /** @var Dom\HtmlNode $anchor */
            $anchor = $list->find('a.href')[0];
            $categories[$key] = ['href' => $anchor->href, 'name' => $anchor->text, 'children' => []];
            $categoryDom = new Dom();
            $categoryDom->loadFromUrl('http://otzyv.pro'.$anchor->href);
            foreach ($categoryDom->find('div.catmenu') as $keyChild => $child) {
                /** @var Dom $child */
                /** @var Dom $childNode */
                $childNode = $child->find('div.tit_menu > a.list')[0];
                if ($childNode) {
                    $categories[$key]['children'][$keyChild] = [
                        'href'     => $childNode->href,
                        'name'     => $childNode->text,
                        'children' => [],
                    ];
                    foreach ($child->find('ol.submenu a.list') as $subChild) {
                        $categories[$key]['children'][$keyChild]['children'][] = [
                            'href' => $subChild->href,
                            'name' => $subChild->text,
                        ];
                    }
                }
            }
        }

        return $categories;
    }
}