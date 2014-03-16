<?php
namespace kfiltr\Interfaces {
    interface Hook {
        /**
         * Retrieves hook filters
         */
        function getFilters();
        
        /**
         * Sets hook filters
         * @param array $filters
         */
        function setFilters(array $filters);
        
        /**
         * Adds filter to hook
         * @param callable $filter
         */
        function addFilter($filter);
        
        /**
         * Removes filter from hook
         * @param mixed $filter
         */
        function removeFilter($filter);
        
        /**
         * Clears all filters from hook
         */
        function clearFilters();
    }
}