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
 * Define all the backup steps that will be used by the backup_youtubedrive_activity_task
 *
 * @package   mod_youtubedrive
 * @category  backup
 * @copyright 2016 Eduardo Kraus ME <kraus@eduardokraus.com>
 * @license   https://www.eduardokraus.com/
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete youtubedrive structure for backup, with file and id annotations
 *
 * @package   mod_youtubedrive
 * @category  backup
 * @copyright 2016 Eduardo Kraus ME <kraus@eduardokraus.com>
 * @license   https://www.eduardokraus.com/
 */
class backup_youtubedrive_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Get know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define the root element describing the youtubedrive instance.
        $youtubedrive = new backup_nested_element('youtubedrive', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'youtubedriveurl', 'youtubedrivesize',
            'showrel', 'showcontrols', 'showshowinfo', 'autoplay'));

        // If we had more elements, we would build the tree here.

        // Define data sources.
        $youtubedrive->set_source_table('youtubedrive', array('id' => backup::VAR_ACTIVITYID));

        // If we were referring to other tables, we would annotate the relation
        // with the element's annotate_ids() method.

        // Define file annotations (we do not use itemid in this example).
        $youtubedrive->annotate_files('mod_youtubedrive', 'intro', null);

        // Return the root element (youtubedrive), wrapped into standard activity structure.
        return $this->prepare_activity_structure($youtubedrive);
    }
}
