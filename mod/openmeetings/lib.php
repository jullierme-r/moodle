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
 * Activity module interface functions are defined here
 *
 * @package     mod_openmeetings
 * @copyright   2017 Maxim Solodovnik <solomax666@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$old_error_handler = set_error_handler("myErrorHandler");
require_once($CFG->dirroot.'/config.php');
require_once($CFG->dirroot.'/mod/openmeetings/api/OmGateway.php');

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	switch ($errno) {
	case E_USER_ERROR:
		echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
		echo "  Fatal error on line $errline in file $errfile";
		echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
		echo "Aborting...<br />\n";
		exit(1);
		break;

	case E_USER_WARNING:
		echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
		break;

	case E_USER_NOTICE:
		echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
		break;

	default:
		//echo "Unknown error type: [$errno] $errstr<br />\n";
		break;
	}

	/* Don't execute PHP internal error handler */
	return true;
}

function getOmUser($gateway) {
	global $USER;
	$pictureUrl = moodle_url::make_pluginfile_url(context_user::instance($USER->id)->id, 'user', 'icon', NULL, '/', 'f2')->out(false);
	return $gateway->getUser($USER->username, $USER->firstname, $USER->lastname, $pictureUrl, $USER->email, $USER->id);
}

function getOmHash($gateway, $options) {
	return $gateway->getSecureHash(getOmUser($gateway), $options);
}

function getOmConfig() {
	global $CFG;
	return array(
			"protocol" => $CFG->openmeetings_protocol,
			"host" => $CFG->openmeetings_host,
			"port" => $CFG->openmeetings_port,
			"context" => $CFG->openmeetings_context,
			"user" => $CFG->openmeetings_user,
			"pass" => $CFG->openmeetings_pass,
			"module" => $CFG->openmeetings_moduleKey
	);
}

function setRoomName(&$openmeetings) {
	$openmeetings->roomname = 'MOODLE_COURSE_ID_' . $openmeetings->course . '_NAME_' . $openmeetings->name;
}

function getRoom(&$openmeetings) {
	setRoomName($openmeetings);
	return array(
			'id' => $openmeetings->room_id > 0 ? $openmeetings->room_id : null
			, 'name' => $openmeetings->roomname
			, 'comment' => 'Created by SOAP-Gateway'
			, 'type' => $openmeetings->type
			, 'numberOfPartizipants' => $openmeetings->max_user
			, 'isPublic' => false
			, 'appointment' => false
			, 'moderated' => 1 == $openmeetings->is_moderated_room
			, 'audioOnly' => false
			, 'allowUserQuestions' => true
			, 'allowRecording' => 1 == $openmeetings->allow_recording
			, 'chatHidden' => 1 == $openmeetings->chat_hidden
			, 'externalId' => $openmeetings->instance
	);
}

function openmeetings_add_instance(&$openmeetings) {
	global $DB;

	$gateway = new OmGateway(getOmConfig());
	if ($gateway->login()) {

		//Roomtype 0 means its and recording, we don't need to create a room for that
		if ($openmeetings->type != 'recording') {
			$openmeetings->room_id = $gateway->updateRoom(getRoom($openmeetings));
		}
	} else {
		echo "Could not login User to OpenMeetings, check your OpenMeetings Module Configuration";
		exit();
	}
	# May have to add extra stuff in here #
	return $DB->insert_record("openmeetings", $openmeetings);
}

function openmeetings_update_instance(&$openmeetings) {
	global $DB;

	$openmeetings->timemodified = time();
	$openmeetings->id = $openmeetings->instance;

	$gateway = new OmGateway(getOmConfig());
	if ($gateway->login()) {

		//Roomtype 0 means its and recording, we don't need to update a room for that
		if ($openmeetings->type == 'recording') {
			$openmeetings->room_id = 0;
		} else {
			$openmeetings->room_id = $gateway->updateRoom(getRoom($openmeetings));
		}
	} else {
		echo "Could not login User to OpenMeetings, check your OpenMeetings Module Configuration";
		exit();
	}
	# May have to add extra stuff in here #
	return $DB->update_record("openmeetings", $openmeetings);
}

function openmeetings_delete_instance($id) {
	global $DB;

	if (!$openmeetings = $DB->get_record("openmeetings", array("id" => "$id"))) {
		return false;
	}

	$result = true;

	$gateway = new OmGateway(getOmConfig());
	if ($gateway->login()) {
		//Roomtype 0 means its and recording, we don't need to update a room for that
		if ($openmeetings->type != 'recording') {
			$openmeetings->room_id = $gateway->deleteRoom($openmeetings->room_id);
		}
	} else {
		echo "Could not login User to OpenMeetings, check your OpenMeetings Module Configuration";
		exit();
	}

	# Delete any dependent records here #
	if (!$DB->delete_records("openmeetings", array("id" => "$openmeetings->id"))) {
		$result = false;
	}
	return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 *
 * See {@link get_array_of_activities()} in course/lib.php
 *
 * @param object $coursemodule
 * @return object info
 */
function openmeetings_get_coursemodule_info($coursemodule) {
	global $DB;

	if (!$meeting = $DB->get_record('openmeetings', array ('id' => $coursemodule->instance))) {
		return NULL;
	}

	if ($meeting->whole_window != 2) {
		return null;
	}
	$info = new cached_cm_info();
	$info->name = $meeting->name;
	$info->onclick = "window.open('" . new moodle_url('/mod/openmeetings/view.php', array ('id' => $coursemodule->id)) . "');return false;";
	return $info;
}

function openmeetings_user_outline($course, $user, $mod, $openmeetings) {
	return true;
}

function openmeetings_user_complete($course, $user, $mod, $openmeetings) {
	return true;
}

function openmeetings_print_recent_activity($course, $isteacher, $timestart) {
	return false;  //  True if anything was printed, otherwise false
}

function openmeetings_cron () {
	return true;
}

function openmeetings_grades($openmeetingsid) {
	return NULL;
}

function openmeetings_get_participants($openmeetingsid) {
	return false;
}

function openmeetings_scale_used($openmeetingsid, $scaleid) {
	return false;
}

function openmeetings_scale_used_anywhere($scaleid) {
	return false;
}
