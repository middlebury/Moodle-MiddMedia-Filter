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
 * Filter for embedding audio and video hosted in MiddMedia.
 *
 * MiddMedia ( https://github.com/middlebury/middmedia ) is a web-based 
 * file-management tool that allows users to upload, delete, preview, and get 
 * embed code for media files. MiddMedia is designed to work along-side Adobe's 
 * Flash Media Server (FMS) to handle media management duties.
 *
 * @package    filter
 * @subpackage middmedia
 * @copyright  2011 Middlebury College
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Filter hook.
 * 
 * @param int       $courseid
 * @param string    $text
 * @return string
 */
function middmedia_filter($courseid, $text) {
    return $text;
}
