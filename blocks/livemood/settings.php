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
 * Strings for component 'block_livemood', language 'en', branch 'MOODLE_30_STABLE'
 *
 * @package   block_livemood
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die;
global $USER;
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('block_livemood_skey', get_string('skey', 'block_livemood'), get_string('description', 'block_livemood'), null, PARAM_RAW));
	$link ='<a href="http://www.live-school.net/indexOrg.lol" target="_blank">Live-Mood website</a>';
    $settings->add(new admin_setting_heading('block_livemood', '', $link));
}
