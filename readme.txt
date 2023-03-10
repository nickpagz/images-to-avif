=== Images to avif ===
Contributors: kubiq
Donate link: https://www.paypal.me/jakubnovaksl
Tags: avif, images, pictures, optimize, convert, media
Requires at least: 3.0.1
Requires PHP: 5.6
Tested up to: 6.1
Stable tag: 4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Convert PNG, JPG and GIF images to avif and speed up your web


== Description ==

Statistics say that avif format can save over a half of the page weight without losing images quality.
Convert PNG, JPG and GIF images to avif and speed up your web, save visitors download data, make your Google ranking better.

<ul>
	<li><strong>automated test after plugin activation to make sure it will work on your server</strong></li>
	<li><strong>works with all types of WordPress installations: domain, subdomain, subdirectory, multisite/network</strong></li>
	<li><strong>works on Apache and NGiNX</strong></li>
	<li><strong>image URL will be not changed</strong> so it works everywhere, in &lt;img&gt; src, srcset, &lt;picture&gt;, even in CSS backgrounds and there is no problem with cache</li>
	<li><strong>original files will be not touched</strong></li>
	<li>set quality of converted images</li>
	<li>auto convert on upload</li>
	<li>only convert image if avif filesize is lower than original image filesize</li>
	<li>bulk convert existing images to avif ( you can choose folders )</li>
	<li>bulk convert only missing images</li>
	<li>works with `Fly Dynamic Image Resizer` plugin</li>
</ul>

## Hooks for developers

#### itw_extensions
Maybe you want to support also less famous JPEG extension like jpe, jfif or jif

`add_filter( 'itw_extensions', 'extra_itw_extensions', 10, 1 );
function extra_itw_extensions( $extensions ){
	$extensions[] = 'jpe';
	$extensions[] = 'jfif';
	$extensions[] = 'jif';
	return $extensions;
}`

#### itw_sizes
Maybe you want to disable avif for thumbnails

`add_filter( 'itw_sizes', 'disable_itw_sizes', 10, 2 );
function disable_itw_sizes( $sizes, $attachmentId ){
	unset( $sizes['thumbnail'] );
	return $sizes;
}`

#### itw_htaccess
Maybe you want to modify htaccess rules somehow

`add_filter( 'itw_htaccess', 'modify_itw_htaccess', 10, 2 );
function modify_itw_htaccess( $rewrite_rules ){
	// do some magic here
	return $rewrite_rules;
}`

#### $images_to_avif->convert_image()
Maybe you want to automatically generate avif for other plugins

`add_action( 'XXPLUGIN_image_created', 'XX_images_to_avif', 10, 2 );
function XX_images_to_avif( $image_path ){
	global $images_to_avif;
	$images_to_avif->convert_image( $image_path );
}`


== Installation ==

1. Upload `images-to-avif` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Plugin requirements =

It should work almost everywhere ;)
PHP 5.6 or higher
GD or Imagick extension with avif support
Enabled server modules: `mod_mime`, `mod_rewrite`

= avif images stored location =

avif images are generated in same directory as original image. Example:
original img: `/wp-content/uploads/2019/11/car.png`
avif version: `/wp-content/uploads/2019/11/car.png.avif`

= How to get original image from the browser? =

Just add `?no_avif=1` to the URL and original JPG/PNG will be loaded

= How to check if plugin works? =

When you have installed plugin and converted all images, follow these steps:

1. Run `Google Chrome` and enable `Dev Tools` (F12).
2. Go to the `Network` tab click on `Disable cache` and select filtering for `Img` *(Images)*.
3. Refresh your website page.
4. Check list of loaded images. Note `Type` column.
5. If value of `avif` is there, then everything works fine.

= Apache .htaccess =

Plugin should automatically update your .htaccess with needed rules.
In case it's not possible to write them automatically, screen with instructions will appear.
Anyway, here is how it should look like:

`<IfModule mod_mime.c>
	AddType image/avif .avif
</IfModule>

<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{HTTP_ACCEPT} image/avif
	RewriteCond %{REQUEST_FILENAME} "/"
	RewriteCond %{REQUEST_FILENAME} "\.(jpg|jpeg|png|gif)$"
	RewriteCond %{REQUEST_FILENAME}\.avif -f
	RewriteCond %{QUERY_STRING} !no_avif
	RewriteRule ^(.+)$ $1\.avif [NC,T=image/avif,E=avif,L]
</IfModule>`

= NGiNX config =

After you activate plugin, screen with instructions will appear.
Anyway, here is how it should look like:

You need to add this map directive to your http config, usually nginx.conf ( inside of the http{} section ):

`map $arg_no_avif $no_avif{
	default "";
	"1" "no_avif";
}

map $http_accept $avif_suffix{
	default "";
	"~*avif" ".avif";
}`

then you need to add this to your server block, usually site.conf or /nginx/sites-enabled/default ( inside of the server{} section ):

`location ~* ^/.+\.(png|gif|jpe?g)$ {
	add_header Vary Accept;
	try_files $uri$avif_suffix$no_avif $uri =404;
}`

= ISP Manager =

Are you using ISP Manager? Then it's probably not working for you, but no worries, you just need to go to `WWW domains` and delete `jpg|jpeg|png` from the `Static content extensions` field.


== Changelog ==

= 4.1 =
* fix - convert also all subdirectories

= 4.0 =
* lazy load folders in convert tab
* make it works for local installations like XAMPP or Flywheel Local
* try-catch conversion errors
* updated jstree library

= 3.1 =
* add ?no_avif=1 to URL to receive original image content from Nginx server

= 3.0 =
* Tested on WP 6.1
* added support for Better image sizes plugin
* add ?no_avif=1 to URL to receive original image content - works only on Apache and only with direct image URL

= 2.0 =
* Tested on WP 6.0
* convert and serve avif images anywhere - not only in wp-content folder
* option to delete original images after conversion

= 1.9.1 =
* Tested on WP 5.9

= 1.9 =
* Tested on WP 5.8
* added some nonce checks and more security validations
* better nginx instructions

= 1.8 =
* Tested on WP 5.7
* add more CURL options
* fix backslashes for localhosts

= 1.7 =
* Tested on WP 5.6
* fixed problem on some multisites

= 1.6 =
* Tested on WP 5.4
* added support for Fly Dynamic Image Resizer plugin

= 1.5 =
* notice when test image is not accessible

= 1.4 =
* new test method

= 1.3 =
* fixed text domain for translations

= 1.2 =
* added instructions for NGiNX

= 1.1 =
* make it works in multisite and subdirectory installs

= 1.0 =
* First version