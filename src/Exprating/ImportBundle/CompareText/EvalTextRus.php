<?php
/**
 * Date: 27.02.16
 * Time: 11:38
 * Импортированный функционал с одного из сайтов. Проверен не полностью. Рефакторинг не делал.
 */

namespace Exprating\ImportBundle\CompareText;


final class EvalTextRus
{
    function preprocessRus($lim_len_symb, $text, &$arrt, &$count_t)
    {
        $text2 = mb_strtolower($text);
        $strm = preg_replace('/[^а-я]/u', " ", $text2);            ## print "<hr>".$strm."<hr>";  //ok
        $arrt = [];
        $_wordst = preg_split("/ +/u", $strm);
        foreach ($_wordst as $ws) {
            $lengws = mb_strlen($ws);                     ##-- print "ws=$ws*  \$lengws=$lengws<br>";  //Ok
            if (($ws != "") && ($lengws > $lim_len_symb)) {
                $arrt[] = $ws;      //////////print "--------------------ws=$ws*<br>";
            }#(lengws_res)
        }
        $count_t = count($arrt);
    }//end func

    function evaltextRus($limit_symbols = 2, $str1, $str2, &$out, $_delta_lett = 3, $_prob75 = 0.702)
    {
        $this->preprocessRus($limit_symbols, $str1, $arr1, $count1);
        $this->preprocessRus($limit_symbols, $str2, $arr2, $count2);
        $eq = 0;
        foreach ($arr1 as $ws1) {
            foreach ($arr2 as $ws2) {
                $lengws1 = mb_strlen($ws1);
                $lengws2 = mb_strlen($ws2);
                $deltalenws = abs($lengws1 - $lengws2);
                if (preg_match("/$ws1/u", $ws2) || preg_match("/$ws2/u", $ws1)) {
                    if ($deltalenws <= $_delta_lett) {
                        ## print "совпадение: $ws1 $ws2    <b>$deltalenws</b><br>";
                        $eq++;
                    }
                } else {
                    $ab1 = [];
                    $ab2 = [];
                    $ab1 = preg_split("//u", $ws1);
                    $ab2 = preg_split("//u", $ws2);
                    $eqlet = 0;
                    $countab1 = count($ab1) - 2;
                    $countab2 = count($ab2) - 2;
                    if ($lengws1 > $lengws2) {
                        $eqdelete = $lengws1;
                    } else {
                        $eqdelete = $lengws2;
                    }
                    for ($j = 1; $j <= $countab1 && $j <= $countab2; $j++) {  ///////////////////////print $ab1[$j]." = ".$ab2[$j]."<br>";
                        if ($ab1[$j] != "" || $ab2[$j] != "") {
                            if ($ab1[$j] == $ab2[$j]) {
                                $eqlet++;
                            }  ///  print $ab1[$j]." = ".$ab2[$j]."<br>";
                        }
                    }#for(j=1)
                    $reseq = $eqlet / $eqdelete;   //$countab1;
                    if ($reseq > $_prob75) {
                        $eq += $reseq;
                        ##  print "~~~~~~несовпадение-OK:~~~$ws1~~~$ws2~~~\$eqdelete=$eqdelete($lengws1,$lengws2)~~~\$eqlet=$eqlet~~~\$reseq=$reseq<br>";
                    }
                    ///print "---несовпадение:--$ws1---$ws2---\$eqdelete=$eqdelete ($lengws1, $lengws2)----\$eqlet=$eqlet---\$reseq=$reseq<br>";
                }//else
            }
        }##for(for)
        $sumcount = ($count2 + $count1) / 2;
        $per = (100 * $eq) / $sumcount;
        $per = sprintf("%5.2f", $per);
        $out["limit"] = $limit_symbols;
        $out["count1"] = $count1;
        $out["count2"] = $count2;
        $out["avercount"] = $sumcount;
        return $per;
    }//end func
}