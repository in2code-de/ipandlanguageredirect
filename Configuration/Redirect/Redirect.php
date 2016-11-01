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
                'baidu.',
                'links.playground.localhost.de',
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
        ]
    ],
    'noMatchingConfiguration' => [
        'identifierUsage' => 'worldwide_english',
        'matchMinQuantifier' => 15
    ],
    'redirectConfiguration' => [
        // pid
        // PCO AG (worldwide, europe)
        1 => [
            // language parameter
            0 => [
                'identifier' => 'worldwide_english',
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
            1 => [
                'identifier' => 'worldwide_german',
                'browserLanguage' => [
                    'de'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
            2 => [
                'identifier' => 'worldwide_chinese',
                'browserLanguage' => [
                    'cn'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
        ],
        // Canada
        16 => [
            0 => [
                'identifier' => 'canada_english',
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    'ca'
                ]
            ]
        ],
        // USA, South America
        22 => [
            0 => [
                'identifier' => 'america_english',
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    'us',
                    'ar',
                    'bo',
                    'br',
                    'cl',
                    'co',
                    'ec',
                    'fk',
                    'gf',
                    'gy',
                    'pe',
                    'py',
                    'sr',
                    'uy',
                    've'
                ]
            ]
        ],
        // Asia (without china), Australia, New Zealand
        26 => [
            0 => [
                'identifier' => 'asia_english',
                'browserLanguage' => [
                    '*',
                ],
                'countryBasedOnIp' => [
                    'ae',
                    'af',
                    'am',
                    'az',
                    'bd',
                    'bh',
                    'bn',
                    'bt',
                    'cy',
                    'ge',
                    'hk',
                    'id',
                    'il',
                    'in',
                    'io',
                    'iq',
                    'ir',
                    'jo',
                    'jp',
                    'kg',
                    'kh',
                    'kp',
                    'kr',
                    'kw',
                    'kz',
                    'la',
                    'lb',
                    'lk',
                    'mm',
                    'mn',
                    'mo',
                    'mv',
                    'my',
                    'np',
                    'om',
                    'ph',
                    'pk',
                    'qa',
                    'sa',
                    'sg',
                    'sy',
                    'th',
                    'tj',
                    'tm',
                    'tl',
                    'tr',
                    'tw',
                    'uz',
                    'vn',
                    'ye',
                    'ps',
                    'au',
                    'nz'
                ]
            ],
            2 => [
                'identifier' => 'asia_chinese',
                'browserLanguage' => [
                    'cn',
                ],
                'countryBasedOnIp' => [
                    'ae',
                    'af',
                    'am',
                    'az',
                    'bd',
                    'bh',
                    'bn',
                    'bt',
                    'cy',
                    'ge',
                    'hk',
                    'id',
                    'il',
                    'in',
                    'io',
                    'iq',
                    'ir',
                    'jo',
                    'jp',
                    'kg',
                    'kh',
                    'kp',
                    'kr',
                    'kw',
                    'kz',
                    'la',
                    'lb',
                    'lk',
                    'mm',
                    'mn',
                    'mo',
                    'mv',
                    'my',
                    'np',
                    'om',
                    'ph',
                    'pk',
                    'qa',
                    'sa',
                    'sg',
                    'sy',
                    'th',
                    'tj',
                    'tm',
                    'tl',
                    'tr',
                    'tw',
                    'uz',
                    'vn',
                    'ye',
                    'ps',
                    'au',
                    'nz'
                ]
            ]
        ],
        // China
        30 => [
            0 => [
                'identifier' => 'china_english',
                'browserLanguage' => [
                    '*',
                ],
                'countryBasedOnIp' => [
                    'cn'
                ]
            ],
            2 => [
                'identifier' => 'china_chinese',
                'browserLanguage' => [
                    'cn',
                ],
                'countryBasedOnIp' => [
                    'cn'
                ]
            ]
        ]
    ]
];
