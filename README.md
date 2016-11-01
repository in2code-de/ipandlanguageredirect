# TYPO3 Extension ipandlanguageredirect
TYPO3 FE visitor automatic or manual redirect to another language or another root page.

## Introduction

This extension allows **multi-language** and **multi-domain** handling with redirects to best fitting pages with best
fitting language based on the visitors browser language and region (IP-Address).

An AJAX-request will handle the serverside-domain-logic to redirect or suggest a new webpage or a new language.

Define in your PHP-configuration which countries belongs to which pagetree and which browserlanguage belongs to which
frontend language

**Note:** At the moment this extension is in a very early alpha status.

## Screens

Suggest another URI because the current page does not fit (sorry for the technical view - a nicer view will follow):
<img src="https://box.everhelper.me/attachment/629316/84725fb7-0b3e-4c40-b52e-29d7620777bb/262407-tgYIf9Rx25uX0xFz/screen.png" />

## Installation

* Install this extension
* Add a static template (page.5 will be used for the suggest container, page.1555 for the script)
* Copy the original configuration from ipandlanguageredirect/Configuration/Redirect/Redirect.php to any other location
* Modify the configuration for your needs
* Set the new path in the extension manager to your configuration file
* Have fun!

## Example configuration

```
<?php
return [
    // Quantifiers for the matches (shouldn't be touched)
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
    // Example action
    'actions' => [
        [
            // Automatic redirect to best fitting uri if visitor comes from a search engine
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
            // For all other cases: Suggest a better fitting page
            'referrers' => [
                '*'
            ],
            'events' => [
                'suggest'
            ]
        ]
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
                    '*'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],

            // Build URI to language 1 if browser language is german "de"
            1 => [
                'identifier' => 'worldwide_german',
                'browserLanguage' => [
                    'de'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],

            // Build URI to language 2 if browser language is chinese "cn"
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

        // Build URI to page 2 if visitors came from USA, Canada or Argentina
        2 => [

            // Build URI to language 0 if browser language is not defined here
            0 => [
                'identifier' => 'america_english',
                'browserLanguage' => [
                    '*'
                ],
                'countryBasedOnIp' => [
                    'ca',
                    'us',
                    'ar',
                ]
            ],

            // Build URI to language 1 if browser language is german "de"
            1 => [
                'identifier' => 'worldwide_german',
                'browserLanguage' => [
                    'de'
                ],
                'countryBasedOnIp' => [
                    '*'
                ]
            ],
        ],
    ]
];
```

## Testing

### Simulate browserlanguage, country and referrer

```
http://domain.org/index.php?id=1
&tx_ipandlanguageredirect_pi1[ipAddress]=66.85.131.18
&tx_ipandlanguageredirect_pi1[browserLanguage]=de
&tx_ipandlanguageredirect_pi1[referrer]=www.google.de
&tx_ipandlanguageredirect_pi1[countryCode]=af
```

### Don't redirect or suggest but show the redirectUri in the browserconsole

```
http://domain.org/index.php?id=1
&ipandlanguagedebug=1
```

## Todos

* Add some more documentation with FAQ and best practice
