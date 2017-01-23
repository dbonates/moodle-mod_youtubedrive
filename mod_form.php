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
 * The main youtubedrive configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_youtubedrive
 * @copyright  2916 Eduardo Kraus ME
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined ( 'MOODLE_INTERNAL' ) || die();

require_once ( $CFG->dirroot . '/course/moodleform_mod.php' );

/**
 * Module instance settings form
 *
 * @package    mod_youtubedrive
 * @copyright  2916 Eduardo Kraus ME
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_youtubedrive_mod_form extends moodleform_mod
{

    /**
     * Defines forms elements
     */
    public function definition ()
    {
        global $CFG, $PAGE;

        $config = get_config ( 'youtubedrive' );

        $mform = $this->_form;

        $PAGE->requires->js ( '/mod/youtubedrive/mod_form.js' );

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement ( 'header', 'general', get_string ( 'general', 'form' ) );

        $mform->addElement ( 'text', 'name', get_string ( 'name' ), array ( 'size' => '48' ), array () );
        $mform->setType ( 'name', PARAM_TEXT );
        $mform->addRule ( 'name', null, 'required', null, 'client' );
        $mform->addRule ( 'name', get_string ( 'maximumchars', '', 255 ), 'maxlength', 255, 'client' );

        $mform->addElement ( 'text', 'youtubedriveurl', get_string ( 'youtubedriveurl', 'youtubedrive' ), array ( 'size' => '60' ), array ( 'usefilepicker' => true ) );
        $mform->setType ( 'youtubedriveurl', PARAM_TEXT );
        $mform->addRule ( 'youtubedriveurl', null, 'required', null, 'client' );


        // Adding the standard "intro" and "introformat" fields.
        if ( $CFG->branch >= 29 )
            $this->standard_intro_elements ();
        else
            $this->add_intro_editor ();

        //$mform->addElement ( 'advcheckbox', 'showrel', get_string ( 'showrel', 'youtubedrive' ) );
        //$mform->setDefault ( 'showrel', $config->showrel );
        //
        //$mform->addElement ( 'advcheckbox', 'showcontrols', get_string ( 'showcontrols', 'youtubedrive' ) );
        //$mform->setDefault ( 'showcontrols', $config->showcontrols );
        //
        //$mform->addElement ( 'advcheckbox', 'showshowinfo', get_string ( 'showshowinfo', 'youtubedrive' ) );
        //$mform->setDefault ( 'showshowinfo', $config->showshowinfo );
        //
        //$mform->addElement ( 'advcheckbox', 'autoplay', get_string ( 'autoplay', 'youtubedrive' ) );
        //$mform->setDefault ( 'autoplay', $config->autoplay );


        $sizeOptions = array (
            0 => 'ED (3x4)',
            1 => 'HD (16x9)',
            2 => 'HD (16x10)'
        );
        $mform->addElement ( 'select', 'youtubedrivesize', 'Tamanho do vídeo', $sizeOptions );
        $mform->setType ( 'youtubedrivesize', PARAM_INT );
        $mform->setDefault ( 'youtubedrivesize', 1 );

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements ();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements ();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons ();
    }

    public function validation ( $data, $files )
    {
        $errors = parent::validation ( $data, $files );
        return $errors;
    }

}
