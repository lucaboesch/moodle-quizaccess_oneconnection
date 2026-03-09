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
 * Admin settings for the "Block concurrent sessions" quiz access rule.
 *
 * @package     quizaccess_oneconnection
 * @category    admin
 * @copyright   2016 Vadim Dvorovenko
 * @copyright   2025 lern.link GmbH <team@lernlink.de>, Adrian Sarmas, Vadym Nersesov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // General section heading.
    $settings->add(
        new admin_setting_heading(
            'quizaccess_oneconnection/heading',
            get_string('generalsettings', 'admin'),
            get_string('settingsintro', 'quizaccess_oneconnection')
        )
    );

    // Setting to control whether the rule is enabled by default for new quizzes.
    $settings->add(
        new admin_setting_configcheckbox_with_advanced(
            'quizaccess_oneconnection/defaultenabled',
            get_string('oneconnection', 'quizaccess_oneconnection'),
            get_string('defaultenabled_desc', 'quizaccess_oneconnection'),
            ['value' => 0, 'adv' => true]
        )
    );

    // Advanced section heading.
    $settings->add(
        new admin_setting_heading(
            'quizaccess_oneconnection/headingadvanced',
            get_string('advancedsettings', 'moodle'),
            ''
        )
    );

    // Setting for a list of IP subnets to be ignored during session validation.
    $settings->add(
        new admin_setting_configtextarea(
            'quizaccess_oneconnection/whitelist',
            get_string('whitelist', 'quizaccess_oneconnection'),
            get_string('whitelist_desc', 'quizaccess_oneconnection'),
            '',
            PARAM_RAW,
            60,
            5
        )
    );
}
