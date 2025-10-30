<?php
namespace local_wscreateassign\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

use core\context_course;

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
        // global $CFG, $DB;

        // self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid, 'name' => $name, 'intro' => $intro]);

        // $context = \core\context_course::instance($courseid);
        // self::validate_context($context);

        // require_once($CFG->dirroot . '/course/lib.php');
        // require_once($CFG->dirroot . '/mod/assign/locallib.php');

        // $assignment = new \stdClass();
        // $assignment->course = $courseid;
        // $assignment->name = $name;
        // $assignment->intro = $intro;
        // $assignment->introformat = FORMAT_HTML;
        // $assignment->assignsubmission_onlinetext_enabled = 1;
        // $assignment->assignsubmission_file_enabled = 1;
        // $assignment->grade = 100;
        // $assignment->timemodified = time();
        // $assignment->timeavailable = time();
        // $assignment->timedue = time() + 7 * 24 * 3600;

        // $coursemodule = assign_add_instance($assignment, null);

        // $assignmentinstance = $DB->get_record('assign', ['id' => $coursemodule->instance]);

        // return ['assignmentid' => $assignmentinstance->id, 'coursemoduleid' => $coursemodule->id];
        return ['assignmentid' => 0, 'coursemoduleid' => 42];
    }

    public static function execute_returns() {
        return new external_single_structure(
            [
                'assignmentid' => new external_value(PARAM_INT, 'New assignment ID'),
                'coursemoduleid' => new external_value(PARAM_INT, 'New course module ID'),
            ]
        );
    }
}
