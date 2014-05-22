<?php
namespace kfiltr\Factory {
    use kfiltr;
    
    /**
     * @method map
     */
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
         * @param callable $namingCallback
         * @return kfiltr\Filter\Mapping
         */
        function setMapping($mapping,callable $namingCallback = null) {
            if(is_array($mapping)) {
                $mapping = new kfiltr\Filter\Mapping($mapping,$namingCallback);
                $namingCallback = null;
            }
            
            if(!($mapping instanceof kfiltr\Filter\Mapping)) {
                throw new \InvalidArgumentException;
            }
            
            if(is_callable($namingCallback)) {
                $mapping->setNamingCallback($namingCallback);
            }
            
            return Filter\Registry::setMapping($this, $mapping);
        }

        /**
         * Default execution path for factory mappers
         * @param mixed $input
         * @param string $typeName
         * @return mixed|null
         */
        function execute($input,$typeName = null) {
            $mapping = $this->getMapping();
            
            if(is_null($typeName)) {
                $callback = $mapping->getNamingCallback();
                $typeName = $callback($input);
            }
            
            $inputMapping = null;
            
            if($mapping->exists($typeName)) {
                $inputMapping = $mapping[$typeName];
            }
            
            if(is_string($inputMapping) && $this->hasFactory()) {
                $factory = $this->getFactory();
                $object = $factory->build($inputMapping);
                
                if(method_exists($this,'map')) {
                    $object = $this->map($input, $object);
                } else {
                    $mappingFn = kfiltr\Mapper\Registry::getDefaultMapper();
                    
                    $object = $mappingFn($input,$object);
                }
                
                return $object;
            }

            return null;
        }
    }
}