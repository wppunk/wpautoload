[![Coverage Status](https://coveralls.io/repos/github/mdenisenko/WP-Autoload/badge.svg)](https://coveralls.io/github/mdenisenko/WP-Autoload)[![CI/CD](https://github.com/mdenisenko/WP-Autoload/workflows/GitHub%20Actions/badge.svg)](https://github.com/mdenisenko/WP-Autoload)
# WordPress Autoload
Autoload for your classes, interfaces and traits in mu-plugins, plugins and themes.

## Rules

- Your namespace must begin with the prefix **My_**.
- Your class, interface or trait full name should be the same as the path to the directory:

### Examples:

1. For namespace ```/My_Theme/Core/Test_Autoload/Some_Name``` path will be ```/wp-content/themes/my-theme/core/test-autoload/class-some-name.php```. 
2. For namespace ```/My_Plugin/Core/Test_Autoload/Some_Name`` path will be ``/wp-content/plugins/my-plugin/core/test-autoload/interface-some-name.php```.

## How use?

```
cd /path/to/wp-content/mu-plugins/
git clone https://github.com/mdenisenko/WP-Autoload.git
```

Then create a file in the mu-plugins folder to connect this plugin, use the bash script:

```
cd WP-Autoload/bash/
bash create-bootstrap-file.sh
```

or create it yourself:

```php
<?php
/**
 * WP-Autoload
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

require_once plugin_dir_path( __FILE__ ) . 'WP-Autoload/wp-autoload.php';
```

## Customize

To change the prefix, use the constant: `WP_AUTOLOAD_PREFIX` type string.

To change the folders, use the constant: `WP_AUTOLOAD_FOLDERS` type array.

Example:

```php
define( 'WP_AUTOLOAD_PREFIX', 'Custom_' );
define( 'WP_AUTOLOAD_FOLDERS', [ WP_CONTENT_DIR . '/plugins/' ] );
```
