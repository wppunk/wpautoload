# WPForms Handbook

Let's get things rolling :)

**Other Handbooks to study:**

- [Human Made](https://engineering.hmn.md/)
- [10Up](https://10up.github.io/Engineering-Best-Practices/)

## Table of Contents

- [Property or method access](#property-or-method-access)
- [Naming Things](#naming-things)
- [Optimizing Queries](#optimizing-queries)
- ["Exit Early" Strategy](#exit-early-strategy)
- [Unused Variables and Method Arguments](#unused-variables-and-method-arguments)
- [PhpStorm Language Injection Annotations](#phpstorm-language-injection-annotations)
- [Security techniques](#security-techniques)

## Access modifiers

Whenever we introduce a new property or method, here’s what we should keep in mind about access modifiers.

First of all, any method with public modifier (that's applied by default) appears in your object or class’s public
interface. Anything public is a *lifetime commitment*. Once it’s there, people may start using it directly even if we
didn’t expect them to. And with that large user base, it will happen. A lot. So we will have to support it and make sure
we don’t break it. We can’t change a method signature or return type without introducing a breaking change. So the rule
of thumb is - think of the class’s public API, what behavior it exposes to the outside world. If the method you're about
to add is not part of a this public API, mark it as `private`. Private APIs are easy to change whenever we need or want.

- Properties should be `private`. Public properties are bad, period. They allow the object state to be mutated from the
  outside. Things may blow up. Things will blow up. Get yourself a getter, save the planet ;)
- If you need access to a property from outside, create a getter method named `get_{$property_name}`
- Don’t weaken the access because "we might need it in the future". Only if/when you encounter a specific case where a
  subclass needs to access parent class’ property or method - then change it to `protected`. The mere fact that
  it’s `private` makes this safe and easy.

## Naming Things

- functions / methods
- variables / properties

2bd...

## Optimizing Queries

- limit + no pagination unless needed
- no found rows (saves 1 slow SQL query)
- do not cache terms and meta unless needed (saves 2 SQL queries)
- what else?

2bd...

## "Exit Early" Strategy

2bd...

## Unused Variables and Method Arguments

We should not introduce unused variables. IDEs are usually good at helping to avoid this issue.

But there is one exception, when working with hooks (both actions and filters). Consider this example:

```php
do_action( 'test', $one, $two, $three );

// Later in the code.
add_action( 'test', 'process_test', 42, 3 );

// And the callback.
function process_test( $one, $two, $three ) {

    echo $one;
}
```

Technically in the `process_test()` function, you can remove the last 2 arguments because they are not used. The code
will continue to work, with only one argument passed to the function and available for a developer to work with. But
this approach will effectively hide the existence of those 2 other parameters (`$two` & `$three`) that have been passed
via a `do_action()` call.

So we prefer not to remove arguments in this case inside the `process_test()` function. It's a general reminder about
the data available for developers while inside this method which is part of the hook. These arguments may not be used at
the moment, but in future they may get utilised and keeping them helps with discoverability.

## PhpStorm Language Injection Annotations

PhpStorm has
special [language injection annotations](https://www.jetbrains.com/help/phpstorm/using-language-injections.html#use-lang-annotation)
that turn on syntax highlighting, inspections and other features for a code that's in PHP string literal. This helps
prevent errors in HTML, CSS, JavaScript, SQL etc code mixed in within PHP and makes the code more readable.

However, it conflicts with PHPCS rules that require either a short description for `@lang <language_ID>` format or a
period for `// language=<language_ID>` format - and both changes break the annotation. So the simplest way to make it
work is to add a short description ending with a period to the latter:

```php
wp_add_inline_style(
	'twenty-twenty-one-style',
	// language=CSS PhpStorm.
	'.wpforms-container .wpforms-field input[type=checkbox],
	.wpforms-container .wpforms-field input[type=radio] {
		width: 25px;
		height: 25px;
	}'
);
```

## Security Techniques

### Early sanitize

> Sanitization is the process of cleaning or filtering your input data. Whether the data is from a user or an API or web service, you use sanitizing when you don’t know what to expect or you don’t want to be strict with data validation.

We should sanitize **user input as soon as possible**. User input in PHP is the list of super global variables (`$_POST`
, `$_GET`, `$_REQUEST`, `$_COOKIE`, `$_SESSION`, etc.)

Use one of these functions:

<details>
  <summary>Sanitize PHP functions:</summary>
- `filter_input`
- `filter_var`
- `number_format`
</details>

<details>
  <summary>Sanitize WordPress functions:</summary>

- `_wp_handle_upload`
- `esc_url_raw`
- `hash_equals`
- `is_email`
- `sanitize_bookmark_field`
- `sanitize_bookmark`
- `sanitize_email`
- `sanitize_file_name`
- `sanitize_hex_color_no_hash`
- `sanitize_hex_color`
- `sanitize_html_class`
- `sanitize_meta`
- `sanitize_mime_type`
- `sanitize_option`
- `sanitize_sql_orderby`
- `sanitize_term_field`
- `sanitize_term`
- `sanitize_text_field`
- `sanitize_textarea_field`
- `sanitize_title_for_query`
- `sanitize_title_with_dashes`
- `sanitize_title`
- `sanitize_user_field`
- `sanitize_user`
- `validate_file`
- `wp_handle_sideload`
- `wp_handle_upload`
- `wp_kses_allowed_html`
- `wp_kses_data`
- `wp_kses_post`
- `wp_kses`
- `wp_parse_id_list`
- `wp_redirect`
- `wp_safe_redirect`
- `wp_sanitize_redirect`
- `wp_strip_all_tags`

</details>

Pay attention to our own sanitizing functions:
<details>
  <summary>Sanitize WPForms functions:</summary>
- `wpforms_sanitize_richtext_field`
- `wpforms_sanitize_amount`
- `wpforms_sanitize_array_combine`
- `wpforms_sanitize_classes`
- `wpforms_sanitize_error`
- `wpforms_sanitize_hex_color`
- `wpforms_sanitize_key`
- `wpforms_sanitize_textarea_field`
- `wpforms_sanitize_text_deeply`
</details>

One more crucial point is the `wp_unslash` function. Before sanitize you should remove slashes. WordPress adds slashes
for some characters to protect code injections and XSS vulnerabilities. You can diving into the `wp_magic_quotes`
function. So, even if you send data without slashes you will get data with slashes. Removing slashes is a required step
before sanitizing.

Skip this point you can if you're going to sanitize the data via slash-free functions:

<details>
  <summary>List of functions that don't need `wp_unslash`:</summary>
- `absint`
- `boolval`
- `count`
- `doubleval`
- `floatval`
- `intval`
- `sanitize_key`
- `sizeof`
</details>

### Work with database

**Sanitizing doesn't protect your SQL queries from injections.**

A few ways how to make an SQL-injection:

- Multi-queries (close the previous query with semicolon(`;`) and write one more) 
  <details>
  <summary>Multi-queries example:</summary>
  PHP code:

  ```php
  $search = $_POST['search'];
  
  global $wpdb;
  
  $posts = $wpdb->get_results(
      'SELECT ID, post_title, post_content FROM ' . $wpdb->posts . '
      WHERE post_type="post"
      AND post_content LIKE "%' . $search . '%"'
  );
  
  wp_send_json_success( $posts );
  ```
  
  Input data:
  
  ```sql
  %"; SELECT * FROM wp_users WHERE ID LIKE "%
  ```
  
  SQL result:
  
  ```sql
  SELECT ID, post_title, post_content
  FROM wp_posts
  WHERE post_type = "post"
    AND post_content LIKE "%%";
  SELECT *
  FROM wp_users
  WHERE ID LIKE "%%"
  ```
  </details>
- Subqueries(use a subquery as a value for the main query)
  <details>
  <summary>Subqueries example:</summary>
  PHP Code:

  ```php
  $ids = $_POST['ids'];
  
  global $wpdb;
  
  $posts = $wpdb->get_results(
      'SELECT post_title FROM ' . $wpdb->posts . '
      WHERE ID IN (' . $ids . ')'
  );
  
  wp_send_json_success( $posts );
  ```
  
  Input data:
  
  ```sql
  %" OR post_title LIKE "%
  ```
  
  SQL result:
  
  ```sql
  SELECT ID, post_title, post_content
  FROM wp_posts
  WHERE post_type = "post"
      AND post_content LIKE "%%"
     OR post_title LIKE "%%"
  ```
  </details>
- Quotes(by closing a quote and adding code after)
  <details>
  <summary>Quotes example:</summary>
  PHP Code:

  ```php
  $search = $_POST['search'];
  
  global $wpdb;
  
  $posts = $wpdb->get_results(
      'SELECT ID, post_title, post_content FROM ' . $wpdb->posts . '
      WHERE post_type="post"
      AND post_content LIKE "%' . $search . '%"'
  );
  
  wp_send_json_success( $posts );
  ```
  
  Input data:
  
  ```sql
  %" OR post_title LIKE "%
  ```
  
  SQL result:
  
  ```sql
  SELECT ID, post_title, post_content
  FROM wp_posts
  WHERE post_type = "post"
      AND post_content LIKE "%%"
     OR post_title LIKE "%%"
  ```
  </details>
- The UNION statement (add a new query via UNION SELECT ...)
  <details>
  <summary>Quotes example:</summary>
  PHP Code:

  ```php
  $search = $_POST['search'];
  
  global $wpdb;
  
  $posts = $wpdb->get_results(
  	'SELECT ID, post_title, post_content FROM ' . $wpdb->posts . '
  	WHERE post_type="post"
  	AND post_content LIKE "%' . $search . '%"'
  );
  
  wp_send_json_success( $posts );
  ```
  
  Input data:
  
  ```sql
  %" UNION SELECT 0, user_pass, user_login FROM wp_users WHERE user_pass LIKE "%
  ```
  
  SQL result:
  
  ```sql
  SELECT ID, post_title, post_content
  FROM wp_posts
  WHERE post_type = "post"
    AND post_content LIKE "%%"
  UNION
  SELECT 0, user_pass, user_login
  FROM wp_users
  WHERE user_pass LIKE "%%"
  ```
  </details>

1. To protect your code from multi-queries SQL injection you must always use `wpdb` class for working with custom
   queries. `php-mysql` and `php-mysqli` extensions allow running multi-queries only using a special function for it,
   but the `wpdb` doesn't use the function.
2. To protect your code from all other types (subqueries, the UNION statement, and quotes) you need to:
    1. use the `wpdb::prepare` method. The method wraps all string into quotes and adds additional slashes for replaced
       value. However, even if someone passes an SQL code we'll get an absolutely safe SQL query.
    2. use special methods for creating, updating, deleting (`wpdb::insert`, `wpdb::update` `wpdb::replace`
       , `wpdb::delete`, etc.).

### Late escape

> Escaping is the process of securing output by stripping out unwanted data, like malformed HTML or script tags, preventing this data from being seen as code.
>
> Escaping helps secure your data prior to rendering it for the end user and prevents XSS (Cross-site scripting) attacks.

We should escape **all output as late as possible**. Output means any code that we're going to display via `echo`
, `print`, `printf`, etc. functions.

Why do we need to escape all output data?

Firstly, many sanitizing functions don't remove HTML tags, scripts, and executable JS code (XSS). Secondly, we work on
the plugin that is only a tiny part of a WordPress website, and we can't ensure that other plugins/themes work as well.
And the last, to break an HTML tags super-duper easy.

<details>
  <summary>Escaping functions:</summary>
- `absint`
- `esc_attr__`
- `esc_attr_e`
- `esc_attr_x`
- `esc_attr`
- `esc_html__`
- `esc_html_e`
- `esc_html_x`
- `esc_html`
- `esc_js`
- `esc_sql`
- `esc_textarea`
- `esc_url_raw`
- `esc_url`
- `filter_input`
- `filter_var`
- `floatval`
- `highlight_string`
- `intval`
- `json_encode`
- `like_escape`
- `number_format`
- `rawurlencode`
- `sanitize_hex_color`
- `sanitize_hex_color_no_hash`
- `sanitize_html_class`
- `sanitize_key`
- `sanitize_user_field`
- `tag_escape`
- `urlencode_deep`
- `urlencode`
- `wp_json_encode`
- `wp_kses_allowed_html`
- `wp_kses_data`
- `wp_kses_post`
- `wp_kses`
</details>
