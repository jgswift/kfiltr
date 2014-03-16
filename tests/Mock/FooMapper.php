<?php
namespace kfiltr\Tests\Mock {
    use kfiltr;
    
    class FooMapper implements kfiltr\Interfaces\Mapper {
        use kfiltr\Mapper;
        
        function __construct(FooFactory $factory) {
            $this->setFactory($factory);
        }
        
        function map($input,$object=null) {
            return $object;
        }
    }
}
