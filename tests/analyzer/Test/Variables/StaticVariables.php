<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_StaticVariables extends Analyzer {
    /* 4 methods */

    public function testVariables_StaticVariables01()  { $this->generic_test('Variables_StaticVariables.01'); }

    public function testVariables_StaticVariables02()  { $this->generic_test('Variables_StaticVariables.02'); }
    public function testVariables_StaticVariables03()  { $this->generic_test('Variables_StaticVariables.03'); }
    public function testVariables_StaticVariables04()  { $this->generic_test('Variables_StaticVariables.04'); }
}
?>