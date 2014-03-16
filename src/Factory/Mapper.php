<?php
namespace kfiltr\Factory {
    trait Mapper {
        use kfiltr\Mapper;

        function getMapping() {
            return Filter\Registry::getMapping($this);
        }

        function setMapping(kfiltr\Filter\Mapping $mapping) {
            return Filter\Registry::setMapping($this, $mapping);
        }

        function execute($input) {
            $mapping = $this->getMapping();
            
            $inputMapping = null;
            if(array_key_exists($input,$mapping)) {
                $inputMapping = $mapping[$input];
            }

            if(is_string($inputMapping) && $this->hasFactory()) {
                $factory = $this->getFactory();
                return $factory->build($inputMapping);
            }

            return null;
        }
    }
}