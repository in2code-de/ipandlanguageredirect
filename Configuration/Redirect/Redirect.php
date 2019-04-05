<?php
return [
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
    'globalConfiguration' => [
        // show suggest message or do a redirect only if the user visits home (not for subpages)
        'actionOnHomeOnly' => true,

        // don't redirect to the home-page, try to stay on the same page while changing the language
        'stayOnCurrentPage' => true
    ],
    'noMatchingConfiguration' => [
        'identifierUsage' => 'worldwide_english',
        'matchMinQuantifier' => 15
    ],
    'redirectConfiguration' => [
        // this number means: pid
        // Worldwide, Europe
        1 => [
            // this number means: language parameter
            0 => [
                'identifier' => 'worldwide_english',
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
            1 => [
                'identifier' => 'worldwide_german',
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
                'browserLanguage' => [
                    'de'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
            2 => [
                'identifier' => 'worldwide_chinese',
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
                'browserLanguage' => [
                    'zh'
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
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
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
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
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
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
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
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
                'browserLanguage' => [
                    'zh',
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
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
                'browserLanguage' => [
                    '*',
                ],
                'countryBasedOnIp' => [
                    'cn'
                ]
            ],
            2 => [
                'identifier' => 'china_chinese',
                'domain' => [
                    'www.domain.org',
                    'local.domain.org'
                ],
                'browserLanguage' => [
                    'zh',
                ],
                'countryBasedOnIp' => [
                    'cn'
                ]
            ]
        ]
    ],
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
    ]
];
