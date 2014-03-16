<?php
namespace kfiltr {
    trait Filter {
        /**
         * Retrieves filter delegate callable if available
         * @return callable
         */
        function getDelegate() {
            return Filter\Registry::getDelegate($this);
        }

        /**
         * Sets filter delegate
         * @param callable $delegate
         * @return callable
         */
        function setDelegate(callable $delegate) {
            Filter\Registry::setDelegate($this,$delegate);
            return $delegate;
        }

        /**
         * Checks if filter is delegated
         * @return boolean
         */
        function hasDelegate() {
            return Filter\Registry::hasDelegate($this);
        }
        
        /**
         * Helper function to remove delegate from filter
         * @param callable $delegate
         */
        function unsetDelegate(callable $delegate) {
            Filter\Registry::unsetDelegate($this, $delegate);
        }

        /**
         * Standard filter invoke implementation
         * @return mixed
         */
        function __invoke() {
            if(Filter\Registry::hasDelegate($this)) {
                return call_user_func_array($this->getDelegate(), func_get_args());
            }

            return call_user_func_array(array($this, 'execute'), func_get_args());
        }
    }
}