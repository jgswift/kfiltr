<?php
namespace kfiltr\Factory\Filter {
    use kfiltr, qtil;
    
    class Registry extends qtil\Registry {
        /**
         * List of factory filter mappings
         * @var array 
         */
        protected static $mappings = [];
        
        /**
         * Retrieves mapping definition for factory filter
         * @param mixed $object
         * @return kfiltr\Filter\Mapping
         */
        public static function getMapping($object) {
            $uid = self::identify($object);
            
            if(!isset(self::$mappings[$uid])) {
                self::$mappings[$uid] = new kfiltr\Filter\Mapping();
            }
            
            return self::$mappings[$uid];
        }
        
        /**
         * Sets mapping definition for factory filter
         * @param mixed $object
         * @param kfiltr\Filter\Mapping $mapping
         * @return kfiltr\Filter\Mapping
         */
        public static function setMapping($object, kfiltr\Filter\Mapping $mapping) {
            $uid = self::identify($object);
            
            return self::$mappings[$uid] = $mapping;
        }
    }
}
