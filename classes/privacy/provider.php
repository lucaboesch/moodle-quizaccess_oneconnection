<?php
// This file is part of Moodle - http://moodle.org/.
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
 * Privacy provider for the oneconnection quiz access rule.
 *
 * This plugin stores only audit information about which teacher/invigilator
 * allowed a connection change for which quiz attempt.
 *
 * @package     quizaccess_oneconnection
 * @category    privacy
 * @copyright   2025 lern.link GmbH <team@lernlink.de>, Adrian Sarmas, Vadym Nersesov
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_oneconnection\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\provider as metadata_provider;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\plugin\provider as plugin_provider;
use core_privacy\local\request\userlist;
use core_privacy\local\request\core_userlist_provider;

/**
 * Privacy provider for the oneconnection plugin.
 *
 * Implements Moodle's privacy API to handle data requests and deletions.
 * @package quizaccess_oneconnection\privacy
 */
class provider implements core_userlist_provider, metadata_provider, plugin_provider {
    /**
     * Get the metadata that describes the data stored by this plugin.
     *
     * @param collection $collection The collection to add metadata to.
     * @return collection The updated collection.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'quizaccess_oneconnection_log',
            [
                'unlockedby' => 'privacy:metadata:log:unlockedby',
            ],
            'privacy:metadata:log'
        );
        return $collection;
    }

    /**
     * Return contexts containing user data for this plugin for a given user.
     *
     * A user may appear here if they are a teacher/invigilator who unlocked
     * another user's quiz attempt.
     *
     * @param int $userid The user ID to search for.
     * @return contextlist List of contexts where this user has data.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        global $DB;

        $contextlist = new contextlist();

        $sql = "SELECT ctx.id
                  FROM {context} ctx
                  JOIN {course_modules} cm ON cm.id = ctx.instanceid
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                  JOIN {quiz} q ON q.id = cm.instance
                  JOIN {quizaccess_oneconnection_log} qol ON qol.quizid = q.id
                 WHERE ctx.contextlevel = :contextlevel
                   AND qol.unlockedby = :userid";

        $params = [
            'modname' => 'quiz',
            'contextlevel' => CONTEXT_MODULE,
            'userid' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export user data.
     *
     * This plugin only stores audit data about teachers/invigilators, which is
     * not considered personal data owned by the user being exported. Therefore,
     * no data is exported.
     *
     * @param approved_contextlist $contextlist Approved contexts for the user.
     * @return void
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        // Intentionally does nothing – this is teacher/invigilator audit data, not student data.
    }

    /**
     * Delete all data for all users in a given context.
     *
     * This is typically called when a course module is deleted.
     *
     * @param \context $context The context being deleted.
     * @return void
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }

        $cm = get_coursemodule_from_id(null, $context->instanceid);
        if (!$cm || $cm->modname !== 'quiz') {
            return;
        }

        // Delete all log entries associated with this quiz.
        $quizid = $cm->instance;
        $DB->delete_records('quizaccess_oneconnection_log', ['quizid' => $quizid]);
    }

    /**
     * Delete data for a specific user in a list of contexts.
     *
     * @param approved_contextlist $contextlist Approved contexts for the user.
     * @return void
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }

            $cm = get_coursemodule_from_id(null, $context->instanceid);
            if (!$cm || $cm->modname !== 'quiz') {
                continue;
            }

            // Delete log entries where this user was the one who unlocked an attempt.
            $quizid = $cm->instance;
            $DB->delete_records('quizaccess_oneconnection_log', [
                'quizid' => $quizid,
                'unlockedby' => $userid,
            ]);
        }
    }

    /**
     * Get a list of users who have data in the given context.
     *
     * @param userlist $userlist The userlist to populate.
     * @return void
     */
    public static function get_users_in_context(userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }

        $cm = get_coursemodule_from_id(null, $context->instanceid);
        if (!$cm || $cm->modname !== 'quiz') {
            return;
        }

        // Find all users who have unlocked an attempt in this quiz.
        $quizid = $cm->instance;
        $sql = "SELECT DISTINCT unlockedby
                  FROM {quizaccess_oneconnection_log}
                 WHERE quizid = :quizid";
        $params = ['quizid' => $quizid];

        $userlist->add_from_sql('unlockedby', $sql, $params);
    }

    /**
     * Delete data for multiple users in the given context.
     *
     * @param approved_userlist $userlist The approved list of users whose data should be deleted.
     * @return void
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }

        $cm = get_coursemodule_from_id(null, $context->instanceid);
        if (!$cm || $cm->modname !== 'quiz') {
            return;
        }

        $quizid = $cm->instance;
        $userids = $userlist->get_userids();

        // Build a query to delete log entries for the specified users in this quiz.
        [$insql, $inparams] = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $params = ['quizid' => $quizid] + $inparams;

        $DB->delete_records_select(
            'quizaccess_oneconnection_log',
            "quizid = :quizid AND unlockedby $insql",
            $params
        );
    }
}
