<?php
namespace kfiltr\Interfaces {
    interface Mapper {
        /**
         * Function used to map input data to object instance
         * @param mixed $input
         * @param mixed $object
         */
        function map($input,$object=null);
    }
}