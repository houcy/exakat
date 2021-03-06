<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_VariableUsedOnceByContext extends Analyzer {
    /* 14 methods */

    public function testVariables_VariableUsedOnceByContext01()  { $this->generic_test('Variables_VariableUsedOnceByContext.01'); }
    public function testVariables_VariableUsedOnceByContext02()  { $this->generic_test('Variables_VariableUsedOnceByContext.02'); }
    public function testVariables_VariableUsedOnceByContext03()  { $this->generic_test('Variables_VariableUsedOnceByContext.03'); }
    public function testVariables_VariableUsedOnceByContext04()  { $this->generic_test('Variables_VariableUsedOnceByContext.04'); }
    public function testVariables_VariableUsedOnceByContext05()  { $this->generic_test('Variables_VariableUsedOnceByContext.05'); }
    public function testVariables_VariableUsedOnceByContext06()  { $this->generic_test('Variables_VariableUsedOnceByContext.06'); }
    public function testVariables_VariableUsedOnceByContext07()  { $this->generic_test('Variables/VariableUsedOnceByContext.07'); }
    public function testVariables_VariableUsedOnceByContext08()  { $this->generic_test('Variables/VariableUsedOnceByContext.08'); }
    public function testVariables_VariableUsedOnceByContext09()  { $this->generic_test('Variables/VariableUsedOnceByContext.09'); }
    public function testVariables_VariableUsedOnceByContext10()  { $this->generic_test('Variables/VariableUsedOnceByContext.10'); }
    public function testVariables_VariableUsedOnceByContext11()  { $this->generic_test('Variables/VariableUsedOnceByContext.11'); }
    public function testVariables_VariableUsedOnceByContext12()  { $this->generic_test('Variables/VariableUsedOnceByContext.12'); }
    public function testVariables_VariableUsedOnceByContext13()  { $this->generic_test('Variables/VariableUsedOnceByContext.13'); }
    public function testVariables_VariableUsedOnceByContext14()  { $this->generic_test('Variables/VariableUsedOnceByContext.14'); }
}
?>