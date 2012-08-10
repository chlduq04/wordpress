=== Image Rotator Widget ===
Contributors: srcoley, douglaskarr
Tags: jquery, widget, widgets, image, images, rotator, slider
Requires at least: 2.7
Tested up to: 3.3.1
Stable tag: 0.1.6

Bare bones image rotator.

== Description ==

**Overview**

A widgetized plugin that puts an image rotator on your theme. You can choose from three different transitions: linear, loop, and fade. In the widget settings you have the ability to upload/select images to add to the Image Rotator, and to drag & drop to reorder the order you want the images to appear. For a short video demonstrating the settings workflow, visit: http://www.youtube.com/watch?v=D7YMN8b0Olg

**Features**

* Use the WordPress Media Library to upload or select images
* Choose from three different smooth transitions
* Set a transition speed
* Drag & drop to order images in whichever way you like
* Use the Image Rotator Widget multiple times on the same page
* Widgetized, so you can rotate your images anywhere 
* Works with modern versions of Chrome, Safari, Firefox, and Internet Explorer

**Includes**

* `jQuery`.`qtip`
* `jQuery`.`imagesLoaded`

**About**

The Image Rotator Widget was written by [Stephen Coley](http://coley.co) of [DK New Media](http://dknewmedia.com)

== Installation ==

Here's how you can get started quickly.

1. Upload `image-rotator-widget` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to `Appearance` -> `Widgets`
1. Drag the `Image Rotator Widget` box to an empty slot
1. Choose transition type
1. Add desired images
1. Sort images in any fashion
1. Save!

== Frequently Asked Questions ==

= Does this plugin require a Widget-ready theme? =

Yes. Without a Widget-ready theme, you will not be able to display the image rotator.

= What if I still have a question? =

You can ask questions [Here](http://www.dknewmedia.com/#contact "DK New Media Contact Page")

== Screenshots ==

1. DK New Media's Image Rotator Widget.
2. Once you've selected or uploaded the image, you must click "Send to Image Rotator."
3. Images added to the widget are listed in dynamically in the Images section.
4. Multiple images have been added to this widget
4. Hover over the image name to see a tooltip that contains the image.
5. Drag & Drop images to sort, right in the widget settings.

== Changelog ==

= 0.1.1 =

* readme.txt updates
* bugfix to only run widget's scripts on the widget.php page

= 0.1.2 =

* WordPress 3.3 fix

= 0.1.3 =

* Added ability to use the "From URL" tab on the Media Upload when selecting an image.

= 0.1.4 =

* Added optional widget setting "title".
* Fixed localization bug with "Send the Image Rotator Widget" button

= 0.1.5 =

* Fixed installation bug

= 0.1.6 =

* Fixed 3.4 a bug - wouldn't send the image url to the widget

== Upgrade Notice ==

= 0.1.1 =
Works with WordPress 3.3. This version fixes the bug that broke the "Insert into Post" button on the new/edit post pages. 

= 0.1.2 =
Fixes a bug in 3.3 that adds an image's thumbnail url instead of the original url, to the widget.

= 0.1.3 =
Added ability to use the "From URL" tab on the Media Upload when selecting an image.

= 0.1.4 =

Added optional widget setting "title" and fixed localization bug with "Send the Image Rotator Widget" button

= 0.1.5 =

Fixed installation bug

= 0.1.6 =

Fixed 3.4 a bug - wouldn't send the image url to the widget
