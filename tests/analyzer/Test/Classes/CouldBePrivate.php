<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_CouldBePrivate extends Analyzer {
    /* 4 methods */

    public function testClasses_CouldBePrivate01()  { $this->generic_test('Classes/CouldBePrivate.01'); }
    public function testClasses_CouldBePrivate02()  { $this->generic_test('Classes/CouldBePrivate.02'); }
    public function testClasses_CouldBePrivate03()  { $this->generic_test('Classes/CouldBePrivate.03'); }
    public function testClasses_CouldBePrivate04()  { $this->generic_test('Classes/CouldBePrivate.04'); }
}
?>