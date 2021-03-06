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

class ModernEmpty extends Analyzer {
    protected $phpVersion = '5.5+';
    
    public function dependsOn() {
        return array('Variables/IsRead',
                     'Classes/IsRead',
                     'Arrays/IsRead',
                     );
    }
    
    public function analyze() {
        // $a = source();
        // if (empty($a)) {}
        // No check for reuse yet
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'variable')
             ->back('first')
             ->nextSibling()
             ->atomInside('Functioncall')
             ->functioncallIs('\\empty')
             ->outIs('ARGUMENT')
             ->samePropertyAs('fullcode', 'variable')
             ->back('first')
             ->raw('not(__.where( __.in("EXPRESSION")
                                    .emit().repeat(__.not(hasLabel("'.implode('", "', self::$CONTAINERS).'")).out()).times('.self::MAX_LOOPING.')
                                    .hasLabel("'.implode('", "', self::$CONTAINERS).'").filter{ it.get().value("fullcode") == variable }
                                    .not( __.where(__.in("ARGUMENT").hasLabel("Functioncall").has("token", "T_EMPTY")))
                                    .where( __.in("ANALYZED").has("analyzer", within("Variables/IsRead", "Classes/IsRead", "Arrays/IsRead")))
                                )
                  )');
        $this->prepareQuery();
    }
}

?>
