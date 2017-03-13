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

namespace Exakat\Analyzer\ZendF;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\ZendF3;

class zf3Component extends Analyzer {
    protected $classes    = array();
    protected $interfaces = array();
    protected $traits     = array();
    protected $version    = null;
    
    public function analyze() {
        $data = new ZendF3($this->config->dir_root.'/data', $this->config->is_phar);

        $classes    = $data->getClasses($this->component, $this->version);
        if (!empty($classes)) {
            $classes    = array_merge(...array_values($classes));
            $classes    = array_keys(array_count_values($classes));
            $classes    = $this->makeFullNsPath($classes);
    
            if (!empty($classes)) {
                $this->atomIs('New')
                     ->outIs('NEW')
                     ->raw('where( __.out("NAME").hasLabel("Array", "Variable", "Property", "Staticproperty", "Methodcall", "Staticmethodcall").count().is(eq(0)))')
                     ->tokenIs(self::$FUNCTIONS_TOKENS)
                     ->fullnspathIs($classes);
                $this->prepareQuery();
                
                $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant'))
                     ->outIs('CLASS')
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                     ->atomIsNot('Array')
                     ->fullnspathIs($classes);
                $this->prepareQuery();
        
                $this->atomIs('Catch')
                     ->outIs('CLASS')
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                     ->fullnspathIs($classes);
                $this->prepareQuery();
        
                $this->atomIs(array('Nsname', 'Identifier'))
                     ->hasIn('TYPEHINT')
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                     ->fullnspathIs($classes);
                $this->prepareQuery();
        
                $this->atomIs('Instanceof')
                     ->outIs('CLASS')
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                     ->atomIsNot(array('Array', 'Null', 'Boolean'))
                     ->fullnspathIs($classes);
                $this->prepareQuery();
        
                $this->atomIs('Class')
                     ->outIs(array('EXTENDS', 'IMPLEMENTS'))
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                     ->fullnspathIs($classes);
                $this->prepareQuery();
        
        // Check that... Const/function and aliases
                $this->atomIs('Use')
                     ->outIs('USE')
                     ->outIsIE('NAME')
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                     ->fullnspathIs($classes);
                $this->prepareQuery();
            }
        }

        $interfaces =  $data->getInterfaces($this->component, $this->version);
        if (!empty($interfaces)) {
            $interfaces = array_merge(...array_values($interfaces));
            $interfaces = array_keys(array_count_values($interfaces));
            $interfaces = $this->makeFullNsPath($interfaces);
        
            if (!empty($interfaces)) {
        
            }
        }

        $traits     =  $data->getTraits($this->component, $this->version);
        if (!empty($traits)) {
            $traits     = array_merge(...array_values($traits));
            $traits     = array_keys(array_count_values($traits));
            $traits     = $this->makeFullNsPath($traits);

            if (!empty($traits)) {
        
            }
        }
    }
}

?>