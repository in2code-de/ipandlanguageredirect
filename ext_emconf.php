<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'ipandlanguageredirect',
    'description' => 'TYPO3 extension to redirect a user based on location and browser language.',
    'category' => 'plugin',
    'version' => '13.0.0',
    'state' => 'stable',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
