<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Native Lazyload + Polyfill
 * Plugin URI:        https://nextgenthemes.com/plugins/native-lazyload-polyfill/
 * Description:       Adds native lazyloading to all images and iframes (embeds), also wraps them in noscript tags and adds a polyfill to make it work in all browsers.
 * Version:           1.1.0
 * Author:            Nicolas Jonas
 * Author URI:        https://nextgenthemes.com
 * License:           GPL 3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       native-lazyload-polyfill
 * Domain Path:       /languages
 */
namespace Nextgenthemes\NativeLazyloadPolyfill;

const VERSION         = '1.1.0';
const VERY_LATE       = PHP_INT_MAX - 5;
const PLUGIN_FILE     = __FILE__;
const PLUGIN_DIR      = __DIR__;
const POLYFILL_HANDLE = 'nextgenthemes-loading-attribute-polyfill';

init();

function init() {

	$ns = __NAMESPACE__;

	add_action( 'wp_enqueue_scripts', "$ns\\action_wp_enqueue_scripts" );
	add_filter( 'script_loader_tag', "$ns\\filter_script_loader_tag", 10, 2 );
	add_filter( 'the_content', "$ns\\filter_the_content", VERY_LATE );
	add_filter( 'get_avatar', "$ns\\filter_get_avatar", VERY_LATE, 6 );
	add_filter( 'embed_oembed_html', "$ns\\filter_embed_oembed_html", VERY_LATE, 3 );
	add_filter( 'post_thumbnail_html', "$ns\\filter_post_thumbnail_html", VERY_LATE, 5 );
	add_filter( 'wp_get_attachment_image_attributes', "$ns\\filter_wp_get_attachment_image_attributes", 10, 3 );
	add_filter(
		'plugin_action_links_' . plugin_basename( __FILE__ ),
		function( $links ) {

			$links['donate'] = sprintf(
				'<a href="https://nextgenthemes.com/donate/"><strong style="display: inline;">%s</strong></a>',
				esc_html__( 'Donate', 'native-lazyload-polyfill' )
			);

			return $links;
		}
	);
}

function action_wp_enqueue_scripts() {

	wp_register_script(
		POLYFILL_HANDLE,
		plugins_url( 'node_modules/loading-attribute-polyfill/loading-attribute-polyfill.min.js', PLUGIN_FILE ),
		array(),
		VERSION,
		false
	);

	wp_enqueue_script( POLYFILL_HANDLE );
}

function filter_script_loader_tag( $html, $handle ) {

	if ( POLYFILL_HANDLE === $handle ) {
		$html = str_replace( '<script ', '<script async="async" ', $html );
	}

	return $html;
}

function polyfill_html( $html, $eagerload_first = false ) {

	if ( ! is_string( $html ) || '' === trim( $html ) ) {
		return $html;
	}

	require_once PLUGIN_DIR . '/vendor/autoload.php';

	// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	$dom = new \IvoPetkov\HTML5DOMDocument();
	$dom->loadHTML( $html, \IvoPetkov\HTML5DOMDocument::ALLOW_DUPLICATE_IDS );

	$first_content_picture       = $eagerload_first ? true : false;
	$first_content_img_or_iframe = $eagerload_first ? true : false;

	$pictures = $dom->querySelectorAll( 'picture' );
	foreach ( $pictures as $key => $picture ) {

		if ( $first_content_picture ) {
			$first_content_picture = false;
			continue;
		}

		if ( ! $picture->querySelector( 'noscript.loading-lazy' ) ) {
			$picture->innerHTML = '<noscript class="loading-lazy">' . $picture->innerHTML . '</noscript>';
		}
	}

	$img_iframes = $dom->querySelectorAll( 'img, iframe' );
	foreach ( $img_iframes as $key => $node ) {

		if ( $first_content_img_or_iframe ) {
			$first_content_img_or_iframe = false;
			$node->setAttribute( 'loading', 'eager' );
			continue;
		}

		if ( ! $node->hasAttribute( 'loading' ) ) {
			$node->setAttribute( 'loading', 'lazy' );
		}

		if ( ! contains( $node->getNodePath(), 'noscript' ) ) {
			$node->outerHTML = '<noscript class="loading-lazy">' . $node->outerHTML . '</noscript>';
		}
	}

	$html = (string) $dom->querySelector( 'body' )->innerHTML;
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

	return $html;
}

function filter_the_content( $content ): string {
	$content = polyfill_html( $content, 'eagerload first image' );
	return $content;
}

function filter_embed_oembed_html( $html, $url, $attr ): string {
	$html = polyfill_html( $html );
	return $html;
}

function filter_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ): string {
	$html = polyfill_html( $html );
	return $html;
}

function filter_get_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ): string {
	$avatar = polyfill_html( $avatar );
	return $avatar;
}

// There seems to be no filter not html so we can't do the polyfill wrap here
function filter_wp_get_attachment_image_attributes( $attr, $attachment, $size ): array {

	$attr = (array) $attr;

	if ( ! empty( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}

	return $attr;
}

function contains( string $haystack, string $needle ): bool {
	return strpos( $haystack, $needle ) !== false;
}
