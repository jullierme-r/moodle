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
 * Mylivechat block config form definition
 *
 * @package    contrib
 * @subpackage block_mylivechat
 * @copyright  mylivechat.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

/**
 * Mylivechat block config form class
 *
 * @copyright mylivechat.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mylivechat_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

		// Start block specific section in config form  
        //$mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('header', 'configheader', get_string('settingheader', 'block_mylivechat'));

		
		//Mylivechat settings
		$mform->addElement('text', 'config_mylivechat_id', "MyLiveChat ID", array('size' => 8,'style'=>'width:120px;'));
        $mform->setDefault('config_mylivechat_id', '');
        $mform->setType('config_mylivechat_id', PARAM_INTEGER);

		$mform->addElement('html', '<div class="fitem"><div class="fitemtitle">&nbsp;</div>
		<div class="felement ftext">Don\'t have MyLiveChat account? <a href="https://www.mylivechat.com/register.aspx" target="_blank">Get it for free!</a></div></div>');

		$displaytypes = array(
      "embedded" => "Embedded Chat",
      "widget" => "Chat Wdiget",
			"button" => "Chat Button",
			"box" => "Chat Box",
			"link" => "Chat Link",
      "monitor" => "Monitor Only"
		);
		$mform->addElement('select', 'config_mylivechat_displaytype', "Display Type", $displaytypes);
		$mform->setDefault('config_mylivechat_displaytype', "button");

		$memberships = array(
			"yes" => "Yes",
			"no" => "No"
		);
		$mform->addElement('select', 'config_mylivechat_membership', "Integrate User", $memberships);
		$mform->setDefault('config_mylivechat_membership', "no");

		$encrymodes = array(
			"none" => "None",
			"basic" => "Basic"
		);
		$mform->addElement('select', 'config_mylivechat_encrymode', "Encryption Mode", $encrymodes);
		$mform->setDefault('config_mylivechat_encrymode', "none");

		$mform->addElement('text', 'config_mylivechat_encrykey', "Encryption Key", array('style'=>'width:160px;'));
        $mform->setDefault('config_mylivechat_encrykey', '');
    }
}
?>