<?php

/*
If you used Flickr to host some of the photos on your blog use this code to
download those images and use local copies instead.

Copy this chunk of commented out code somewhere where you can run it in a
WordPress environment. You might need to change the "uploads/flickr/"
directory to something else.
Once the files have downloaded to your server copy the replace_flickr_images()
function into a php file in wp-content/mu-plugins and fix the URL and path.
That function will search for any post that uses images hosted on Flickr and
replace them with local URLs.
*/

/*
mkdir( WP_CONTENT_DIR . '/uploads/flickr/' );
$flickr_urls = array( 'static.flickr.com', 'staticflickr.com' );
foreach( $flickr_urls as $url ) {
	$posts = $wpdb->get_results( "SELECT * FROM " . $wpdb->posts . " WHERE post_content LIKE '%" . $url . "%'" );
	foreach( $posts as $post ) {
		preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $post->post_content, $match);
		echo $post->ID . " <pre>" . print_r( $match, 1 ) . "</pre><br />";
		foreach( $match[0] as $url ) {
			if ( strpos( $url, "static.flickr.com" ) ) {
				$name = download_url( $url );
				$local_name = basename( parse_url( $url, PHP_URL_PATH ) );
				rename( $name, WP_CONTENT_DIR . '/uploads/flickr/' . $local_name );
				echo "name: " . $local_name . "<br />";
			} else {
				echo "static not found: $url<br />";
			}
		}
	}
}
die();
*/

function replace_flickr_images( $content ) {
	// https://stackoverflow.com/questions/36564293/extract-urls-from-a-string-using-php
	preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $content, $match);
	foreach( $match[0] as $url ) {
		if ( strpos( $url, "static.flickr.com" ) || strpos( $url, "staticflickr.com" ) ) {
			$local_name = basename( parse_url( $url, PHP_URL_PATH ) );
			$content = str_replace( $url, 'https://example.com/wp-content/uploads/flickr/' . $local_name, $content );
		}
	}
	return $content;
}
add_filter( 'the_content', 'replace_flickr_images' );
