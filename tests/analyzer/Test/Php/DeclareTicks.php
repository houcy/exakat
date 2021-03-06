<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_DeclareTicks extends Analyzer {
    /* 4 methods */

    public function testPhp_DeclareTicks01()  { $this->generic_test('Php/DeclareTicks.01'); }
    public function testPhp_DeclareTicks02()  { $this->generic_test('Php/DeclareTicks.02'); }
    public function testPhp_DeclareTicks03()  { $this->generic_test('Php/DeclareTicks.03'); }
    public function testPhp_DeclareTicks04()  { $this->generic_test('Php/DeclareTicks.04'); }
}
?>