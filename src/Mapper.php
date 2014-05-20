<?php
namespace kfiltr {
    /**
     * @method map
     */
    trait Mapper {
        use Filter;
        
        /**
         * Retrieves mapper factory
         * @return mixed
         */
        function getFactory() {
            return Mapper\Registry::getFactory($this);
        }

        /**
         * Sets mapper factory, must implement build method from qtil\Factory
         * @param mixed $factory
         * @return mixed
         */
        function setFactory($factory) {
            Mapper\Registry::setFactory($this, $factory);
            return $factory;
        }
        
        /**
         * Removes factory from registry
         * @param mixed $factory
         */
        function unsetFactory() {
            Mapper\Registry::unsetFactory($this);
        }

        /**
         * Checks if mapper has factory
         * @return boolean
         */
        function hasFactory() {
            return Mapper\Registry::hasFactory($this);
        }

        /**
         * Filter execute implementation for mapper
         * @param mixed $input
         * @param string $typeClass
         * @return mixed
         */
        function execute($input,$typeClass=null) {
            if(!is_array($input)) {
                $input = (array)$input;
            }
            
            $object = null;
            
            if($this->hasFactory()) {
                $factory = $this->getFactory();
                $object = $factory::build($typeClass);
                if(method_exists($this,'map')) {
                    $object = $this->map($input, $object);
                } else {
                    $mappingFn = Mapper\Registry::getDefaultMapper();
                    
                    $object = $mappingFn($input,$object);
                }
            }

            return $object;
        }
    }
}