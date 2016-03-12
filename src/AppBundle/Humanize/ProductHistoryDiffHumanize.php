<?php
/**
 * Date: 12.03.16
 * Time: 18:56
 */

namespace AppBundle\Humanize;


use AppBundle\Entity\ProductEditHistory;

class ProductHistoryDiffHumanize
{
    protected $dictionary = [
        'rating1'       => 'Оценка',
        'rating2'       => 'Оценка',
        'rating3'       => 'Оценка',
        'rating4'       => 'Оценка',
        'advantages'    => 'Достоинства',
        'disadvantages' => 'Недостатки',
        'expertOpinion' => 'Заключение',
        'expertComment' => 'Комментарий эксперта',
        'previewImage'  => 'Главная картинка',
    ];

    public function humanize(ProductEditHistory $productEditHistory)
    {
        /** @var array $diff */
        $diff = $productEditHistory->getDiff();

        $maxDiffKey = '';
        $prevDelta = 0;
        foreach ($diff as $key => $value) {
            if (isset($value[0], $value[1])) {
                $oldValueSerialized = print_r($value[0], true);
                $newValueSerialized = print_r($value[1], true);
                $delta = levenshtein($oldValueSerialized, $newValueSerialized);
                if ($delta > $prevDelta) {
                    $prevDelta = $delta;
                    $maxDiffKey = $key;
                }
            }
        }
        if (!$maxDiffKey) {
            return 'Изменений не было';
        }
        $humanizeKey = isset($this->dictionary[$maxDiffKey]) ? $this->dictionary[$maxDiffKey] : $maxDiffKey;

        return 'Изменение поля '.$humanizeKey;
    }
}
