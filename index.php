<?php

    function autoload($name) {
        require 'lib/' . $name . '.php';
    }

    spl_autoload_register('autoload');
    
    PageParser::start();
