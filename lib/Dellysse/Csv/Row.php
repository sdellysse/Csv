<?php
namespace Dellysse\Csv;

use ArrayAccess;
use Countable;
use Dellysse\Csv\Exception\NotSupported as NotSupportedException;

class Row implements ArrayAccess, Countable {
    public $array;

    function __construct ($row) {
        if (count($row) === 1 && !$row[0]) {
            $this->array = array();
        } else {
            $this->array = $row;
        }
    }

    function applyKeys ($keys) {
        $this->array = array_combine($keys, $this->array);
    }


    function isEmpty () {
        return !count($this);
    }

    function toArray () {
        return $this->array;
    }


    function count () {
        return count($this->array);
    }

    function offsetExists ($offset) {
        return isset($this->array[$offset]);
    }

    function offsetGet ($offset) {
        return $this->array[$offset];
    }

    function offsetSet ($offset, $value) {
        throw new NotSupportedException;
    }

    function offsetUnset ($offset) {
        throw new NotSupportedException;
    }
}
