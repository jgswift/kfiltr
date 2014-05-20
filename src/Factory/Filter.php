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
         * @param kfiltr\Filter\Mapping $mapping
         * @return kfiltr\Filter\Mapping
         */
        function setMapping(kfiltr\Filter\Mapping $mapping) {
            return Filter\Registry::setMapping($this, $mapping);
        }

        /**
         * Default execution path for factory filters
         * @param mixed $input
         * @param string $typeName
         * @return mixed
         */
        function execute($input,$typeName) {
            $mapping = $this->getMapping()->toArray();
            
            $inputMapping = null;
            if(array_key_exists($typeName,$mapping)) {
                $inputMapping = $mapping[$typeName];
            }

            if(is_string($inputMapping)) {
                $object = self::build($inputMapping);
                
                if(method_exists($this,'map')) {
                    $object = $this->map($input, $object);
                } else {
                    $mappingFn = kfiltr\Mapper\Registry::getDefaultMapper();
                    
                    $object = $mappingFn($input,$object);
                }
                
                return $object;
            }

            return $input;
        }
    }
}