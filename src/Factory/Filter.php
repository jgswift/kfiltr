<?php
namespace kfiltr\Factory {
    use kfiltr;
    use qtil;
    
    trait Filter {
        use qtil\Factory, kfiltr\Filter;

        /**
         * Retrieves mapping for factory filter
         * @return mixed
         */
        function getMapping() {
            return Filter\Registry::getMapping($this);
        }

        /**
         * Sets mapping for factory filter
         * @param array|kfiltr\Filter\Mapping $mapping
         * @return kfiltr\Filter\Mapping
         */
        function setMapping($mapping) {
            if(is_array($mapping)) {
                $mapping = new kfiltr\Filter\Mapping($mapping);
            }
            
            if(!($mapping instanceof kfiltr\Filter\Mapping)) {
                throw new \InvalidArgumentException;
            }
            
            return Filter\Registry::setMapping($this, $mapping);
        }

        /**
         * Default execution path for factory filters
         * @param mixed $input
         * @param string $typeName
         * @return mixed
         */
        function execute($input,$typeName = null) {
            $mapping = $this->getMapping();
            
            if(is_null($typeName)) {
                $callback = $mapping->getNamingCallback();
                $typeName = $callback($input);
            }
            
            if(!is_string($typeName)) {
                throw new \InvalidArgumentException;
            }
            
            $inputMapping = null;
            
            if($mapping->exists($typeName)) {
                $inputMapping = $mapping[$typeName];
            }

            if(is_string($inputMapping)) {
                return self::build($inputMapping);
            }

            return $input;
        }
    }
}