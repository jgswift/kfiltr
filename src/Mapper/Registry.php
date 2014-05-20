<?php
namespace kfiltr\Mapper {
    use qtil;
    
    class Registry extends qtil\Registry {
        /**
         * list of factories registered to mappers by uid
         * @var array
         */
        protected static $factories = [];
        
        /**
         * Retrieves factory for mapper
         * @param mixed $object
         * @return mixed
         */
        public static function getFactory($object) {
            $uid = self::identify($object);
            
            if(array_key_exists($uid, self::$factories)) {
                return self::$factories[$uid];
            }
        }
        
        /**
         * sets factory for mapper
         * @param mixed $object
         * @param mixed $factory
         */
        public static function setFactory($object,$factory) {
            $uid = self::identify($object);
            self::$factories[$uid] = $factory;
        }
        
        /**
         * Remove factory from mapper
         * @param mixed $object
         * @param mixed $factory
         */
        public static function unsetFactory($object) {
            $uid = self::identify($object);
            
            if(isset(self::$factories[$uid])) {
                unset(self::$factories[$uid]);
            }
        }
        
        /**
         * Check if mapper has factory assigned
         * @param mixed $object
         * @return boolean
         */
        public static function hasFactory($object) {
            $uid = self::identify($object);
            
            return array_key_exists($uid, self::$factories);
        }
        
        /**
         * Returns closure procedure to map objects if mapping method isnt otherwise provided 
         * @return closure
         */
        public static function getDefaultMapper() {
            $fn = function($input,$object) {
                foreach($input as $name => $value) {
                    $object->$name = $value;
                }
                
                return $object;
            };
            
            return $fn;
        }
    }
}