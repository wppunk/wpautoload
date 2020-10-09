[![Coverage Status](https://coveralls.io/repos/github/mdenisenko/WP-Autoload/badge.svg)](https://coveralls.io/github/mdenisenko/WP-Autoload)[![CI/CD](https://github.com/mdenisenko/WP-Autoload/workflows/GitHub%20Actions/badge.svg)](https://github.com/mdenisenko/WP-Autoload)
# WordPress Autoload
Autoload for your classes, interfaces and traits by WordPress Coding Standard.

## How use?

```
composer require wppunk/wpautoload
```
Then add to the `composer.json`:
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

## Examples:

namespace `My_Plugin`

path `src` inside the plugin `my-plugin`.

Names for class, interface, trait:
```
wppunk\My_Plugin\Core\Awesome_Feature
wppunk\My_Plugin\Admin\Interface_Awesome_Feature
wppunk\My_Plugin\Front\Trait_Awesome_Feature
```

Paths:
```
.../wp-content/plugins/my-plugin/src/core/class-awesome-feature.php
.../wp-content/plugins/my-plugin/src/admin/interface-awesome-feature.php
.../wp-content/plugins/my-plugin/src/front/trait-awesome-feature.php
``` 
