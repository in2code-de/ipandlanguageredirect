<?php
return [
    'quantifier' => [
        'browserLanguage' => [
            'totalMatch' => 7,
            'wildCardMatch' => 3
        ],
        'countryBasedOnIp' => [
            'totalMatch' => 13,
            'wildCardMatch' => 5
        ],
        'actions' => [
            'referrers' => [
                'totalMatch' => 7,
                'wildCardMatch' => 3,
            ]
        ]
    ],
    'actions' => [
        [
            'referrers' => [
                'google.',
                'bing.',
                'yahoo.',
                't-online.',
                'yandex.',
                'baidu.'
            ],
            'events' => [
                'redirect'
            ]
        ],
        [
            'referrers' => [
                '*'
            ],
            'events' => [
                'suggest'
            ]
        ],
    ],
    // configuration if nothing matches
    'noMatchingConfiguration' => [
        'identifierUsage' => 'worldwide_english',
        'matchMinQuantifier' => 15
    ],
    // main redirect configuration
    'redirectConfiguration' => [

        // Build URI to page 1 if visitors came from anywhere in the world
        1 => [

            // Build URI to language 0 if browser language is not defined here
            0 => [
                'identifier' => 'worldwide_english',
                'browserLanguage' => [
                    'en'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],

            // Build URI to language 1 if browser language is english "en"
            1 => [
                'identifier' => 'worldwide_german',
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
        ],
    ]
];
