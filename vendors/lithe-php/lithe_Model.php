<?php

class lithe_Model {
    protected $data = array();
    public function set($key, $value = null) {
        $this->data[$key] = $value;
    }
    public function delete($key) {
        unset($this->data[$key]);
    }
    public function get($key) {
        return $this->data[$key];
    }
    public function merge(array $data) {
        $this->data = array_merge_recursive($this->data, $data);
    }
    public function replace(array $data) {
        $this->data = $data;
    }
    public function export() {
        return $this->data;
    }
}