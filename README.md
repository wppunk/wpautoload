[![Coverage Status](https://coveralls.io/repos/github/mdenisenko/WP-Autoload/badge.svg)](https://coveralls.io/github/mdenisenko/WP-Autoload)[![CI/CD](https://github.com/mdenisenko/WP-Autoload/workflows/GitHub%20Actions/badge.svg)](https://github.com/mdenisenko/WP-Autoload)
# WordPress Autoload
Autoload for your classes, interfaces and traits in mu-plugins, plugins and themes.

## Rules

- Your namespace must begin with the prefix **My_**.
- Your class, interface or trait full name should be the same as the path to the directory:

### Examples:

1. For namespace `/My_Theme/Core/Test_Autoload/Some_Name` path will be `/wp-content/themes/my-theme/core/test-autoload/class-some-name.php`. 
2. For namespace `/My_Plugin/Core/Test_Autoload/Some_Name` path will be `/wp-content/plugins/my-plugin/core/test-autoload/interface-some-name.php`.

## How use?

```
composer require wppunk/wpautoload
```
And add to the `composer.json`:
```
{
    ...
    "extra": {
		"wp-autoload": {
			"\Name\Space\": "src"
		}
	},
    ...
}
```
Where key it is namespace and value it is the folder name. 
