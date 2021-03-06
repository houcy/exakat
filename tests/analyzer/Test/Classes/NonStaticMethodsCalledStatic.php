<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_NonStaticMethodsCalledStatic extends Analyzer {
    /* 10 methods */

    public function testClasses_NonStaticMethodsCalledStatic01()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.01'); }
    public function testClasses_NonStaticMethodsCalledStatic02()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.02'); }
    public function testClasses_NonStaticMethodsCalledStatic03()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.03'); }
    public function testClasses_NonStaticMethodsCalledStatic04()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.04'); }
    public function testClasses_NonStaticMethodsCalledStatic05()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.05'); }
    public function testClasses_NonStaticMethodsCalledStatic06()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.06'); }
    public function testClasses_NonStaticMethodsCalledStatic07()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.07'); }
    public function testClasses_NonStaticMethodsCalledStatic08()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.08'); }
    public function testClasses_NonStaticMethodsCalledStatic09()  { $this->generic_test('Classes/NonStaticMethodsCalledStatic.09'); }
    public function testClasses_NonStaticMethodsCalledStatic10()  { $this->generic_test('Classes/NonStaticMethodsCalledStatic.10'); }
}
?>