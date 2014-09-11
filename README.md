kfiltr
====
PHP 5.5+ filtering pattern implementation 

[![Build Status](https://travis-ci.org/jgswift/kfiltr.png?branch=master)](https://travis-ci.org/jgswift/kfiltr)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jgswift/kfiltr/badges/quality-score.png?s=8f84c6df5bd73741f73c77f59924b100d91ebb17)](https://scrutinizer-ci.com/g/jgswift/kfiltr/)
[![Latest Stable Version](https://poser.pugx.org/jgswift/kfiltr/v/stable.svg)](https://packagist.org/packages/jgswift/kfiltr)
[![License](https://poser.pugx.org/jgswift/kfiltr/license.svg)](https://packagist.org/packages/jgswift/kfiltr)
[![Coverage Status](https://coveralls.io/repos/jgswift/kfiltr/badge.png?branch=master)](https://coveralls.io/r/jgswift/kfiltr?branch=master)

## Installation

Install via cli using [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/kfiltr:0.1.*
```

Install via composer.json using [composer](https://getcomposer.org/):
```json
{
    "require": {
        "jgswift/kfiltr": "0.1.*"
    }
}
```

## Description

Kfiltr provides a set of generic traits that handle filtering, mapping, and hooking in a domain-agnostic way.  
Interfaces are also provided to broadly describe the intended implementation, however they are required to use this package.

## Dependency

* php 5.5+

## Usage

### Filters

A minimal Filter example.  The execute method does not enforce any particular signature and may define whatever arguments needed
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

#### Delegate

Effectively a filter is the same except that filters can be bypassed by a custom callable.

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
Using a mapper requires [jgswift/qtil](http://github.com/jgswift/qtil)

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

### Hooks

To assist filter processing, a standard hook implementation is provided.
Hooks are containers for multiple filters that typically run sequentially.
Hooks may also be filters, but are not by default.  In the example below, 
the hook will iterate over and perform all contained filtering operations. 

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

#### Filter

Using this filter requires [jgswift/qtil](http://github.com/jgswift/qtil)

Factory filters make objects using a class map.  Arguments are passed to the constructor

```php
namespace Creatures {
    class Animal { 
        function __construct($species) { /* ... */ }
    }

    class Human extends Animal {
        function __construct($ethnicity) { /* ... */ }
    }
}

class MyFilter {
    use kfiltr\Factory\Filter;
}

// specific class names keyed by an id
$mapping = [
    'animal' => 'Creatures\Animal',
    'human' => 'Creatures\Human'
];

$filter = new MyFilter(); // create filter
$filter->setMapping($mapping); // apply mapping

$animal = $filter(['cat'],'animal'); // create animal
$human = $filter(['polish'],'human');// create human

var_dump(get_class($animal));   // Creatures\Animal
var_dump(get_class($human));    // Creatures\Human
```

#### Mapper

This filter makes object using a factory and maps the properties using kfiltr\Mapper.
Arguments are mapped and the constructor must be empty for mapped classes.
To map objects with non-empty constructors, a custom factory is required.
Using this filter requires [jgswift/qtil](http://github.com/jgswift/qtil)

```php
namespace Creatures {
    class Animal { 
        function __construct($species) { /* ... */ }
    }

    class Human extends Animal {
        function __construct($ethnicity) { /* ... */ }
    }
}

class MyFactory {
    use qtil\Factory;
}

class MyMapper {
    use kfiltr\Factory\Mapper;
}

$mapping = [
    'animal' => 'Creatures\Animal',
    'human' => 'Creatures\Human'
];

$mapper = new MyMapper();
$mapper->setFactory(new MyFactory);
$mapper->setMapping($mapping);

$caucasian = $mapper(['ethnicity'=>'caucasian'],'human');
$indian = $mapper(['ethnicity'=>'indian'],'human');
$elephant = $mapper(['species'=>'elephant'],'animal');

var_dump($caucasian->ethnicity);
var_dump($indian->ethnicity);
var_dump($elephant->species);
```
