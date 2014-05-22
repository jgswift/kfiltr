kfiltr
====
PHP 5.5+ filtering pattern implementation 

[![Build Status](https://travis-ci.org/jgswift/kfiltr.png?branch=master)](https://travis-ci.org/jgswift/kfiltr)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jgswift/kfiltr/badges/quality-score.png?s=8f84c6df5bd73741f73c77f59924b100d91ebb17)](https://scrutinizer-ci.com/g/jgswift/kfiltr/)

## Installation

Install via [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/kfiltr:dev-master
```

## Usage

Kfiltr is a generic component that implements several traits for filtering, mapping, and hooking.
Kfiltr provides several interfaces which broadly describe the intended implementation criteria.
Kfiltr does not impose on your domain model and is meant to assist in the development of more specific components
that frequently rely on similar functionality.

### Filters

A minimal Filter example
```php
<?php
class MyFilter implements kfiltr\Interfaces\Filter {
    use kfilter\Filter;

    function execute() {
        return func_get_arg(0);
    }
}

$filter = new MyFilter();

var_dump($filter('foo')); // returns 'foo'
```

Any filter can easily be bypassed by a custom closure.

```php
<?php
$filter = new MyFilter();
$filter->setDelegate(function() {
    return 'bar';
});

var_dump($filter('foo')); // returns 'bar'
```

### Mappers

Similar to the Filter approach above, the Mapping approach is used specifically for populating already instantiated objects with input data

```php
<?php

class MyFactory {
    use qtil\Factory;
}

class MyMapper {
    use kfiltr\Mapper;

    function __construct(MyFactory $factory) {
        $this->setFactory($factory);
    }

    // do stuff to map object here.
    function map($input,$object) {
        return $object;
    }
}

class MyMiscClass {

}

$mapper = new MyMapper(new MyFactory());

$object = $mapper([],'MyMiscClass');

var_dump($object); // returns blank MyMiscClass object
```

If no ```map``` method is provided then a default mapping callback will be used.
The default mapping callback assumes that input is an array and object is an object respectively.

###Hooks

To facilitate the usage of filters and mappers, a standard hook implementation is provided

```php
<?php
// the same filter class from above
class MyFilter implements kfiltr\Interfaces\Filter {
    use kfilter\Filter;

    function execute() {
        return func_get_arg(0);
    }
}

class MyHook implements kfiltr\Interfaces\Filter, kfiltr\Interfaces\Hook {
    use kfiltr\Hook, kfiltr\Filter;

    // this will execute all filters in order and return an array containing all results
    function execute() {
        $filters = $this->getFilters();

        $results = [];
        if(!empty($filters)) {
            foreach($filters as $filter) {
                $results[] = call_user_func_array($filter,func_get_args());
            }
        }

        return $results;
    }
}

$filter = new MyFilter();

$hook = new MyHook();

$hook->addFilter($filter);

var_dump($hook('foo')); // returns [ 0 => 'foo' ]
```

### Factory Filter/Mapper
Additionally two factories are provided that take advantage of the filter and mapper abstractions outlined above.  No examples available yet, please consult unit tests for implementation details.