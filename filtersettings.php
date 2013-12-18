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

$settings->add(new admin_setting_configtext('filter_middmedia_protocol', get_string('protocol_title', 'filter_middmedia'), get_string('protocol_desc', 'filter_middmedia'), 'http'));
$settings->add(new admin_setting_configtext('filter_middmedia_host', get_string('host_title', 'filter_middmedia'), get_string('host_desc', 'filter_middmedia'), 'middmedia.middlebury.edu'));
$settings->add(new admin_setting_configtext('filter_middmedia_base_path', get_string('base_path_title', 'filter_middmedia'), get_string('base_path_desc', 'filter_middmedia'), '/media/'));
$settings->add(new admin_setting_configcheckbox('filter_middmedia_supports_rtmp', get_string('supports_rtmp_title', 'filter_middmedia'), get_string('supports_rtmp_desc', 'filter_middmedia'), 1));
$settings->add(new admin_setting_configtext('filter_middmedia_rtmp_base_path', get_string('rtmp_path_title', 'filter_middmedia'), get_string('rtmp_path_desc', 'filter_middmedia'), '/vod/'));
$settings->add(new admin_setting_configtext('filter_middmedia_audio_player_path', get_string('audio_path_title', 'filter_middmedia'), get_string('audio_path_desc', 'filter_middmedia'), 'http://middmedia.middlebury.edu/AudioPlayer/'));