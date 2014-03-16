kfiltr
====
PHP 5.5+ filtering pattern implementation

[![Build Status](https://travis-ci.org/jgswift/kfiltr.png?branch=master)](https://travis-ci.org/jgswift/kfiltr)

## Installation

Install via [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/kfiltr:dev-master
```

## Usage

Kfiltr provides a set of traits and interfaces which implement general filtering and mapping patterns

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

Any filter can easily be interceded by a custom closure.

```php
<?php
$filter = new MyFilter();
$filter->setDelegate(function() {
    return 'bar';
});

var_dump($filter('foo')); // returns 'bar'
```

Similar to the Filter above, the Mapping pattern implementation is used specifically for building objects through qtil\Factory

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

    // do stuff to map object here. if this method isn't provided, a default mapping procedure is used
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

Note: there are a couple of other filter and mapping implementations provided, please consult unit tests for implementation details