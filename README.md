# Extended CPTs Extras

Extended CPTs Extras is a companion package for the [Extended CPTs](https://github.com/johnbillion/extended-cpts) library, providing additional functionality for managing post types in WordPress.

## Features

-   Apply extra configurations to multiple post types at once
-   Adjust the width of featured image columns in the admin interface
-   Remove specified meta boxes from post edit screens
-   Register custom meta fields for post types

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

Use the `extended_post_type_extras()` function to apply extra configurations to post types:

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

## Functions

### extended_post_type_extras($post_types, $options)

Apply extra configurations to specified post types.

Parameters:

-   `$post_types`: String or array of post type names
-   `$options`: Array of configuration options
    -   `featured_image_column_width`: Set width for featured image column
    -   `remove_meta_boxes`: Array of meta box IDs to remove
    -   `register_meta`: Array of meta fields to register

## Contributing

Contributions are welcome. Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
