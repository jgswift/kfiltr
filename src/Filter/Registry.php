<?php
namespace kfiltr\Filter {
    use qtil;
    
    class Registry extends qtil\Registry {
        /**
         * List of all filter delegates
         * @var array 
         */
        private static $delegates = [];
        
        /**
         * Retrieves filter delegate
         * @param mixed $object
         * @return \Closure
         */
        public static function getDelegate($object) {
            $uid = self::identify($object);
            
            if(array_key_exists($uid,self::$delegates)) {
                return self::$delegates[$uid];
            }
        }
        
        /**
         * sets delegate function for filter
         * @param mixed $object
         * @param callable $delegate
         */
        public static function setDelegate($object, callable $delegate) {
            $uid = self::identify($object);
            self::$delegates[$uid] = $delegate;
        }
        
        /**
         * Removes delegate function from filter
         * @param mixed $object
         * @param callable $delegate
         */
        public static function unsetDelegate($object) {
            $uid = self::identify($object);
            
            if(isset(self::$delegates[$uid])) {
                unset(self::$delegates[$uid]);
            }
        }
        
        /**
         * Checks if filter has delegate function
         * @param mixed $object
         * @return boolean
         */
        public static function hasDelegate($object) {
            $uid = self::identify($object);
            return array_key_exists($uid,self::$delegates);
        }
    }
}