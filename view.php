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
 * Prints a particular instance of youtubedrive
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_youtubedrive
 * @copyright  2916 Eduardo Kraus ME
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace youtubedrive with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... youtubedrive instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('youtubedrive', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $youtubedrive    = $DB->get_record('youtubedrive', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $youtubedrive    = $DB->get_record('youtubedrive', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $youtubedrive->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('youtubedrive', $youtubedrive->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/youtubedrive:view', $context);

$event = \mod_youtubedrive\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $youtubedrive);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/youtubedrive/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($youtubedrive->name));
$PAGE->set_heading(format_string($course->fullname));

// Update 'viewed' state if required by completion system
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$config = get_config('youtubedrive');

$PAGE->set_url('/mod/youtubedrive/view.php', array('id' => $cm->id));
$PAGE->requires->js('/mod/youtubedrive/js/util.js', true);
$PAGE->requires->css('/mod/youtubedrive/pix/youtubedrive.css');
$PAGE->set_title($course->shortname.': '.$youtubedrive->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_activity_record($youtubedrive);
echo $OUTPUT->header();

echo $OUTPUT->heading(format_string($youtubedrive->name), 2, 'main', 'youtubedriveheading');

//  rel=1
if( $youtubedrive->showrel )
    $urlParameters = 'rel=1';
else
    $urlParameters = 'rel=0';
$urlParameters .= '&amp;';

//  controls=1
if( $youtubedrive->showcontrols )
    $urlParameters .= 'controls=1';
else
    $urlParameters .= 'controls=0';
$urlParameters .= '&amp;';

//  showinfo=1
if( $youtubedrive->showshowinfo )
    $urlParameters .= 'showinfo=1';
else
    $urlParameters .= 'showinfo=0';
$urlParameters .= '&amp;';

//  autoplay=1
if( $youtubedrive->autoplay )
    $urlParameters .= 'autoplay=1';
else
    $urlParameters .= 'autoplay=0';
$urlParameters .= '&amp;';


preg_match ( "/(.*?)\/view/", $youtubedrive->youtubedriveurl, $output_array );
$urlDrive = $output_array[ 1 ];

echo '<div id="youtubedriveworkaround">';


echo '<div class="logo">
          <img src="http://paulacassim.com.br/wp-content/themes/paulacassimHD2016/imagens/logo.png" class="logo">
      </div>';

if ( $youtubedrive->youtubedrivesize == 0 )
    $size = 'width="100%" height="240"';
if ( $youtubedrive->youtubedrivesize == 1 )
    $size = 'width="100%" height="480"';
if ( $youtubedrive->youtubedrivesize == 2 )
    $size = 'width="100%" height="450"';

echo '<iframe id="videohd2" ' . $size . ' src="' . $urlDrive . '/preview' . '" frameborder="0" allowfullscreen></iframe>';

echo '</div>';
echo $OUTPUT->footer();