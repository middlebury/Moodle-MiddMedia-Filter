Moodle MiddMedia Filter
=======================

This Moodle filter will convert links to MiddMedia media files into embed code and/or HTML5 audio/video elements so that they can be played directly in the browser.

MiddMedia ( https://github.com/middlebury/middmedia ) is a web-based  file-management tool that allows users to upload, delete, preview, and get embed code for media files. MiddMedia is designed to work along-side Adobe's Flash Media Server (FMS) to handle media management duties. See https://github.com/middlebury/middmedia for more details about MiddMedia.

Installation via Git
--------------------

1. At the command line, cd to the `filter` subdirectory in your Moodle installation:

        cd moodle/filter/

2. Clone the repository to a subdirectory named `middmedia`:

        git clone git://github.com/middlebury/Moodle-MiddMedia-Filter.git middmedia
        
Installation via Download
--------------------------

1. At the command line, cd to the `filter` subdirectory in your Moodle installation:

        cd moodle/filter/

2. Download the latest version of the source:

        wget https://github.com/middlebury/Moodle-MiddMedia-Filter/zipball/master -O middmedia_filter-latest.zip
        
3. Unzip the archive:

        unzip middmedia_filter-latest.zip
        
4. Rename the directory:

        mv middlebury-Moodle-MiddMedia-Filter-9836f5a middmedia
        

Configuration
-------------

When logged into Moodle as an admin, go to *Site Administration -> Modules -> Filters -> Manage filters* and enable the plugin. Configuration settings can be changed from their defaults by clicking on the *Settings* link next to the MiddMedia filter.

Usage
-----

In any Moodle text block, just make an HTML link to a middmedia file using the link tool on the Moodle WYSIWYG toolbar. The URL to use is the **HTTP (Download) URL** in MiddMedia. Example:

    <a href="http://middmedia.middlebury.edu/media/afranco/mp4/Course%20Catalog%20Screencast.mp4">Video</a>

When viewing the text-block, the link will be replaced with the embeded video.

To change the dimensions of the video, append `?d=320x240` to the end of the URL where `320` is your desired width and `240` is your desired height. Example:

    <a href="http://middmedia.middlebury.edu/media/afranco/mp4/Course%20Catalog%20Screencast.mp4?d=320x240">Video</a>

The default width is 640 and the default height is 480.