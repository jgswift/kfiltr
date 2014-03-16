<?php
namespace kfiltr\Hook {
    use qtil;
    
    class Registry extends qtil\Registry {
        /**
         * Multidimensional array of hook filters keyed by uid
         * @var type 
         */
        protected static $filters = [];
        
        /**
         * Retrieves all filters assigned to hook
         * @param mixed $object
         * @return array
         */
        public static function getFilters($object) {
            $uid = self::identify($object);
            if(array_key_exists($uid, self::$filters)) {
                return self::$filters[$uid];
            }
        }
        
        /**
         * Replaces/Assigns list of filters to hook
         * @param mixed $object
         * @param array $filters
         */
        public static function setFilters($object,array $filters) {
            $uid = self::identify($object);
            self::$filters[$uid] = $filters;
        }
        
        /**
         * Adds filter to list of filters for hook
         * @param mixed $object
         * @param mixed $filter
         */
        public static function addFilter($object,$filter) {
            $uid = self::identify($object);
            
            if(!isset(self::$filters[$uid])) {
                self::$filters[$uid] = [];
            }
            
            if(is_array($filter) && !is_callable($filter)) {
                self::$filters[$uid] = array_merge(self::$filters[$uid],$filter);
            } else {
                self::$filters[$uid][] = $filter;
            }
        }
        
        /**
         * Removes filter from hook
         * @param mixed $object
         * @param mixed $filter
         */
        public static function removeFilter($object,$filter) {
            $key = array_search($filter,self::$filters);
            
            if($key) {
                unset(self::$filters[$key]);
            }
        }
        
        /**
         * Clears all filters for hook
         * @param mixed $object
         */
        public static function clearFilters($object) {
            $uid = self::identify($object);
            
            self::$filters[$uid] = [];
        }
        
        /**
         * Check if hook has any filters
         * @param mixed $object
         * @return boolean
         */
        public static function hasFilters($object) {
            $uid = self::identify($object);
            
            return array_key_exists($uid,self::$filters);
        }
    }
}