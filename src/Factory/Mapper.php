<?php
namespace kfiltr\Factory {
    use kfiltr;
    
    trait Mapper {
        use kfiltr\Mapper;

        function getMapping() {
            return Filter\Registry::getMapping($this);
        }

        function setMapping(kfiltr\Filter\Mapping $mapping) {
            return Filter\Registry::setMapping($this, $mapping);
        }

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