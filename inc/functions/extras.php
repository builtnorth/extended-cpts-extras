<?php

if (!function_exists('extended_post_type_extras')) {
	function extended_post_type_extras($post_types, $options = [])
	{
		$post_types = (array) $post_types;

		foreach ($post_types as $post_type) {
			// Handle featured image width
			if (!empty($options['featured_image_column_width'])) {
				add_action('admin_head', function () use ($post_type, $options) {
					$width = $options['featured_image_column_width'];
					echo "<style>
						.post-type-{$post_type} .column-featured_image { width: {$width}; }
						.post-type-{$post_type} .column-featured_image img {  aspect-ratio: 4 / 3; object-fit: cover; }
					</style>";
				});
			}

			// Remove meta boxes
			if (!empty($options['remove_meta_boxes'])) {
				add_action('add_meta_boxes', function () use ($post_type, $options) {
					foreach ($options['remove_meta_boxes'] as $meta_box) {
						remove_meta_box($meta_box, $post_type, 'normal');
						remove_meta_box($meta_box, $post_type, 'side');
						remove_meta_box($meta_box, $post_type, 'advanced');
					}
				}, 20);
			}

			// Register meta
			if (!empty($options['register_meta'])) {
				$register_meta = function () use ($post_type, $options) {
					foreach ($options['register_meta'] as $meta_key => $meta_args) {
						$args = array_merge([
							'show_in_rest' => true,
							'single' => true,
							'type' => 'string',
							'description' => '',
						], $meta_args);

						if (!isset($args['sanitize_callback'])) {
							$args['sanitize_callback'] = get_default_sanitize_callback($args['type']);
						}

						register_post_meta($post_type, $meta_key, $args);
					}
				};

				add_action('init', $register_meta);
				add_action('rest_api_init', $register_meta);
			}
		}
	}
}

if (!function_exists('get_default_sanitize_callback')) {
	function get_default_sanitize_callback($type)
	{
		switch ($type) {
			case 'boolean':
				return 'rest_sanitize_boolean';
			case 'integer':
				return 'absint';
			case 'number':
				return 'floatval';
			case 'array':
				return 'rest_sanitize_array';
			default:
				return 'sanitize_text_field';
		}
	}
}
