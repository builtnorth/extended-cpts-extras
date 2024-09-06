<?php

if (!function_exists('extended_post_type_extras')) {
	function extended_post_type_extras($post_types, $options = [])
	{
		// Convert single post type to array if necessary
		$post_types = (array) $post_types;

		foreach ($post_types as $post_type) {
			if (!empty($options['featured_image_width'])) {
				add_action('admin_head', function () use ($post_type, $options) {
					$width = $options['featured_image_width'];
					echo "<style>
						.post-type-{$post_type} .column-featured_image { width: {$width}px; }
						.post-type-{$post_type} .column-featured_image img { width: {$width}px; height: {$width}px; object-fit: cover; }
					</style>";
				});
			}

			if (!empty($options['remove_meta_boxes'])) {
				add_action('add_meta_boxes', function () use ($post_type, $options) {
					foreach ($options['remove_meta_boxes'] as $meta_box) {
						remove_meta_box($meta_box, $post_type, 'normal');
						remove_meta_box($meta_box, $post_type, 'side');
						remove_meta_box($meta_box, $post_type, 'advanced');
					}
				}, 20);
			}

			if (!empty($options['register_meta'])) {
				add_action('init', function () use ($post_type, $options) {
					foreach ($options['register_meta'] as $meta_key => $meta_args) {
						$args = array_merge([
							'object_subtype' => $post_type,
							'type' => 'string',
							'single' => true,
							'show_in_rest' => true,
						], $meta_args);

						register_meta('post', sanitize_key($meta_key), $args);
					}
				});
			}

			// Add more custom options handling here as needed
		}
	}
}
