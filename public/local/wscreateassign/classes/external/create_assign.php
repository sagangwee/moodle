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
                'restrictedstudentemails' => new external_multiple_structure(
                    new external_value(PARAM_EMAIL, 'Student email'),
                    'List of student emails to restrict access to',
                    VALUE_DEFAULT,
                    []
                ),
                'sectionnumber' => new external_value(PARAM_INT, 'Section number within the course', VALUE_DEFAULT, 0),
            ]
        );
    }

    public static function execute($courseid, $name, $intro, $restrictedstudentemails = [], $sectionnumber = 0) {
        global $CFG;
        require_once($CFG->dirroot . '/course/modlib.php');
        
        self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid, 'name' => $name, 'intro' => $intro, 'restrictedstudentemails' => $restrictedstudentemails, 'sectionnumber' => $sectionnumber]);

        $context = context\course::instance($courseid, IGNORE_MISSING);
        self::validate_context($context);

        $course = get_course($courseid);
        
        // Ensure section is an integer and non-negative
        $sectionnumber = (int)$sectionnumber;
        if ($sectionnumber < 0) {
            $sectionnumber = 0;
        }

        // Prepare defaults for a new assign module in the requested section
        list($module, $context, $cw, $cm, $moduleinfo) = prepare_new_moduleinfo_data($course, 'assign', $sectionnumber);

        // Fill moduleinfo fields
        $moduleinfo->modulename = 'assign';
        $moduleinfo->name = $name;
        $moduleinfo->introeditor = ['text' => $intro, 'format' => FORMAT_HTML, 'itemid' => file_get_submitted_draft_itemid('introeditor')];
        $moduleinfo->visible = 1;
        $moduleinfo->section = $sectionnumber;
        
        // Add default assignment settings
        $moduleinfo->assignsubmission_onlinetext_enabled = 1;
        $moduleinfo->assignsubmission_file_enabled = 1;
        $moduleinfo->grade = 100;
        
        // Date settings
        $now = time();
        $threemonths = 90 * 24 * 3600; // 90 days
        $moduleinfo->allowsubmissionsfromdate = $now;
        $moduleinfo->duedate = $now + $threemonths; // 3 months from now
        $moduleinfo->cutoffdate = $now + ($threemonths + (7 * 24 * 3600)); // 1 week after due date
        $moduleinfo->gradingduedate = $now + ($threemonths + (14 * 24 * 3600)); // 2 weeks after due date
        
        // Required settings that can't be NULL
        $moduleinfo->submissiondrafts = 0;
        $moduleinfo->requiresubmissionstatement = 0;
        $moduleinfo->sendnotifications = 0;
        $moduleinfo->sendlatenotifications = 0;
        $moduleinfo->sendstudentnotifications = 1;
        $moduleinfo->teamsubmission = 0;
        $moduleinfo->requireallteammemberssubmit = 0;
        $moduleinfo->blindmarking = 0;
        $moduleinfo->markingworkflow = 0;
        $moduleinfo->markingallocation = 0;

        // Set up availability restriction by email if provided
        if (!empty($restrictedstudentemails)) {
            $conditions = [];
            foreach ($restrictedstudentemails as $email) {
                $conditions[] = [
                    'type' => 'profile',
                    'sf' => 'email',
                    'op' => 'contains',
                    'v' => $email
                ];
            }
            
            $moduleinfo->availability = json_encode([
                'op' => '|',  // OR operator to match any of the emails
                'c' => $conditions,
                'show' => true
            ]);
        }
        // Create the module and instance
        $moduleinfo = add_moduleinfo($moduleinfo, $course);
        
        return $moduleinfo->instance;
    }

    public static function execute_returns() {
        return new external_value(PARAM_INT, 'New assignment ID');
    }
}
