<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrays_MixedKeys extends Analyzer {
    /* 3 methods */

    public function testArrays_MixedKeys01()  { $this->generic_test('Arrays/MixedKeys.01'); }
    public function testArrays_MixedKeys02()  { $this->generic_test('Arrays/MixedKeys.02'); }
    public function testArrays_MixedKeys03()  { $this->generic_test('Arrays/MixedKeys.03'); }
}
?>