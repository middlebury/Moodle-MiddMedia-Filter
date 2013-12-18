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
    global $CFG;
    if (empty($CFG->filter_middmedia_protocol)) {
        $CFG->filter_middmedia_protocol = 'http';
    }
    if (empty($CFG->filter_middmedia_host)) {
        $CFG->filter_middmedia_host = 'middmedia.middlebury.edu';
    }
    if (empty($CFG->filter_middmedia_base_path)) {
        $CFG->filter_middmedia_base_path = '/media/';
    }

    $media_base = $CFG->filter_middmedia_protocol.'://'.$CFG->filter_middmedia_host.$CFG->filter_middmedia_base_path;
    $media_base = str_replace('/', '\/', $media_base);

    // Replace audio
    $search = '/<a[^>]* href="'.$media_base.'([^"<?]+\.mp3)"[^>]*>[^<]*<\/a>/is';
    $text = preg_replace_callback($search, 'middmedia_filter_audio_callback', $text);

    // Replace videos
    $search = '/<a[^>]* href="'.$media_base.'([^"<?]+)(\?d=([\d]{1,4}%?)x([\d]{1,4}%?))?"[^>]*>[^<]*<\/a>/is';
    $text = preg_replace_callback($search, 'middmedia_filter_video_callback', $text);

    // Ensure that links to our "view" page don't get picked up by the built-in media filter
    $view_base = $CFG->filter_middmedia_protocol.'://'.$CFG->filter_middmedia_host.'/middmedia/view/';
    $view_base = str_replace('/', '\/', $view_base);
    $search = '/(<a[^>]* href=")('.$view_base.'[^"<?]+[^\/])("[^>]*>[^<]*<\/a>)/is';
    $text = preg_replace_callback($search, 'middmedia_filter_view_callback', $text);

    return $text;
}

/**
 * Replace middmedia audio links with embed code.
 *
 * @param array $matches
 * @return string
 */
function middmedia_filter_audio_callback ($matches) {
    global $CFG;
    $media_base = $CFG->filter_middmedia_protocol.'://'.$CFG->filter_middmedia_host.$CFG->filter_middmedia_base_path;

    $path = middmedia_filter_get_path_parts($matches[1]);
    $mp3 = $media_base.$path['directory'].'/mp3/'.$path['file'].'.mp3';
    $id = preg_replace('/[^a-z0-9]/', '_', $mp3);

    if (empty($CFG->filter_middmedia_audio_player_path)) {
        $CFG->filter_middmedia_audio_player_path = 'http://middmedia.middlebury.edu/AudioPlayer/';
    }

    ob_start();
    static $js_included = false;
    if (!$js_included) {
        print "\n".'<script type="text/javascript" src="'.$CFG->filter_middmedia_audio_player_path.'audio-player.js"></script>';
        $js_included = true;
    }
    print "\n".'<object width="290" height="24" id="'.$id.'" data="'.$CFG->filter_middmedia_audio_player_path.'player.swf" type="application/x-shockwave-flash">';
    print "\n\t".'<param value="'.$CFG->filter_middmedia_audio_player_path.'player.swf" name="movie" />';
    print "\n\t".'<param value="high" name="quality" />';
    print "\n\t".'<param value="false" name="menu" />';
    print "\n\t".'<param value="transparent" name="wmode" />';
    print "\n\t".'<param value="soundFile='.$mp3.'" name="FlashVars" />';
    print "\n</object>\n";
    return ob_get_clean();
}

/**
 * Replace middmedia video links with embed code.
 *
 * @param array $matches
 * @return string
 */
function middmedia_filter_video_callback ($matches) {
    global $CFG;
    $media_base = $CFG->filter_middmedia_protocol.'://'.$CFG->filter_middmedia_host.$CFG->filter_middmedia_base_path;
    $supports_rtmp = !empty($CFG->filter_middmedia_supports_rtmp);

    if (empty($matches[2])) {
        $width = 640;
    } else {
        $width = $matches[2];
    }
    if (empty($matches[3])) {
        $height = 480;
    } else {
        $height = $matches[3];
    }

    $path = middmedia_filter_get_path_parts($matches[1]);

    $poster = $media_base.$path['directory'].'/splash/'.$path['file'].'.jpg';
    $mp4 = $media_base.$path['directory'].'/mp4/'.$path['file'].'.mp4';
    $webm = $media_base.$path['directory'].'/webm/'.$path['file'].'.webm';
    if ($supports_rtmp) {
        if (empty($CFG->filter_middmedia_rtmp_base_path)) {
            $CFG->filter_middmedia_rtmp_base_path = '/vod/';
        }
        $stream = 'rtmp://'.$CFG->filter_middmedia_host.$CFG->filter_middmedia_rtmp_base_path.'mp4:'.$path['directory'].'/mp4/'.$path['file'].'.mp4';
    } else {
        $stream = $mp4;
    }

    ob_start();
    $use_html5 = middmedia_filter_use_html5_video();
    if ($use_html5) {
        print "\n".'<video width="'.$width.'" height="'.$height.'" poster="'.$poster.'" controls>';
        print "\n\t".'<source src="'.$mp4.'" type="video/mp4" />';
        print "\n\t".'<source src="'.$webm.'" type="video/webm" />';
    }
    print "\n\t".'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="'.$width.'" height="'.$height.'">';
    print "\n\t\t".'<param name="movie" value="http://middmedia.middlebury.edu/strobe_mp/StrobeMediaPlayback.swf"></param>';
    print "\n\t\t".'<param name="FlashVars" value="src='.$stream.'&poster='.rawurlencode($poster).'"></param>';
    print "\n\t\t".'<param name="allowFullScreen" value="true"></param>';
    print "\n\t\t".'<param name="allowscriptaccess" value="always"></param>';
    print "\n\t\t".'<embed src="http://middmedia.middlebury.edu/strobe_mp/StrobeMediaPlayback.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="400" height="300" FlashVars="src='.$stream.'&poster='.rawurlencode($poster).'">';
    print '</embed>';
    print "\n\t".'</object>';
    if ($use_html5) {
        print "\n</video>\n";
    }
    return ob_get_clean();
}

/**
 * Tweak middmedia view links so that the built-in media plugin doesn't try to turn them into embed code.
 *
 * @param array $matches
 * @return string
 */
function middmedia_filter_view_callback ($matches) {
    return $matches[1].$matches[2].'/'.$matches[3];
}

/**
 * Answer an array of path info parts for middmedia paths.
 *
 * @param string $path
 * @return array
 */
function middmedia_filter_get_path_parts ($path) {
    $parts = array();
    $info = pathinfo($path);
    $parts['directory'] = dirname($info['dirname']);
    $parts['type_subdir'] = basename($info['dirname']);
    $parts['file'] = $info['filename'];
    return $parts;
}

/**
 * Answer true if HTML 5 markup can be used for video.
 * HTML5 markup will break on Firefox < 4 because we only have MP4 and WebM video
 * and not Ogg video.
 *
 * @return boolean
 */
function middmedia_filter_use_html5_video () {
    // If Firefox.
    if (check_browser_version("Firefox", 0)) {
        // only use HTML5 elements for 4 and later.
        return check_browser_version('Firefox', 4);
    }
    // HTML5 is safe for other browsers.
    return true;
}