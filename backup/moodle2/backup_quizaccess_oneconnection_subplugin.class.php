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
 * Backup handler for the oneconnection quiz access plugin.
 *
 * @package     quizaccess_oneconnection
 * @category    backup
 * @copyright   2016 Vadim Dvorovenko
 * @copyright   2025 lern.link GmbH <team@lernlink.de>, Adrian Sarmas, Vadym Nersesov
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/backup/moodle2/backup_mod_quiz_access_subplugin.class.php');

/**
 * Provides the information to backup the oneconnection quiz access plugin.
 *
 * This class defines which data associated with the plugin should be included
 * in a course backup.
 * @package quizaccess_oneconnection
 */
class backup_quizaccess_oneconnection_subplugin extends backup_mod_quiz_access_subplugin {
    /**
     * Define the structure of the data to be added to the quiz backup XML.
     *
     * @return backup_nested_element
     */
    protected function define_quiz_subplugin_structure() {
        $subplugin = $this->get_subplugin_element();
        $wrapper = new backup_nested_element($this->get_recommended_name());
        $subplugin->add_child($wrapper);

        // Define the structure for the main per-quiz setting.
        $settings = new backup_nested_element('quizaccess_oneconnection', null, ['enabled']);
        $wrapper->add_child($settings);
        $settings->set_source_table('quizaccess_oneconnection', ['quizid' => backup::VAR_ACTIVITYID]);

        // Define the structure for the audit log of manual unlocks.
        $logs = new backup_nested_element('logs');
        $wrapper->add_child($logs);

        $log = new backup_nested_element('log', ['id'], ['attemptid', 'unlockedby', 'timeunlocked']);
        $logs->add_child($log);
        $log->set_source_table('quizaccess_oneconnection_log', ['quizid' => backup::VAR_ACTIVITYID]);

        // Annotate IDs so they can be mapped correctly during restore.
        $log->annotate_ids('quiz_attempt', 'attemptid');
        $log->annotate_ids('user', 'unlockedby');

        return $subplugin;
    }
}
