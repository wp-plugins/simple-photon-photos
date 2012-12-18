=== Simple Photon Photos ===
Contributors: scottsousa, slocumstudio
Donate link: 
Tags: photon, photos, images, photon photos, photo effects, photo editing, simple photos, photon api, image editing, image effects, image api, photon images
Requires at least: 3.4
Tested up to: 3.5
Stable tag: 0.5
License: GPLv2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simply and easily adjust the size and add effects to your photos utilizing the Jetpack Photon API. This plugin requires [Jetpack by WordPress.com](http://jetpack.me).

== Description ==

With the release of the Photon API in Jetpack v2.0, we noticed that there wasn't a simple way to use the GET query arguments supplied in the [API documentation](http://developer.wordpress.com/docs/photon/api/). We set out to change this.

Introducing Simple Photon Photos, the simplest and easiest way to add JetPack Photon API effects to photos on your WordPress website. This plugin adds a control panel to the Add Media Panel when inserting an image into a post. It utilizes jQuery UI Slider Widgets to give you control over the effects you'd like to add to your images.

This plugin requires [Jetpack by WordPress.com](http://jetpack.me).

**Please Note: This plugin is considered an alpha release and should be used with caution. We'll be actively updating it when we can, so please report any bugs [here](http://simplephotonphotos.com) or in the support forums.**

**Known Bugs/Issues**
1. When multiple images are selected (WordPress 3.5), Simple Photon Photos options are still displayed but are displayed as default values even though values may have already been changed.

== Installation ==

1. Upload Simple Photon Photos to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Simple Photon API features will be added to the Add Media Panel when inserting images into a post

== Frequently asked questions ==

= I cant get this plugin to activate. Can you please help? =

= Can I preview my photos before inserting them into a post? =

Not at this time, but we're actively working on this feature. Expect this functionality to be included in future releases.

= I've selected multiple images in >= WordPress 3.5 and Simple Photon Photo settings are displayed as default values even though I've changed them. Is this functionality correct? =

At this time it is, however we'll most likely be updating this in a future release. Please stay tuned for updates.

== Screenshots ==

1. Simple Photon Photos as seen in Add Media Panel (3.5)

2. More Simple Photon Photos options as seen in Add Media Panel (3.5)


== Changelog ==

= 0.5 =
 * **Initial Public Release**
 * Enhancement: Added colorize, smooth, and zoom GET query parameters to media modal
 * Enhancement: Better sanity checks on client and server side for custom post meta
 * Bug Fix (UX Enhancement): Prevented alpha characters in input boxes on client side
 * Bug Fix: Ensured that resize and fit query parameters are sanitized properly and values are added to query in the proper format
 * UX Enhancement: Removed controls from Attachment Editor in all versions of WordPress (for now)

= 0.4 =
 * Bug Fix: Fixed bug where custom post meta was not saved due to "change" event not being triggered on input boxes
 * Enhancement: Added resize, fit, ulb, and filter GET query parameters to media modal
 * Enhancement (JS): Sanity checks on width/height input boxes
 * Bug Fix: Added conditonal to check if post meta existed (fixes issue where image was not inserted into editor)

= 0.3 =
 * Enhancement: Added functionality to modify image source upon "image_send_to_editor" filter to append test GET queries

= 0.2 =
 * Enhancement: Added custom jQuery UI stylesheet
 * Enhancement: Added JS for media modal
 * Enhancement: Created custom fields ("attachment_fields_to_edit") for width and height GET queries

= 0.1 =
 * **Initial Private Release**

== Upgrade notice ==



== Features ==

Simple Photon Photos allows you to add or modify the following features using the [Photon API](http://developer.wordpress.com/docs/photon/api/):

* [Width](http://developer.wordpress.com/docs/photon/api/#w)
* [Height](http://developer.wordpress.com/docs/photon/api/#h)
* [Resize](http://developer.wordpress.com/docs/photon/api/#resize)
* [Fit](http://developer.wordpress.com/docs/photon/api/#fit)
* [Ulb](http://developer.wordpress.com/docs/photon/api/#ulb)
* [Filter](http://developer.wordpress.com/docs/photon/api/#filter)
* [Brightness](http://developer.wordpress.com/docs/photon/api/#brightness)
* [Contrast](http://developer.wordpress.com/docs/photon/api/#contrast)
* [Colorize](http://developer.wordpress.com/docs/photon/api/#colorize)
* [Smooth](http://developer.wordpress.com/docs/photon/api/#smooth)
* [Zoom](http://developer.wordpress.com/docs/photon/api/#zoom)

* **Crop support coming soon**