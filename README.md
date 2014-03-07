# AnhTaggableBundle

Bundle which provides integration of [doctrine-extensions-taggable](https://github.com/hilobok/doctrine-extensions-taggable), adds form types for editing tag and tagging.

## Installation

Install via composer with command:

```bash
$ php composer.phar require 'anh/taggable-bundle:~1.0'
```

Enable bundles in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sp\BowerBundle\SpBowerBundle(),
        new Anh\TaggableBundle\AnhTaggableBundle()
    );

    // ...
}
```

Install dependencies:

```bash
$ app/console sp:bower:install
```

Bundle uses [SpBowerBundle](https://github.com/Spea/SpBowerBundle) for manage external assets, so installed [bower](http://bower.io) is required.

Update schema:

```bash
$ app/console doctrine:schema:update --force
```

## Example

### Building form

```php
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...
        $builder
            ->add('tags', 'tags', array(
                'tagit' => array(/* ... */) // see https://github.com/hilobok/tag-it for available options, may be empty
                'autocomplete' => 'dynamic' // default
            ))
        ;
        // ...
    }
```

`autocomplete` option:

* `dynamic` - tags fetched dynamicaly via ajax (you may set custom url in `['tagit']['autocomplete']['source']`) (limited to 100 tags)
* `static`  - all tags passed via attribute (limited to 100 tags)
* `custom`  - no tags will be provided from db (custom list of tags may be passed by setting `['tagit']['availableTags']`)

### Rendering form

```html
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    {% stylesheets
        '@anh_taggable_css'
    %}<link rel="stylesheet" href="{{ asset_url }}" />{% endstylesheets %}

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    {% javascripts
        '@anh_taggable_js'
    %}<script src="{{ asset_url }}"></script>{% endjavascripts %}
</head>
<body>
    {{ form(form) }}
</body>
</html>
```

![Example](https://raw.github.com/hilobok/AnhTaggableBundle/master/Resources/doc/example.png)

#### Note

Bundle automatically adds mapping for Tag and Tagging entities from [doctrine-extensions-taggable](https://github.com/hilobok/doctrine-extensions-taggable). If you for any reason have to disable it put in config.yml:

```yml
doctrine:
    orm:
        mappings:
            anh_taggable: { mapping: false }
```