# Municipio 1.0 (for Helsingborg stad)

## Getting started
To get started you'll need to install the required npm packages. To install these components you will need to have Node.js installed on your system.

```
$ cd [THEME-DIR]
$ npm install
$ composer install
```

## Coding standards
For PHP, use PSR-2 and PSR-4 where applicable.

## Gulp
We use Gulp to compile, concatenate and minify SASS and JavaScript.
The compiling of SASS will also automatically add vendor-prefixes where needed.

To compile both js and sass and start the "watch" task run the following command from the theme directory:
```
$ gulp
```

## Constants

#### Block author pages
Author pages is blocked by default. To "unblock" add the following constant to wp-config (or other suitable place).

```
define('MUNICIPIO_BLOCK_AUTHOR_PAGES', false);
```

#### Load specific version of styleguide
Constants that lock version of the styleguide. Comes in handy when you want to enshure maximum stability of a site. 

```
define('STYLEGUIDE_VERSION', 1.0.32);
```

#### Load specific developement version of styleguide
Constant that load local verrsion of the styleguide. 

```
define('DEV_MODE', true);
```

## Actions

#### Municipio/blog/post_info

Blog post info header (single)

- ```@param object $post``` - The post object

```php
do_action('Municipio/author_display/name', $post);
```

## Filters

#### Municipio/theme/key
Filters the theme/styleguide asset key. 

- ```@param string $key``` - The key of the styleguide theme

```php
apply_filters('Municipio/theme/key', $key);
```


#### Municipio/GoogleAnalytics/ua
Filters the Google Analytics UA (user id) used to laod Google Analytics.

- ```@param string $ua``` - The Google Analytics UA

```php
apply_filters('Municipio/GoogleAnalytics/ua', $ua);
```

#### Municipio/author_display/name
Set the name of the author display

- ```@param string $name``` - Default name
- ```@param string $userId``` - The ID of the user

```php
apply_filters('Municipio/author_display/name', $name, $userId);
```

#### Municipio/author_display/title
Set the title label for the author name display

- ```@param string $title``` - Default title

```php
apply_filters('Municipio/author_display/title', $title);
```

#### Municipio/ajax_url_in_head
Set the ajax_url in the <head>

- ```@param string $ajax_url``` - Default ajax url

```php
apply_filters('Municipio/ajax_url_in_head', $ajax_url);
```

#### Municipio/favicon_sizes
Add sizes to theme options for favicon

- ```@param array $sizes``` - Default favicon sizes

```php
apply_filters('Municipio/favicon_sizes', $sizes);
```

#### Municipio/favicon_tag
Add sizes to theme options for favicon

- ```@param string $tag``` - The HTML tag(s)
- ```@param array $icon``` - The icon data

```php
apply_filters('Municipio/favicon_tag', $tag, $icon);
```

#### Municipio/header_grid_size
Applied to classes string for header sizes.

- ```@param string $classes``` - 

```php
apply_filters('Municipio/header_grid_size', $classes);
```


#### Municipio/mobile_menu_breakpoint
Applied to classes string for mobile hamburger menu breakpoint. 

- ```@param string $classes``` - The default site name

```php
apply_filters('Municipio/mobile_menu_breakpoint', $classes);
```


#### Municipio/logotype_text
Applied to the text that displays as the logo when now logotype image is uploaded in theme options.

- ```@param string $title``` - The default site name

```php
apply_filters('Municipio/logotype_text', $title);
```

#### Municipio/logotype_class
Applied to the logotype class attirbute

- ```@param array $classes``` - Default class(es)

```php
apply_filters('Municipio/logotype_class', $classes);
```

#### Municipio/logotype_tooltip
Applied to the logotype class attirbute

- ```@param string $tooltip``` - Default tooltip text

```php
apply_filters('Municipio/logotype_tooltip', $tooltip);
```

#### Municipio/blade/data
Applied to the blade template data. Can be used to send data to a Blade view.

- ```@param array $data``` - Dafault data

```php
apply_filters('Municipio/blade/data', $data);
```

#### Municipio/blade/template_types
Applied to the list of Blade template types.

- ```@param array $types``` - Dafault Blade template types

```php
apply_filters('Municipio/blade/template_types', $types);
```

#### Municipio/search_result/…
Multiple filters applied to the contents of a search result

- ```@param string $var``` - The content of the variable
- ```@param object $post``` - Post object

```php
apply_filters('Municipio/search_result/date', $date, $post);
apply_filters('Municipio/search_result/title', $title, $post);
apply_filters('Municipio/search_result/excerpt', $excerpt, $post);
apply_filters('Municipio/search_result/permalink_url', $permalink_url, $post);
apply_filters('Municipio/search_result/permalink_text', $permalink_text, $post);
```

#### Municipio/search_form/…
Filters applied to the search form

- ```@param string $var``` - The content of the variable

```php
apply_filters('Municipio/search_form/action', $url);
```

#### Municipio/archive/sort_keys
Modify the avaiable sorting keys for archives

- ```@param array $keys``` - The keys
- ```@param string $postType``` - The post type

```php
apply_filters('Municipio/archive/sort_keys', $keys, $postType);
```

#### Municipio/archive/date_filter
Modify the date filter WHERE clause

- ```@param string $where``` - The sql WHERE clause
- ```@param string $from``` - The "from" date from querystring
- ```@param string $to``` - The "to" date from querystring

```php
apply_filters('Municipio/archive/date_filter', $where, $from, $to);
```

#### Municipio/Breadcrumbs
Show/hide (true/false) breadcrumbs

- ```@param boolean $bool```- True or false (show or hide)

```php
apply_filters('Municipio/Breadcrumbs', $bool, get_queried_object())
```

#### Municipio/Breadcrumbs/Items
Filter the items/links in the breadcrumb

- ```@param array $items``` - The breadcrumb items

```php
apply_filters('Municipio/Breadcrumbs/Items', $items, get_queried_object());
```

#### Municipio/admin/editor_stylesheet
Change custom editor stylesheet

- ```@param string $url``` - The stylesheet url

```php
apply_filters('Municipio/admin/editor_stylesheet', $url);
```

#### Municipio/oembed/should_filter_markup
Decide if oembed markup should be filtered to HbgPrime video player (youtube and vimeo) or not.

- ```@param string $url``` - The resource url
- ```@param int $postId``` - Id of the current post

```php
apply_filters('Municipio/oembed/should_filter_markup', true, $url, $postId);
```

## Dev mode
To load assets from local styleguide. Set contant DEV_MODE to "true"

```php
define('DEV_MODE', true);
```
