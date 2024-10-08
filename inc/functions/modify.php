<?php

/**
 * ------------------------------------------------------------------
 * Modify existing post types
 * ------------------------------------------------------------------
 *
 * @package ExtendedCPTsExtras
 * @since ExtendedCPTsExtras 1.1.0
 *
 * template
 * template_lock
 * menu_position
 * menu_icon
 */

/**
 * Modify existing post types
 *
 * @param array $post_types
 * @param array $options
 */
if (!function_exists('extended_post_type_modify_existing')) {
	function extended_post_type_modify_existing($post_types, $options = [])
	{
		$post_types = (array) $post_types;

		foreach ($post_types as $post_type) {
			add_action('init', function () use ($post_type, $options) {
				$post_type_object = get_post_type_object($post_type);
				if ($post_type_object) {
					// Template
					if (isset($options['template'])) {
						$post_type_object->template = $options['template'];
					}
					if (isset($options['template_lock'])) {
						$post_type_object->template_lock = $options['template_lock'];
					}

					// Menu position
					if (isset($options['menu_position'])) {
						$post_type_object->menu_position = $options['menu_position'];
					}

					// Menu icon
					if (isset($options['menu_icon'])) {
						$post_type_object->menu_icon = $options['menu_icon'];
					}
				}
			}, 11); // Priority 11 to ensure it runs after the post type is registered

			// Modify menu order
			if (isset($options['menu_position'])) {
				add_filter('custom_menu_order', '__return_true');
				add_filter('menu_order', function ($menu_order) use ($post_type, $options) {
					global $menu;

					// Find the current position of the post type menu item
					$current_position = array_search($post_type, $menu_order);

					if ($current_position !== false) {
						// Remove the item from its current position
						unset($menu_order[$current_position]);

						// Insert it at the new position
						array_splice($menu_order, $options['menu_position'], 0, $post_type);
					}

					return $menu_order;
				});
			}
		}
	}
}
