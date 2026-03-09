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

namespace quizaccess_oneconnection;

use basic_testcase;
use mod_quiz\quiz_settings;
use quizaccess_oneconnection;
use stdClass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/quiz/accessrule/oneconnection/rule.php');

/**
 * Unit tests for the quizaccess_oneconnection plugin.
 *
 * @copyright Luca Bösch <luca.boesch@bfh.ch>
 * @package   quizaccess_oneconnection
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers    \quizaccess_oneconnection
 */
final class rule_test extends basic_testcase {
    public function test_oneconnection_rule_creation(): void {
        global $DB;
        $quiz = new stdClass();
        $quiz->id = 1;
        $cm = new stdClass();
        $cm->id = 0;
        $quizobj = new quiz_settings($quiz, $cm, null);

        $quiz->oneconnectionenabled = 1;
        $rule = quizaccess_oneconnection::make($quizobj, 0, false);

        $this->assertInstanceOf('quizaccess_oneconnection', $rule);
    }
}
