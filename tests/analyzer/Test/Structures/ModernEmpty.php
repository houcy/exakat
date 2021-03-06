<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ModernEmpty extends Analyzer {
    /* 5 methods */

    public function testStructures_ModernEmpty01()  { $this->generic_test('Structures/ModernEmpty.01'); }
    public function testStructures_ModernEmpty02()  { $this->generic_test('Structures/ModernEmpty.02'); }
    public function testStructures_ModernEmpty03()  { $this->generic_test('Structures/ModernEmpty.03'); }
    public function testStructures_ModernEmpty04()  { $this->generic_test('Structures/ModernEmpty.04'); }
    public function testStructures_ModernEmpty05()  { $this->generic_test('Structures/ModernEmpty.05'); }
}
?>