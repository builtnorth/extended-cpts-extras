<?php

/**
 * ------------------------------------------------------------------
 * Add additional features to existing post types
 * ------------------------------------------------------------------
 *
 * @package ExtendedCPTsExtras
 * @since ExtendedCPTsExtras 1.0.0
 *
 * featured_image_column_width
 * remove_meta_boxes
 * register_meta
 * register_block_bindings
 */

/**
 * Add additional features to existing post types
 *
 * @param array $post_types
 * @param array $options
 */
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
						.post-type-{$post_type} .column-featured_image { width: {$width}px; }
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
							$callback = get_default_sanitize_callback($args['type']);
							// Only set sanitize_callback if we have a non-null value
							// WordPress will use its internal type-based sanitization for null
							if ($callback !== null) {
								$args['sanitize_callback'] = $callback;
							}
						}

						// Handle global meta registration (empty post_type)
						if ($post_type === '' || $post_type === null) {
							// Register for all post types using register_meta
							register_meta('post', $meta_key, $args);
						} else {
							// Register for specific post type
							register_post_meta($post_type, $meta_key, $args);
						}
					}
				};

				add_action('init', $register_meta);
				add_action('rest_api_init', $register_meta);
			}

			// Register block bindings
			if (!empty($options['register_block_bindings'])) {
				add_action('init', function () use ($post_type, $options) {
					foreach ($options['register_block_bindings'] as $source_name => $source_args) {
						// Merge with defaults
						$args = array_merge([
							'label' => $source_name,
							'get_value_callback' => null,
							'uses_context' => [],
						], $source_args);

						// If registering globally (empty post_type), the callback might need to handle all post types
						// If registering for specific post type, we can add post type context to the callback
						if (($post_type === '' || $post_type === null) && isset($args['get_value_callback'])) {
							// Global registration - callback should handle all post types
							register_block_bindings_source($source_name, $args);
						} else if ($post_type !== '' && $post_type !== null) {
							// Post type specific registration
							// Wrap the callback to ensure it only applies to the specified post type
							if (isset($args['get_value_callback']) && is_callable($args['get_value_callback'])) {
								$original_callback = $args['get_value_callback'];
								$args['get_value_callback'] = function($source_args, $block_instance) use ($post_type, $original_callback) {
									// Check if we're in the context of the specified post type
									$current_post_type = $block_instance->context['postType'] ?? get_post_type();
									if ($current_post_type === $post_type) {
										return call_user_func($original_callback, $source_args, $block_instance);
									}
									return '';
								};
							}
							register_block_bindings_source($source_name, $args);
						}
					}
				}, 20); // Priority 20 to ensure it runs after post types are registered
			}
		}
	}
}

/**
 * Get the default sanitize callback for the given type
 *
 * @param string $type
 * @return string|callable
 */
if (!function_exists('get_default_sanitize_callback')) {
	function get_default_sanitize_callback($type)
	{
		switch ($type) {
			case 'boolean':
				return 'rest_sanitize_boolean';
			case 'integer':
				return 'absint';
			case 'number':
				// For number type, use absint as most number fields in WordPress are IDs
				// If you need decimals, you should explicitly set a custom sanitize_callback
				return 'absint';
			case 'array':
				return 'rest_sanitize_array';
			default:
				return 'wp_kses_post';
		}
	}
}
