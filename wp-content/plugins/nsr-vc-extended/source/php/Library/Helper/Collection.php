<?php

namespace VcExtended\Library\Helper;

class Collection
{
    public $items = array();

    public function addItem($obj, $key = null) {

        if ($key === null) {
            $this->items[] = $obj;
        }
        else {
            if (isset($this->items[$key])) {
                return false;
            }
            else {
                $this->items[$key] = $obj;
            }
        }
    }

    public function deleteItem($key) {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }
        else {
            return false;
        }
    }

    public function getItem($key) {

        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
        else {
            return 0;
        }
    }


    public function keys() {
        return array_keys($this->items);
    }


    public function length() {
        return count($this->items);
    }

    public function keyExists($key) {
        return isset($this->items[$key]);
    }

}