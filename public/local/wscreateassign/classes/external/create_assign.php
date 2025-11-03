<?php
namespace local_wscreateassign\external;

use core\context;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

class create_assign extends external_api {

    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'name' => new external_value(PARAM_TEXT, 'Assignment name'),
                'intro' => new external_value(PARAM_RAW, 'Assignment introduction'),
            ]
        );
    }

    public static function execute($courseid, $name, $intro) {
        global $CFG, $DB;
        require_once("$CFG->dirroot/mod/assign/lib.php");
        self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid, 'name' => $name, 'intro' => $intro]);

        $context = context\course::instance($courseid, IGNORE_MISSING);
        self::validate_context($context);

        $assignment = new \stdClass();
        $assignment->course = $courseid;
        $assignment->coursemodule = $courseid;
        $assignment->name = $name;
        $assignment->intro = $intro;
        $assignment->introformat = FORMAT_HTML;
        $assignment->assignsubmission_onlinetext_enabled = 1;
        $assignment->assignsubmission_file_enabled = 1;
        $assignment->grade = 100;
        $assignment->timemodified = time();
        $assignment->timeavailable = time();
        $assignment->timedue = time() + 7 * 24 * 3600;

        $assignment_id = assign_add_instance($assignment, null);

        // $assignmentinstance = $DB->get_record('assign', ['id' => $coursemodule->instance]);

        return $assignment_id;
    }

    public static function execute_returns() {
        return new external_value(PARAM_INT, 'New assignment ID');
    }
}
