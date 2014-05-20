<?php
namespace kfiltr\Factory {
    use kfiltr;
    
    trait Mapper {
        use kfiltr\Mapper;

        /**
         * retrieve factory mapping
         * @return kfiltr\Filter\Mapping
         */
        function getMapping() {
            return Filter\Registry::getMapping($this);
        }

        /**
         * Sets mapping for factory mapper
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
         * Default execution path for factory mappers
         * @param mixed $input
         * @param string $typeName
         * @return mixed|null
         */
        function execute($input,$typeName) {
            $mapping = $this->getMapping();
            
            $inputMapping = null;
            if(isset($mapping[$typeName])) {
                $inputMapping = $mapping[$typeName];
            }
            
            if(is_string($inputMapping) && $this->hasFactory()) {
                $factory = $this->getFactory();
                $object = $factory->build($inputMapping);
                
                if(method_exists($this,'map')) {
                    $object = $this->map($input, $object);
                }
                
                return $object;
            }

            return null;
        }
    }
}