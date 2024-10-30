===MPQ Clean Video Gallery ===
Contributors: zhouyibhic
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VLFPQMLSD9MJC
Tags: gallery, video, gallery video, video album, vid, videoalbum, website video gallery, multiple videos, videos, movie, moviealbum, videogallery, videoalbum
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin help to implement video gallery page into your web site. It reuses Custom Type Post. No specific db change needed. 

== Description ==

This plugin makes it possible to implement as many video galleries as you want into your website. You can add multiple videos and description for each video gallery, show them all at one page, view each one separately. 

= Features =

* Actions: Create any quantity of the video galleries.
* Description: Add description to each video gallery.
* Actions: Possibility to set featured image as cover of the video gallery.
* Actions: Possibility to load any number of videos to each video gallery.
* Actions: Possibility to add Single Video Gallery to your page or post with shortcode.
* Actions: Option to make the sorting settings of attachments in the admin panel.
* Caption: Add caption to each video in the gallery.
* Display: You can select dimensions of the thumbnails for the cover of the gallery as well as for videos in the gallery.
* Multilingual: Multilingual fully supported. Works with qTranslate as well. 

= Translation =
* Chinese(zh_CN)

If you create your own language pack or update an existing one, you can send <a href="http://codex.wordpress.org/Translating_WordPress" target="_blank">the text in PO and MO files</a> for <a href="mailto:zhouyibhic@gmail.com">MPQ Support</a> and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO files  <a href="http://www.poedit.net/download.php" target="_blank">Poedit</a>.

= Technical support =

Dear users, if you have any questions or propositions regarding our plugins (current options, new options, current issues) please feel free to contact us. Please note that we accept requests in English only. All messages on another languages wouldn't be accepted. 

Also, emails which are reporting about plugin's bugs are accepted for investigation and fixing. Your request must contain URL of the website, issues description and WordPress admin panel access. Plugin customization based on your Wordpress theme is a paid service (standard price is $10, but it could be higer and depends on the complexity of requested changes). We will analize existing issue and make necessary changes after 100% pre-payment.All these paid changes and modifications could be included to the next version of plugin and will be shared for all users like an integral part of the plugin. Free fixing services will be provided for user who send translation on their native language (this should be a new translation of a certain plugin, and you can check available translations on the official plugin page).

== Installation ==
0. Install ffmpeg on the server box. Googling 'ffmpeg installation' for the installation manual.
1. Upload unzipped folder to the directory `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Please check if you have the `gallery-video-template.php` template file as well as `gallery-video-single-template.php` template file in your templates directory. If you are not able to find these files, then just copy it from `/wp-content/plugins/gallery/template/` directory to your templates directory.

== Frequently Asked Questions ==

= Video can't be uploaded successfully =
1. check if you have ffmpeg installed on your server. This plugin needs the package to convert video and generate thumbnail picture for uploaded video.
2. check if you have added the file extentions of the video formats in the network settings.

= I cannot view my Video Gallery page =

1. First of all, you need to create your first Video Gallery page and choose 'Gallery-Video' from the list of available templates (which will be used for displaying our gallery).
2. If you cannot find 'Gallery-video' in the list of available templates, then just copy it from `/wp-content/plugins/MPQ Video Gallery Folder/template/` directory to your templates directory.

= How to use plugin? =

1. Choose 'Add New' from the 'Video Galleries' menu and fill out your page.
2. Upload videos by using an uploader in the bottom of the page. 
3. Save the page.

= How to add an video? =

- Choose the necessary gallery from the list on the Video Galleries page in admin section (or create a new gallery - choose 'Add New' from the 'Video Galleries' menu). 
- Use the option 'Upload a file' available in the uploader, choose the necessary pictures and click 'Open'
- The files uploading process will start.
- Once all videos are uploaded, please save the page.
- If you see the message 'Please enable JavaScript to use the file uploader.', you should enable JavaScript in your browser.

= How to add many videos? =

The multiple files upload is supported by all modern browsers except Internet Explorer. 

= I'm getting the following error: Fatal error: Call to undefined function get_post_thumbnail_id(). What am I to do? ? =

This error says that your theme doesn't support thumbnail option, in order to add this option please find 'functions.php' file in your theme and add the following strings to this file:

`add_action( 'after_setup_theme', 'theme_setup' );

function theme_setup() {
    add_theme_support( 'post-thumbnails' );
}`

After that your theme will support thumbnail option and the error won't display again.

= How to change image order on single gallery page? =

1. Please open the menu "Video Galleries" and choose random gallery from the list. You should be redirected to the gallery editing page. 
Please use drag and drop function to change the order of the output of images and do not forget to save post.
Please do not forget to select `Attachments order by` -> `attachments order` in the settings of the plugin (page http://your_domain/wp-admin/admin.php?page=gallery-plugin-video.php) 

2. Please open the menu "video Galleries" and choose random gallery from the list. You should be redirected to the gallery editing page. 
There will be one or several media upload icons between the title and content adding blocks. Please choose any icon. 
After that you'll see a popup window with three or four tabs. 
Choose gallery tab and there'll be displayed attached files which are related to this gallery. 
You can change their order using drag'n'drop method. 
Just setup a necessary order and click 'Save' button.

== Changelog ==

= V0.2 - 2.1.2013 =
* first beta version.
* support xmlrpc calls (Has to co-work with plugin mpqvideogallery-xmlrpc)


= V0.1 - 23.10.2012 =
* initial version.
* known issue, video deletion is not working yet.
