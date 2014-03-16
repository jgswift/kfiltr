<?php
namespace kfiltr {
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
        function unsetFactory($factory) {
            Mapper\Registry::unsetFactory($this,$factory);
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
                    $input = $this->map($input, $object);
                } else {
                    $mappingFn = Mapper\Registry::getDefaultMapper();
                    
                    $object = $mappingFn($input,$object);
                }
            }

            return $object;
        }
    }
}