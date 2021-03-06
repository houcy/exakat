<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Psr_Psr16Usage extends Analyzer {
    /* 1 methods */

    public function testPsr_Psr16Usage01()  { $this->generic_test('Psr/Psr16Usage.01'); }
}
?>