<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class ShouldPreprocess extends Analyzer {
    public function dependsOn() {
        return array('Constants/IsPhpConstant');
    }
    
    public function analyze() {
        $dynamicAtoms = array('Variable', 'Array', 'Member', 'Magicconstant', 'Staticmethodcall', 'Staticproperty', 'Methodcall');
        //'Functioncall' : if they also have only constants.

        $methods = new Methods($this->config);
        $functionList = $methods->getDeterministFunctions();
        $functionList = $this->makeFullNsPath($functionList);

        // Operator only working on constants
        $tokenList = makeList( self::$FUNCTIONS_TOKENS );
        $this->atomIs(array('Addition', 'Multiplication', 'Concatenation', 'Power', 'Bitshift', 'Logical', 'Not'))
             ->hasNoInstruction('Constant')

            // Functioncall, that are not authorized
             ->raw('not( where( __.emit( ).repeat( out() ).times('.self::MAX_LOOPING.')
                                          .hasLabel("Functioncall").has("fullnspath")
                                          .has("token", within('.$tokenList.'))
                                          .filter{ !(it.get().value("fullnspath") in ***) } ) )', $functionList)
            // PHP Constantes are not authorized
             ->raw('not( where( __.emit( ).repeat( out() ).times('.self::MAX_LOOPING.')
                                          .hasLabel("Identifier", "Nsname").has("fullnspath")
                                          .where( __.in("ANALYZED").has("analyzer", "Constants/IsPhpConstant"))
                        )     )')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();
        
        $functionListNoArray = array_diff($functionList,
                array('\\defined', '\\error_reporting', '\\extension_loaded', '\\get_defined_vars', '\\print', '\\echo', '\\set_time_limit'));
        $functionListNoArray = array_values($functionListNoArray);
        
        // Function only applied to constants
        $this->atomFunctionIs($functionListNoArray)
             ->is('constant', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
