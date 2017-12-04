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
 */

define('NO_MOODLE_COOKIES', true);

require('../../../../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/lib/editor/tinymce/plugins/glatex/glatex.php');
$PAGE->set_title(get_string('title', 'tinymce_glatex'));
$PAGE->set_pagelayout('embedded');

$editor = get_texteditor('tinymce');
$plugin = $editor->get_plugin('glatex');

$PAGE->requires->js(new moodle_url($editor->get_tinymce_base_url().'/tiny_mce_popup.js'));
$PAGE->requires->js(new moodle_url($plugin->get_tinymce_file_url('js/dialog.js')));

echo $OUTPUT->header();

?>
<form onsubmit="LatexDialog.insert();return false;" action="#">
    <p><?php print_string('pastecode', 'tinymce_glatex')?></p>
    <p>
        <input type="button" id="preview" name="preview" value="<?php print_string('preview', 'tinymce_glatex')?>"
        onclick="LatexDialog.preview();" />
    </p>
    <p class="glatex-preview" title="<?php echo get_string('preview', 'tinymce_glatex'); ?>">
        <img id="previewImg" src="" alt=""/>
    </p>
    <p><textarea name="latex_code" id="glatex_input"></textarea></p>
    <div class="mceActionPanel">
        <input type="button" id="insert" name="insert" value="{#insert}" onclick="LatexDialog.insert();" />
        <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
    </div>
</form>
<?php

echo $OUTPUT->footer();
