<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MockOptions
 *
 * @author jarkkolaine
 */
class MockOptions {

    protected $data;

    function __construct() {
        $this->data = array();
    }

    function update_option($option_name, $data) {
        $this->data[$option_name] = $data;
    }

    function get_option($option_name) {
        if (isset($this->data[$option_name])) {
            return $this->data[$option_name];
        } else {
            return array();
        }
    }

}
?>