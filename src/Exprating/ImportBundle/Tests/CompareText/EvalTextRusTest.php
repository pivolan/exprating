<?php
/**
 * Date: 18.03.16
 * Time: 16:53
 */

namespace Exprating\ImportBundle\Tests\CompareText;


use Exprating\ImportBundle\CompareText\EvalTextRus;

class EvalTextRusTest extends \PHPUnit_Framework_TestCase
{
    public function testEvalTextRus()
    {
        $evalText = new EvalTextRus();
        $string1 = 'this is a teSt compare';
        $string2 = 'this is a teXt compare2';
        $percent = $evalText->evaltextRus($string1, $string2);
        $this->assertEquals(91, (int)$percent);
        $string1 = 'Это русский текст для проверка';
        $string2 = 'Это русский текст проверки для';
        $percent = $evalText->evaltextRus($string1, $string2);
        $this->assertEquals(97, (int)$percent);
        $percent = $evalText->evaltextRus('different', 'equal');
        $this->assertEquals(0, (int)$percent);
        $percent = $evalText->evaltextRus('different', '');
        $this->assertEquals(0, (int)$percent);
        $percent = $evalText->evaltextRus('', 'different');
        $this->assertEquals(0, (int)$percent);
    }
}