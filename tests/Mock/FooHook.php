<?php
namespace kfiltr\Tests\Mock {
    use kfiltr;
    
    class FooHook implements kfiltr\Interfaces\Filter, kfiltr\Interfaces\Hook {
        use kfiltr\Hook, kfiltr\Filter;
        
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
}
