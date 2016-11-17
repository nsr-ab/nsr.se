Modularity
==========

Modular component system plugin for WordPress. Drag and drop the bundled modules or your custom modules to your page layout.

Creating modules
----------------

To create your very own Modularity module you simply create a plugin with a class that extends our Modularity\Module class.

A module actually is the same as a custom post type. However we've added a few details to enable you to use them as modules.

Use the `$this->register()` method to create a very basic module.

Here's a very basic example module for you:

```php
/*
 * Plugin Name: Modularity Article Module
 * Plugin URI: -
 * Description: Article module for Modularity
 * Version: 1.0
 * Author: Modularity
 */

namespace MyArticleModule;

class Article extends \Modularity\Module
{
    public function __construct()
    {
        $id = 'article';
        $nameSingular = 'Article';
        $namePlural = 'Articles';
        $description = 'Outputs a full article with title and content';
        $supports = array('editor'); // All modules automatically supports title
        $icon = '[BASE-64 encoded svg data-uri]';
        $plugin = '/path/to/include-file.php' // CAn also be an array of paths to include 
        $cacheTTL = 60*60*24 //Time to live for fragment cache (stored in memcached). 

        $this->register(
            $id,
            $nameSingular,
            $namePlural,
            $description,
            $supports,
            $icon,
            $plugin,
            $cacheTTL
        );
    }
}

new \MyArticleModule\Article;
```

#### Module templates

You can easily create your own module templates by placing them in: `/wp-content/themes/[my-theme]/templates/module/`.

Name your template file with the following pattern: `modularity-[module-id].php`. You can get your module's id from the Modularity options page.

#### Module boilerplate

You can download our module boilerplate. It will be a good starting point for any custom module that you would like to build.

[Download it here (NOT AVAILABLE YET)](http://www.helsingborg.se)


Action reference
----------------

#### Modularity

> Runs when Modularity core is loaded. Typically used to add custom modules.

*Example:*
```php
add_action('Modularity', function () {
    // Do your thing
});
```

#### Modularity/Module/[MODULE SLUG]/enqueue

> Enqueue js or css only for the add and edit page of the specified module.

*Example:*

```php
add_action('Modularity/Module/mod-article/enqueue', function () {
    // Do your thing
});
```

#### Modularity/Options/Module

> Action to use for adding option fields to modularity options page.
> Use "Modularity/Options/Save" action to handle save of the option field added

*Example:*

```php
add_action('Modularity/Options/Module', function () {
    echo '<input type="text">';
});
```

Filter reference
----------------

#### Modularity/Editor/WidthOptions

> Filter module width options

*Params:*
```
$options      The default width options array ('value' => 'label')
```

*Example:*

```php
add_filter('Modularity/Editor/WidthOptions', function ($options) {
    // Do your thing
    return $filteredValue;
});
```

---

#### Modularity/Display/BeforeModule

> Filter module sidebar wrapper (before)

*Params:*
```
$beforeModule     The value to filter
$args             Arguments of the sidebar (ex: before_widget)
$moduleType       The module's type
$moduleId         The ID of the module
```

*Example:*
```php
add_filter('Modularity/Display/BeforeModule', function ($beforeModule, $args, $moduleType, $moduleId) {
    // Do your thing
    return $filteredValue;
});
```

---

#### Modularity/Display/AfterModule

> Filter module sidebar wrapper (after)

*Params:*
```
$afterModule      The value to filter
$args             Arguments of the sidebar (ex: before_widget)
$moduleType       The module's type
$moduleId         The ID of the module
```

*Example:*

```php
add_filter('Modularity/Display/AfterModule', function ($afterModule, $args, $moduleType, $moduleId) {
    // Do your thing
    return $filteredValue;
});
```

---

#### Modularity/Module/TemplatePath

> Modify (add/edit) paths where to look for module templates
> Typically used for adding search path's for finding custom modules templates.
> 
> *Attention: Unsetting paths may cause issues displaying modules. Plase do not do this unless you know exacly what you are doing.*

*Params:*
```
$paths      The value to filter
```

*Example:*
```php
add_filter('Modularity/Module/TemplatePath', function ($paths) {
    return $paths;
});
```

#### Modularity/Module/Classes

> Modify the list of classes added to a module's main element

*Params:*
```
$classes      The classes (array)
$moduleType   The module type
$sidebarArgs  The sidebar's args
```

*Example:*
```php
add_filter('Modularity/Module/Classes', function ($classes, $moduleType, $sidebarArgs) {
    $classes[] = 'example-class';
    return $classes;
});
```

#### Modularity/Display/Markup

> Module display markup

*Params:*
```
$markup      The markup
$module      The module post
```

*Example:*
```php
add_filter('Modularity/Display/Markup', function ($markup, $module) {
    return $markup;
});
```

#### Modularity/Display/[MODULE SLUG]/Markup

*Params:*
```
$markup      The markup
$module      The module post
```

*Example:*
```php
add_filter('Modularity/Display/Markup', function ($markup, $module) {
    return $markup;
});
```

#### Modularity/CoreTemplatesSearchTemplates

> What template files to look for

*Params:*
```
$templates
```

*Example:*
```php
add_filter('Modularity/CoreTemplatesSearchTemplates', function ($templates) {
    $templates[] = 'my-custom-template';
    return $templates;
});
```

