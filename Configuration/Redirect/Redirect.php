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
        ]
    ],
    'noMatchingConfiguration' => [

    ],
    'redirectConfiguration' => [
        // pid
        // PCO AG (worldwide, europe)
        1 => [
            // language parameter
            0 => [
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
            1 => [
                'browserLanguage' => [
                    'de'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
            2 => [
                'browserLanguage' => [
                    'cn'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
        ],
        // Canada
        2 => [
            0 => [
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    'ca'
                ]
            ]
        ],
        // USA, South America
        3 => [
            0 => [
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
        4 => [
            0 => [
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
        5 => [
            0 => [
                'browserLanguage' => [
                    '*',
                ],
                'countryBasedOnIp' => [
                    'cn'
                ]
            ],
            2 => [
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
