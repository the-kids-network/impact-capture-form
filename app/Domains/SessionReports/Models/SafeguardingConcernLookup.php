<?php

namespace App\Domains\SessionReports\Models;

class SafeguardingConcernLookup {

    public static $values = [
        [
            'id' => 0,
            'value' => false,
            'type' => 'None',
            'label' => 'No'
        ],
        [
            'id' => 1,
            'value' => true,
            'type' => 'Serious',
            'label' => 'Yes - Serious'
        ],
        [
            'id' => 2,
            'value' => true,
            'type' => 'Mild',
            'label' => 'Yes - Mild'
        ]
    ];
}
