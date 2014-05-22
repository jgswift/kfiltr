<?php
namespace kfiltr\Filter {
    use qtil;
    
    class Mapping extends qtil\Collection {
        
        /**
         * Stores a function that determines how to name
         * and input in a way that will allow that input
         * to be mapped to classes
         * @var \Closure 
         */
        protected $namingCallback;
        
        /**
         * Default mapping constructor
         * @param array $mapping
         * @param \Closure $namingCallback
         */
        function __construct() {
            $args = func_get_args();
            
            $data = [];
            if(func_num_args()) {
                foreach($args as $a) {
                    if(qtil\ArrayUtil::isIterable($a)) {
                        $data = $a;
                    } elseif(is_callable($a)) {
                        $this->setNamingCallback($a);
                    }
                }
            }
            
            parent::__construct($data);
        }
        
        /**
         * Very general naming convention to simply attempt to stringify input
         * @return \Closure
         */
        function getDefaultNamingCallback() {
            return function($input) {
                return (string)$input;
            };
        }
        
        /**
         * Retrieves closure associated with identifying input
         * @return \Closure
         */
        function getNamingCallback() {
            if(!is_callable($this->namingCallback)) {
                $this->namingCallback = $this->getDefaultNamingCallback();
            }
            
            return $this->namingCallback;
        }
        
        /**
         * Update \Closure that performs input identification
         * @param callable $callback
         * @return \Closure
         */
        function setNamingCallback(callable $callback) {
            return $this->namingCallback = $callback;
        }
    }
}