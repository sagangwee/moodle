<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

class local_wscreateassign_external extends external_api {

    public static function create_assign_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'name' => new external_value(PARAM_TEXT, 'Assignment name'),
                'intro' => new external_value(PARAM_RAW, 'Assignment introduction'),
            ]
        );
    }

    public static function create_assign($courseid, $name, $intro) {
        global $CFG, $DB;

        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->dirroot . '/mod/assign/locallib.php');

        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

        $assignment = new stdClass();
        $assignment->course = $course->id;
        $assignment->name = $name;
        $assignment->intro = $intro;
        $assignment->introformat = FORMAT_HTML;
        $assignment->assignsubmission_onlinetext_enabled = 1;
        $assignment->assignsubmission_file_enabled = 1;
        $assignment->grade = 100;
        $assignment->timemodified = time();
        $assignment->timeavailable = time();
        $assignment->timedue = time() + 7 * 24 * 3600;

        $coursemoduleid = assign_add_instance($assignment, null);

        $assignmentinstance = $DB->get_record('assign', ['id' => $coursemoduleid->instance]);

        return ['assignmentid' => $assignmentinstance->id, 'coursemoduleid' => $coursemoduleid->id];
    }

    public static function create_assign_returns() {
        return new external_single_structure(
            [
                'assignmentid' => new external_value(PARAM_INT, 'New assignment ID'),
                'coursemoduleid' => new external_value(PARAM_INT, 'New course module ID'),
            ]
        );
    }
}
