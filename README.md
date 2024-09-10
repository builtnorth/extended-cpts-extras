# Extended CPTs Extras

Extended CPTs Extras is a companion package for the [Extended CPTs](https://github.com/johnbillion/extended-cpts) library, providing additional functionality for managing post types in WordPress.

## Features

1. **Featured Image Column Width**: Customize the width of the featured image column in the admin list view.
2. **Remove Meta Boxes**: Easily remove unwanted meta boxes from the post edit screen (useful for Gutenberg).
3. **Register Meta**: Register custom meta fields for your post types with support for the REST API.
4. **Modify Existing Post Types**: Add additional features to existing post types, including:
    - Custom templates
    - Template locking
    - Menu position
    - Menu icon

## Installation

1. Require the package in your `composer.json`:

    ```json
    {
        "require": {
            "builtnorth/extended-cpts-extras": "^1.0"
        }
    }
    ```

2. Run `composer install` or `composer update`.

## Usage

### Use the `extended_post_type_extras()` function to apply extra configurations to post types:

```php
extended_post_type_extras(['post', 'page'], [
	'featured_image_column_width' => 80,
	'remove_meta_boxes' => ['postcustom', 'commentstatusdiv'],
	'register_meta' => [
		'my_custom_field' => [
			'type' => 'string',
			'description' => 'A custom field for this post type',
			'single' => true,
			'show_in_rest' => true,
		],
	]
]);
```

### Use function `extended_post_type_modify_existing()` function to modify existing post types:

```php
extended_post_type_modify_existing('post', [
	'menu_icon' => 'dashicons-index-card',
	'menu_position' => 30,
	'template' => [
		['core/image', []],
	],

]);
```

## Functions

### extended_post_type_extras($post_types, $options)

Apply extra configurations to specified post types.

Parameters:

-   `$post_types`: String or array of post type names
-   `$options`: Array of configuration options
    -   `featured_image_column_width`: Set width for featured image column
    -   `remove_meta_boxes`: Array of meta box IDs to remove
    -   `register_meta`: Array of meta fields to register

### extended_post_type_modify_existing($post_types, $options)

Modify existing post types with additional features that are not available in the original Extended CPTs package. This function allows you to apply certain options to existing post types that normally only work when registering a new post type with `register_extended_post_type()`. Specifically, it enables you to modify the following attributes for existing post types:

-   `$post_types`: String or array of post type names
-   `$options`: Array of configuration options
    -   `template`: Custom template for the post type
    -   `template_lock`: Lock the template to prevent changes
    -   `menu_position`: Set the menu position
    -   `menu_icon`: Set the menu icon

## Contributing

Contributions are welcome. Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
