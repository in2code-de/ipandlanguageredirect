<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'ipandlanguageredirect',
    'description' => 'TYPO3 extension to redirect a user based on location and browser language.',
    'category' => 'plugin',
    'version' => '5.2.2',
    'state' => 'stable',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.8-12.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
