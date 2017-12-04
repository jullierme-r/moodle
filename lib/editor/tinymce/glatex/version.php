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
 * This plugin is only wrapper for TinyMCE LaTeX Plugin developed by moonwave99
 * https://moonwave99.github.io/TinyMCELatexPlugin/
 *
 * @package   tinymce_glatex
 * @author    Yevhen Matasar <matasar.ei@gmail.com>
 * @copyright Borys Grinchenko Kyiv University, 2016
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://docs.moodle.org/dev/version.php
 */

defined('MOODLE_INTERNAL') || die();

// The current plugin version (Date: YYYYMMDDXX).
$plugin->version   = 2017080900;
// Required Moodle version.
$plugin->requires  = 2012112900;
// Release type.
$plugin->maturity = MATURITY_STABLE;
// Version in human readable format.
$plugin->release = '1.0.3';
// Full name of the plugin (used for diagnostics).
$plugin->component = 'tinymce_glatex';
