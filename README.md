# TYPO3 Extension ipandlanguageredirect
TYPO3 FE visitor automatic or manual redirect to another language or another root page.

## Introduction

This extension allows **multi-language** and **multi-domain** handling with redirects to best fitting pages with best
fitting language based on the visitors browser language and region (IP-Address).

An AJAX-request (VanillaJS only - no JavaScript framework is needed) will handle the serverside-domain-logic to redirect
or suggest a new webpage or a new language.

Define in your PHP-configuration which countries belongs to which pagetree and which browserlanguage belongs to which
frontend language

### In short words

Automaticly redirect or show a note for the visitor to give him the best fitting website version for his/her needs.

### What's the difference to other extensions like rmpl_language_detect?

There is a basic difference in the concept. While most of the language-redirect extensions hook into the page rendering
process via USER_INT, we choose an ansynchronical way with JavaScript and PHP (AJAX). This solution needs JavaScript on
the one hand but is much faster for high availability and more complex websites on the other hand. This means:
You can use e.g. [staticfilecache](https://github.com/lochmueller/staticfilecache) or another static solution to improve
web performance. While it's not possible to use staticfilecache with a USER_INT, which is included on every single page.

## Screens

Suggest another URI because the current page does not fit:
<img src="https://box.everhelper.me/attachment/646846/84725fb7-0b3e-4c40-b52e-29d7620777bb/262407-wlKVfm63J1ZcviVA/screen.png" />

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
        ],
        [
            // Disable redirect for pages inside the given pid
            'pidInRootline' => [
                '129',
                '11',
            ],
            'events' => [
                'none',
            ],
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

## TypoScript

If you add the static TypoScript, this lines have an effect:

```
plugin.tx_ipandlanguageredirect {
	view {
		templateRootPaths {
			0 = EXT:ipandlanguageredirect/Resources/Private/Templates/
			1 = {$plugin.tx_ipandlanguageredirect.view.templateRootPath}
		}
	}
	settings {
		configuration = {$plugin.tx_ipandlanguageredirect.settings.configuration}
	}
}

page {
	includeJSFooter.ipandlanguageredirect = EXT:ipandlanguageredirect/Resources/Public/JavaScripts/Frontend.min.js
	includeCSS.ipandlanguageredirect = EXT:ipandlanguageredirect/Resources/Public/Css/Frontend.min.css

	# Suggest container that can be slided down
	5 = USER
	5 {
		userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
		extensionName = Ipandlanguageredirect
		pluginName = Pi1
		vendorName = In2code
		controller = Redirect
		action = suggest
		switchableControllerActions.Redirect.1 = suggest
	}

	# Container for informations that will be send to an AJAX service
	1555 = COA
	1555 {
		wrap = <script id="ipandlanguageredirect_container"|></script>

		# Uri to send AJAX request to
		10 = TEXT
		10 {
			noTrimWrap = | data-ipandlanguageredirect-ajaxuri="|"|
			typolink {
				parameter.data = TSFE:id
				additionalParams = &type=1555
				returnLast = url
				forceAbsoluteUrl = 1
			}
		}

		# FE language
		20 = TEXT
		20 {
			noTrimWrap = | data-ipandlanguageredirect-languageuid="|"|
			data = GP:L
			intval = 1
		}

		# Root page uid
		30 = TEXT
		30 {
			noTrimWrap = | data-ipandlanguageredirect-rootpageuid="|"|
			data = leveluid:0
		}

		# Fake browser language for testing - e.g. &tx_ipandlanguageredirect_pi1[browserLanguage]=en
		40 = TEXT
		40 {
			noTrimWrap = | data-ipandlanguageredirect-browserlanguage="|"|
			data = GP:tx_ipandlanguageredirect_pi1|browserLanguage
			htmlSpecialChars = 1
			required = 1
		}

		# Fake ip-address for testing - e.g. &tx_ipandlanguageredirect_pi1[ipAddress]=66.85.131.18 (USA)
		50 = TEXT
		50 {
			noTrimWrap = | data-ipandlanguageredirect-ipaddress="|"|
			data = GP:tx_ipandlanguageredirect_pi1|ipAddress
			htmlSpecialChars = 1
			required = 1
		}

		# Fake country for testing (overlays ip-address) - e.g. &tx_ipandlanguageredirect_pi1[countryCode]=us (USA)
		60 = TEXT
		60 {
			noTrimWrap = | data-ipandlanguageredirect-countrycode="|"|
			data = GP:tx_ipandlanguageredirect_pi1|countryCode
			htmlSpecialChars = 1
			required = 1
		}

		# Fake referrer for testing - e.g. &tx_ipandlanguageredirect_pi1[referrer]=www.google.de
		70 = TEXT
		70 {
			noTrimWrap = | data-ipandlanguageredirect-referrer="|"|
			data = GP:tx_ipandlanguageredirect_pi1|referrer
			htmlSpecialChars = 1
			required = 1
		}
	}
}

# AJAX types
redirectAjax = PAGE
redirectAjax {
	typeNum = 1555
	config {
		additionalHeaders = Content-Type: application/json
		no_cache = 1
		disableAllHeaderCode = 1
		disablePrefixComment = 1
		xhtml_cleaning = 0
		admPanel = 0
		debug = 0
	}

	10  = USER
	10 {
		userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
		extensionName = Ipandlanguageredirect
		pluginName = Pi1
		vendorName = In2code
		controller = Redirect
		action = redirect
		switchableControllerActions.Redirect.1 = redirect
	}
}
testAjax < redirectAjax
testAjax {
	typeNum = 1556
	10.action = test
	10.switchableControllerActions.Redirect.1 = test
}
```

## Testing

### Simulate browserlanguage, country and referrer

```
http://domain.org/index.php?id=1
&tx_ipandlanguageredirect_pi1[ipAddress]=66.85.131.18
&tx_ipandlanguageredirect_pi1[browserLanguage]=de
&tx_ipandlanguageredirect_pi1[referrer]=www.google.de
&tx_ipandlanguageredirect_pi1[countryCode]=af
&no_cache=1
```
Note: Be aware that this settings are cached by default. So you have to always add a &no_cache=1

### Don't redirect or suggest but show the redirectUri in the browserconsole

```
http://domain.org/index.php?id=1
&ipandlanguagedebug=1
```

## FAQ

* Hide Suggest Message
  * Q1: How to disable the suggest message on click on one of my own links?
  * A1: You could add links or other dom elements with attribute **data-ipandlanguageredirect-action="hideMessage"** anywhere
    on your page to disable the suggest message.
    Use case: If the user changes the region or language manually on your site, the message should be dissapear in
    future.
  * Q2: How to disable the suggest message with a GET parameter?
  * A2: The message will be hidden and the cookie to hide it will be set if the GET parameter **&h=1** or **&h=3** is given
* Disable redirect
  * Q1: How to disable the redirect on click on one of my own links?
  * A1: You could add links or other dom elements with attribute **data-ipandlanguageredirect-action="disableRedirect"** anywhere
    on your page to disable redirect.
  * Q2: How to disable the redirect with a GET parameter?
  * A2: The redirect will be disabled by settings a cookie if the GET parameter **&h=2** or **&h=3** is given
* Cookie Livetime
  * The default cookie livetime is temporarely - as long as the browser is open. This is not adjustable at the moment.
* Testing
  * Q1: How can I test another IP-address, region or browserlanguage?
  * A1: See part testing
  * Q2: Where can I see which parameters are send and received via AJAX?
  * A2: Open your browser console and check the post request to ?type=1555 - check the parameters or answers

## Your Contribution

**Pull requests** are welcome in general! Nevertheless please don't forget to add a description to your pull requests. This
is very helpful to understand what kind of issue the **PR** is going to solve.

- Bugfixes: Please describe what kind of bug your fix solve and give me feedback how to reproduce the issue. I'm going
to accept only bugfixes if I can reproduce the issue.
- Features: Not every feature is relevant for the bulk of extension users. Please discuss a new feature before.

## What's not (yet?) possible at the moment

* Make wildcard usage available in browserlanguage (like "en*" for all english browserlanguages)
* Support all browserlanguages and not only the first one
* Support also continents

## Changelog

| Version    | Date       | State      | Description                                                                  |
| ---------- | ---------- | ---------- | ---------------------------------------------------------------------------- |
| 1.7.2      | 2018-01-21 | Task       | Allow ipapi key now without &key=                                            |
| 1.7.1      | 2018-01-16 | Bugfix     | Don't send "null" for an IP-address value if not testvalue is given          |
| 1.7.0      | 2017-09-25 | Feature    | Support paid variant of IpApi.co for more then 1000 visitors a day           |
| 1.6.4      | 2017-08-11 | Bugfix     | Fix header output in TYPO3 8                                                 |
| 1.6.3      | 2017-07-28 | Bugfix     | Disable cHash check for AJAX requests in T3 8                                |
| 1.6.2      | 2017-07-12 | Bugfix     | Fix small typo in composer.json                                              |
| 1.6.1      | 2017-04-12 | Bugfix     | Fix getting browserlanguage from chrome                                      |
| 1.6.0      | 2017-02-04 | Feature    | Redirection should be only once in a session                                 |
| 1.5.3      | 2017-02-01 | Bugfix     | Make code readable for PHP 5.5                                               |
| 1.5.2      | 2017-01-31 | Task       | Remove unused typoscript                                                     |
| 1.5.1      | 2017-01-26 | Bugfix     | Avoid JS exception if state == none                                          |
| 1.5.0      | 2017-01-25 | Feature    | New option to disable functions for a pagetree                               |
| 1.4.1      | 2016-11-15 | Task       | Semantic code cleanup for HTML and CSS                                       |
| 1.4.0      | 2016-11-15 | Feature    | Semantic code cleanup for HTML and CSS                                       |
| 1.3.0      | 2016-11-07 | Feature    | Hide suggest message on GET parameter                                        |
| 1.2.0      | 2016-11-03 | Feature    | Allow hideMessage on multi-links now                                         |
| 1.1.0      | 2016-11-01 | Feature    | Add testing features                                                         |
| 1.0.0      | 2016-11-01 | Initial    | Initial release                                                              |
