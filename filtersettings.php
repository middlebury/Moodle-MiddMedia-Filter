<?php

$settings->add(new admin_setting_configtext('filter_middmedia_protocol', get_string('protocol_title','filter_middmedia'), get_string('protocol_desc', 'filter_middmedia'), 'http'));
$settings->add(new admin_setting_configtext('filter_middmedia_host', get_string('host_title','filter_middmedia'), get_string('host_desc', 'filter_middmedia'), 'middmedia.middlebury.edu'));
$settings->add(new admin_setting_configtext('filter_middmedia_base_path', get_string('base_path_title','filter_middmedia'), get_string('base_path_desc', 'filter_middmedia'), '/media/'));
$settings->add(new admin_setting_configcheckbox('filter_middmedia_supports_rtmp', get_string('supports_rtmp_title','filter_middmedia'), get_string('supports_rtmp_desc', 'filter_middmedia'), 1));
$settings->add(new admin_setting_configtext('filter_middmedia_rtmp_base_path', get_string('rtmp_path_title','filter_middmedia'), get_string('rtmp_path_desc', 'filter_middmedia'), '/vod/'));