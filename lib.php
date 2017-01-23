<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants for module youtubedrive
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the youtubedrive specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_youtubedrive
 * @copyright  2916 Eduardo Kraus ME
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function youtubedrive_supports($feature) {

    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:             return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_MOD_INTRO:                 return true;
        case FEATURE_SHOW_DESCRIPTION:          return true;
        case FEATURE_GRADE_HAS_GRADE:           return false;
        case FEATURE_BACKUP_MOODLE2:            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:   return true;
        default:                                return null;
    }
}

/**
 * Saves a new instance of the youtubedrive into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $youtubedrive Submitted data from the form in mod_form.php
 * @param mod_youtubedrive_mod_form $mform The form instance itself (if needed)
 * @return int The id of the newly inserted youtubedrive record
 */
function youtubedrive_add_instance(stdClass $youtubedrive, mod_youtubedrive_mod_form $mform = null) {
    global $DB;

    $youtubedrive->timecreated = time();

    $youtubedrive->id = $DB->insert_record('youtubedrive', $youtubedrive);

    return $youtubedrive->id;
}

/**
 * Updates an instance of the youtubedrive in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $youtubedrive An object from the form in mod_form.php
 * @param mod_youtubedrive_mod_form $mform The form instance itself (if needed)
 * @return boolean Success/Fail
 */
function youtubedrive_update_instance(stdClass $youtubedrive, mod_youtubedrive_mod_form $mform = null) {
    global $DB;

    $youtubedrive->timemodified = time();
    $youtubedrive->id = $youtubedrive->instance;

    $result = $DB->update_record('youtubedrive', $youtubedrive);

    return $result;
}

/**
 * Removes an instance of the youtubedrive from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function youtubedrive_delete_instance($id) {
    global $DB;

    if (! $youtubedrive = $DB->get_record('youtubedrive', array('id' => $id))) {

        echo ('aaaa');
        return false;
    }

    $DB->delete_records('youtubedrive', array('id' => $youtubedrive->id));

    echo ('bbbb');
    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 *
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param cm_info|stdClass $mod The course module info object or record
 * @param stdClass $youtubedrive The youtubedrive instance record
 * @return stdClass|null
 */
function youtubedrive_user_outline($course, $user, $mod, $youtubedrive) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * It is supposed to echo directly without returning a value.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $youtubedrive the module instance record
 */
function youtubedrive_user_complete($course, $user, $mod, $youtubedrive) {
    global $CFG, $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'youtubedrive',
                                              'action'=>'view', 'info'=>$youtubedrive->id), 'time ASC')) {
        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $strmostrecently = get_string('mostrecently');
        $strnumviews = get_string('numviews', '', $numviews);

        echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

    } else {
        print_string('neverseen', 'youtubedrive');
    }
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 *
 * See {@link get_array_of_activities()} in course/lib.php
 *
 * @param stdClass $coursemodule
 * @return cached_cm_info Info to customise main youtubedrive display
 */
function youtubedrive_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;

    if (!$youtubedrive = $DB->get_record('youtubedrive', array('id'=>$coursemodule->instance),
        'id, name, youtubedriveurl, intro, introformat')) {
        return NULL;
    }

    $info = new cached_cm_info();
    $info->name = $youtubedrive->name;

    if ( $coursemodule->showdescription )
        $info->content = format_module_intro ( 'youtubedrive', $youtubedrive, $coursemodule->id, false );

    return $info;
}

