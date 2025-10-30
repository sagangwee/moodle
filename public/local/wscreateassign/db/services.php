<?php
// defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_wscreateassign_create_assign' => [
        'classname' => 'local_wscreateassign\external\create_assign',
        'methodname' => 'execute',
        'description' => 'Create a new assignment.',
        'type' => 'write',
        'capabilities' => 'moodle/course:manageactivities',
        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            MOODLE_OFFICIAL_MOBILE_SERVICE,
        ]
    ],
];

// $services = [
//     'wscreateassign' => [
//         'functions' => ['local_wscreateassign_create_assign'],
//         'restrictedusers' => 0,
//         'enabled' => 1,
//     ],
// ];
