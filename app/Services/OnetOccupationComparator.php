<?php

namespace App\Services;

use App\Contracts\OccupationComparator;

class OnetOccupationComparator implements OccupationComparator
{
    public function compare($scope, $list_1, $list_2)
    {
        if(in_array($scope, ['skills', 'knowledge', 'abilities'])){
            // Compare lists accounting for importance

            $list_1_keys = array_keys($list_1);
            $list_2_keys = array_keys($list_2);
            $list_keys = array_unique(array_merge($list_1_keys, $list_2_keys));

            $list = [];
            $total_score = 0;
            foreach($list_keys as $list_key){
                if(isset($list_1[$list_key]) && isset($list_2[$list_key])){
                    // Item is shared by both occupations so calculate a score from 0-100 based on the difference of it's importance
                    $skill_score = 100 - abs($list_1[$list_key]['value'] - $list_2[$list_key]['value']);
                    $total_score += $skill_score;
                    $list[] = [
                        'label' => $list_1[$list_key]['label'],
                        'values' => [
                            $list_1[$list_key]['value'],
                            $list_2[$list_key]['value'],
                        ],
                        'score' => $skill_score,
                        'description' => $list_1[$list_key]['description']
                    ];
                }else{
                    // Item only applicable to one occupation so assign a score of 0
                    $list[] = [
                        'label' => isset($list_1[$list_key]['label']) ? $list_1[$list_key]['label'] : $list_2[$list_key]['label'],
                        'values' => [
                            isset($list_1[$list_key]['value']) ? $list_1[$list_key]['value'] : 0,
                            isset($list_2[$list_key]['value']) ? $list_2[$list_key]['value'] : 0,
                        ],
                        'score' => 0,
                        'description' => isset($list_1[$list_key]['description']) ? $list_1[$list_key]['label'] : $list_2[$list_key]['description'],
                    ];
                }
            }

            $similarity = round($total_score / count($list_keys));

            return [
                'list' => $list,
                'similarity' => $similarity
            ];
        }else if(in_array($scope, ['tools'])){
            // Compare based on some other logic
        }

        return false;
    }
}