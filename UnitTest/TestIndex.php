<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
require_once __DIR__.DIRECTORY_SEPARATOR."DataBaseTest.php";

use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\TestRunner;
$suite = new TestSuite();

/**
 * 测试 mode 的基础功能
 */
$suite->addTest(new DataBaseTest('testSelect'));
$suite->addTest(new DataBaseTest('testDelete'));
$suite->addTest(new DataBaseTest('testInsert'));
$suite->addTest(new DataBaseTest('testUpdate'));

$runner=new TestRunner();
$result = $runner->run($suite);