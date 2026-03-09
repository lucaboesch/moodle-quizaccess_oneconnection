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
 * Event observers for the oneconnection rule.
 *
 * @package    quizaccess_oneconnection
 * @category    event
 * @copyright   2016 Vadim Dvorovenko
 * @copyright   2025 lern.link GmbH <team@lernlink.de>, Adrian Sarmas, Vadym Nersesov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_oneconnection;

use core\event\base;

/**
 * Event observer callbacks.
 *
 * These observers remove the stored session fingerprint when an attempt
 * is no longer active (finished, abandoned, submitted or deleted).
 * @package quizaccess_oneconnection
 */
class observers {
    /**
     * Remove unneeded session information when an attempt is no longer active.
     *
     * This is triggered when an attempt is finished, abandoned, submitted, or deleted,
     * effectively releasing the session lock.
     *
     * @param base $event The event object.
     * @return void
     */
    public static function unlock_attempt(base $event): void {
        global $DB;

        $attemptid = $event->objectid;
        if (!empty($attemptid)) {
            $DB->delete_records('quizaccess_oneconnection_sess', ['attemptid' => $attemptid]);
        }
    }
}
