<?php
namespace Dellysse\Csv;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Dellysse\Csv\Exception\NotFound as NotFoundException;
use Dellysse\Csv\Exception\NotSupported as NotSupportedException;

class File implements ArrayAccess, Countable, IteratorAggregate {
    static function fromFilename ($filename) {
        $instance = new File($filename);
        $instance->process();
        return $instance;
    }

    public $rows;
    public $filename;
    public $header;
    public $hasHeader;

    function __construct ($filename, $hasHeader = false) {
        $this->rows = array();
        $this->filename = $filename;
        $this->hasHeader = $hasHeader;
    }

    function process () {
        if (count($this->rows)) {
            return;
        }

        $handle = fopen($this->filename, 'r');
        if (!$handle) {
            throw new NotFoundException("Cannot read file {$this->filename}");
        }

        if ($this->hasHeader) {
            $first = true;
        }

        ini_set('auto_detect_line_endings', true);
        while ($rowArray = fgetcsv($handle)) {
            if ($this->hasHeader) {
                if ($first) {
                    $first = false;
                    $this->header = array_values($rowArray);
                    continue;
                }
                $row = new Row($rowArray);
                $row->applyKeys($this->header);
                $this->rows []= $row;
            } else {
                $this->rows []= new Row($rowArray);
            }
        }
        fclose($handle);
    }

    function toArray () {
        $this->process();
        $retval = array();
        foreach ($this as $row) {
            $retval []= $row->toArray();
        }
        return $retval;
    }


    function getIterator () {
        $this->process();
        return new ArrayIterator($this->rows);
    }

    function count () {
        $this->process();
        return count($this->rows);
    }

    function offsetExists ($offset) {
        $this->process();
        return isset($this->rows[$offset]);
    }

    function offsetGet ($offset) {
        $this->process();
        return $this->rows[$offset];
    }

    function offsetSet ($offset, $value) {
        throw new NotSupportedException;
    }

    function offsetUnset ($offset) {
        throw new NotSupportedException;
    }

}
