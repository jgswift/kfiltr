<?php
namespace kfiltr\Tests\Mock {
    use kfiltr;
    
    class FooFilter implements kfiltr\Interfaces\Filter {
        use kfiltr\Filter;
        
        public function execute() {
            return func_get_arg(0);
        }        
    }
}
