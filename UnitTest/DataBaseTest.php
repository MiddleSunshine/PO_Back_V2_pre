<?php

require_once dirname(__DIR__).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php";

use PHPUnit\Framework\TestCase;

class DataBaseTest extends TestCase{
    public function testD1(){
        $this->assertTrue(true);
    }
}