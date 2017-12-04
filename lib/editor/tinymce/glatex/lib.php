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
 * @package   tinymce_glatex
 * @author    Yevhen Matasar <matasar.ei@gmail.com>
 * @copyright Borys Grinchenko Kyiv University, 2016
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://docs.moodle.org/dev/Creating_a_Moodle_specific_TinyMCE_plugin
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Google API based LaTeX pulugin for TinyMCE
 */
class tinymce_glatex extends editor_tinymce_plugin {

    /**
     * @var array list of buttons defined by this plugin
     */
    protected $buttons = array('latex');

    /**
     * Update editor params on init
     */
    protected function update_init_params(array &$params, context $context, array $options = null) {

        // Add button to the editor.
        $this->add_button_after($params, $this->count_button_rows($params), 'glatex');

        // Add JS file, which uses default name.
        $this->add_js_plugin($params);

    }
}
