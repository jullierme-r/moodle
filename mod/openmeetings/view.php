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
 * view the openmeetings instance
 *
 * @package     mod_openmeetings
 * @copyright   2017 Maxim Solodovnik <solomax666@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once ("../../config.php");
require_once ("lib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$g = optional_param('g', 0, PARAM_INT);

if (!empty($id)) {
	if (!$cm = get_coursemodule_from_id('openmeetings', $id)) {
		print_error('invalidcoursemodule');
	}
	if (!$course = $DB->get_record("course", array(
			"id" => $cm->course
	))) {
		print_error('coursemisconf');
	}
	if (!$openmeetings = $DB->get_record("openmeetings", array(
			"id" => $cm->instance
	))) {
		print_error('invalidid', 'openmeetings');
	}
} else if (!empty($g)) {
	if (!$openmeetings = $DB->get_record("openmeetings", array(
			"id" => $g
	))) {
		print_error('invalidid', 'openmeetings');
	}
	if (!$course = $DB->get_record("course", array(
			"id" => $openmeetings->course
	))) {
		print_error('invalidcourseid');
	}
	if (!$cm = get_coursemodule_from_instance("openmeetings", $openmeetings->id, $course->id)) {
		print_error('invalidcoursemodule');
	}
	$id = $cm->id;
} else {
	print_error('invalidid', 'openmeetings');
}

require_login($course->id);
$context = context_module::instance($cm->id);

$event = \mod_openmeetings\event\course_module_viewed::create(array(
		'objectid' => $openmeetings->id,
		'context' => $context,
));
$event->add_record_snapshot('openmeetings', $openmeetings);
$event->trigger();

$output = $PAGE->get_renderer('mod_openmeetings');
$openmeetingswidget = new openmeetings($openmeetings, false);

echo $output->header();
echo $output->render($openmeetingswidget);
echo $output->footer();
