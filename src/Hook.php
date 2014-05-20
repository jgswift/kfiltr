<?php
namespace kfiltr {
    trait Hook {
        /**
         * Retrieves list of all hooked filters
         * @return array
         */
        function getFilters() {
            return Hook\Registry::getFilters($this);
        }

        /**
         * Sets list of hooked filters, all filters must implement __invoke method
         * @param array $filters
         * @return array
         */
        function setFilters(array $filters) {
            Hook\Registry::setFilters($this, $filters);
            return $filters;
        }

        /**
         * Adds hooked filter
         * @param \callable $filter
         * @return \callable
         */
        function addFilter($filter) {
            Hook\Registry::addFilter($this, $filter);
            return $filter;
        }

        /**
         * Removes hooked filter
         * @param \callable $filter
         */
        function removeFilter() {
            Hook\Registry::removeFilter($this);
        }

        /**
         * Removes all hooked filters
         */
        function clearFilters() {
            Hook\Registry::clearFilters($this);
        }

        /**
         * Checks if any hooked filters exist locally
         * @return boolean
         */
        function hasFilters() {
            return Hook\Registry::hasFilters($this);
        }
    }
}