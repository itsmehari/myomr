<?php
/**
 * Static data for OMR/vicinity assembly constituencies – 2021 results, areas, AC numbers.
 * Used by constituency/*.php pages.
 */
if (!defined('ELECTIONS_2026_AC_DATA')) {
    define('ELECTIONS_2026_AC_DATA', true);
}

$elections_2026_constituencies = [
    'sholinganallur' => [
        'name' => 'Sholinganallur',
        'ac_no' => 27,
        'ls_constituency' => 'Chennai South',
        'district' => 'Chennai',
        'areas' => ['Sholinganallur', 'Thoraipakkam', 'Perumbakkam', 'Navalur', 'Karapakkam', 'Kandanchavadi', 'Okkiyam Thuraipakkam'],
        'polling_stations_2021' => 279,
        '2021' => [
            'winner' => 'S. Aravind Ramesh',
            'winner_party' => 'DMK',
            'winner_votes' => 168361,
            'runner_up' => 'K.P. Kandhan',
            'runner_party' => 'AIADMK',
            'runner_votes' => 133600,
            'margin' => 34761,
        ],
        'has_blo_search' => true,
    ],
    'velachery' => [
        'name' => 'Velachery',
        'ac_no' => 26,
        'ls_constituency' => 'Chennai South',
        'district' => 'Chennai',
        'areas' => ['Velachery', 'Perungudi', 'Taramani', 'Adambakkam'],
        'polling_stations_2021' => null,
        '2021' => [
            'winner' => 'J.M.H. Aassan Maulaana',
            'winner_party' => 'INC',
            'winner_votes' => 68493,
            'runner_up' => 'M.K. Ashok',
            'runner_party' => 'AIADMK',
            'runner_votes' => 64141,
            'margin' => 4352,
        ],
        'has_blo_search' => false,
    ],
    'thiruporur' => [
        'name' => 'Thiruporur',
        'ac_no' => null,
        'ls_constituency' => null,
        'district' => 'Chengalpattu',
        'areas' => ['Thiruporur', 'Kelambakkam'],
        'polling_stations_2021' => null,
        '2021' => [
            'winner' => 'S.S. Balaji',
            'winner_party' => 'VCK',
            'winner_votes' => 92987,
            'runner_up' => 'Arumugam',
            'runner_party' => 'PMK',
            'runner_votes' => 91388,
            'margin' => 1599,
        ],
        'has_blo_search' => false,
    ],
];
