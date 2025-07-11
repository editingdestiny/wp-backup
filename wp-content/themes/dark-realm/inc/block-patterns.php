<?php

/**
 * Registers block patterns and categories.
 *
 * @since 1.0
 *
 * @return void
 */
function dark_realm_register_block_patterns()
{
    $block_pattern_categories = array(
        'dark-realm' => array('label' => esc_html__('Dark Realm', 'dark-realm')),
        'pages'  => array('label' => esc_html__('Pages', 'dark-realm')),
    );

    /**
     * Filters the theme block pattern categories.
     *
     * @since 1.0
     *
     * @param array[] $block_pattern_categories {
     *     An associative array of block pattern categories, keyed by category name.
     *
     *     @type array[] $properties {
     *         An array of block category properties.
     *
     *         @type string $label A human-readable label for the pattern category.
     *     }
     * }
     */
    $block_pattern_categories = apply_filters('dark_realm_block_pattern_categories', $block_pattern_categories);

    foreach ($block_pattern_categories as $name => $properties) {
        if (!WP_Block_Pattern_Categories_Registry::get_instance()->is_registered($name)) {
            register_block_pattern_category($name, $properties);
        }
    }

    // Register block patterns from PHP files in the "share" directory
    $patterns_directory = get_stylesheet_directory() . '/patterns/';
    $pattern_files = scandir($patterns_directory);

    foreach ($pattern_files as $file) {
        $file_path = $patterns_directory . $file;

        // Check if it's a file and not a directory and if it's a PHP file
        if (is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) == 'php') {
            // Exclude pricing-table-2.php from registration
            if ($file !== 'pricing-table-2.php') {
                ob_start(); // Start output buffering to capture output from included file
                include $file_path;
                $pattern_content = ob_get_clean(); // Get captured output and clear buffer

                // Make sure the pattern content is not empty
                if (!empty($pattern_content)) {
                    // Get the pattern title from the file name
                    $pattern_title = str_replace('-', ' ', pathinfo($file, PATHINFO_FILENAME));
                    $pattern_title = ucwords($pattern_title); // Capitalize each word
                    $pattern_name = 'dark-realm-pattern-' . sanitize_title($pattern_title); // Generate a unique name for the pattern

                    register_block_pattern(
                        $pattern_name,
                        array(
                            'title' => sprintf(__('%s', 'dark-realm'), $pattern_title),
                            'content'     => $pattern_content,
                            'categories'  => array('dark-realm'), // Category under which your pattern should appear
                        )
                    );
                }
            }
        }
    }
}
add_action('init', 'dark_realm_register_block_patterns', 9);


?>