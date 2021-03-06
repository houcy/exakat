<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UndefinedProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_UndefinedProperty01()  { $this->generic_test('Classes_UndefinedProperty.01'); }
    public function testClasses_UndefinedProperty02()  { $this->generic_test('Classes_UndefinedProperty.02'); }
    public function testClasses_UndefinedProperty03()  { $this->generic_test('Classes/UndefinedProperty.03'); }
    public function testClasses_UndefinedProperty04()  { $this->generic_test('Classes/UndefinedProperty.04'); }
}
?>