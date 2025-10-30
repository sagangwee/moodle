<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_wscreateassign_create_assign' => [
        'classname' => 'local_wscreateassign_external',
        'methodname' => 'create_assign',
        'classpath' => 'local/wscreateassign/externallib.php',
        'description' => 'Create a new assignment.',
        'type' => 'write',
        'capabilities' => 'moodle/course:manageactivities',
    ],
];

$services = [
    'wscreateassign' => [
        'functions' => ['local_wscreateassign_create_assign'],
        'restrictedusers' => 0,
        'enabled' => 1,
    ],
];
