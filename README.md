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

 require_once plugin_dir_path( __FILE__ ) . 'WP-Autoload/wp-autoload.php
```

## Customize
In file ```classes/class-autoload.php``` you can change:

- prefix your namespaces:

```php
private $prefix = 'My_New_Prefix_';
```

- folders for searching classes:

```php
private $folders = [
    WP_CONTENT_DIR . '/plugins/',
    WP_CONTENT_DIR . '/themes/',
];
```