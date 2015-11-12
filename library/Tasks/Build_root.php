<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Build_root extends Tasks {
    private $project_dir = '.';
    private $config      = null;
    
    public function run(\Config $config) {
        $this->checkTokenLimit();
        
        $this->config = $config;
        $this->project_dir = $config->projects_root.'/projects/'.$config->project;

        $this->logTime('Start');

        $result = gremlin_query('g.idx("racines")');

        if (!isset($result->results)) {
            gremlin_query("g.createIndex('racines', Vertex)");
        }
        $this->logTime('Created racines');

        $this->logTime('g.idx("racines")');
        gremlin_query('g.dropIndex("atoms");');
        gremlin_query('g.createIndex("atoms", Vertex)');

        $this->logTime('g.idx("atoms")');

        // separate processing for T_STRING
        $query = <<<GREMLIN
g.V.has("token", "T_STRING").has("atom", null).each{
    it.setProperty("fullcode", it.getProperty("code"));
    it.setProperty("atom", "Identifier");
    g.idx("atoms").put("atom", it.atom, it);
}
GREMLIN;
        gremlin_query($query);
        $this->logTime('g.idx("atoms") : T_STRING');

        // separate processing for T_VARIABLE
        $query = <<<GREMLIN
g.V.has("token", "T_VARIABLE").each{
    it.setProperty("fullcode", it.getProperty("code"));
    it.setProperty("atom", "Variable");
    g.idx("atoms").put("atom", it.atom, it);
}
GREMLIN;
        gremlin_query($query);
        $this->logTime('g.idx("atoms") : T_VARIABLE');

        $query = <<<GREMLIN
g.V.has("token", "T_STRING_VARNAME").each{
    it.setProperty("fullcode", it.getProperty("code"));
    it.setProperty("atom", "Variable");
    g.idx("atoms").put("atom", it.atom, it);
}
GREMLIN;
        gremlin_query($query);
        $this->logTime('g.idx("atoms") : T_STRING_VARNAME');

        $query = <<<GREMLIN
g.V.filter{it.atom in ["Integer", "String",  "Magicconstant", "Null",
                       "Rawstring", "Float", "Boolean", "Void", "File"]}.each{
    g.idx("atoms").put("atom", it.atom, it);
    if (it.atom == 'Integer') {
        if (it.code.length() == 1) { // number
            it.setProperty('intval', Integer.parseInt(it.code));
        } else if (it.code.substring(0, 2) == '0b') { // binary
            it.setProperty('intval', new BigInteger(it.code.substring(2), 2).toLong());
        } else if (it.code.substring(0, 2) == '0B') { // binary
            it.setProperty('intval', new BigInteger(it.code.substring(2), 2).toLong());
        } else if (it.code.substring(0, 2) == '0x') { // hexadecimal
            it.setProperty('intval', new BigInteger(it.code.substring(2), 16).toLong());
        } else if (it.code.substring(0, 2) == '0X') { // hexadecimal
            it.setProperty('intval', new BigInteger(it.code.substring(2), 16).toLong());
        } else if (it.code.substring(0, 1) == '0') { // octal
            // Calculating PHP 5 style. In case of problem (presence of 9), PHP 7 will just stop.
            nine = it.code.indexOf('9');
            if (nine == -1) {
                it.setProperty('intval', new BigInteger(it.code.substring(1), 8).toLong());
            } else if (nine == 1) {
                it.setProperty('intval', 0);
            } else {
                it.setProperty('intval', new BigInteger(it.code.substring(1, nine), 8).toLong());
            }
        } else {
            it.setProperty('intval', new BigInteger(it.code).toLong());
        }
    }
}
GREMLIN;
        gremlin_query($query);
        $this->logTime('g.idx("atom")[["atom":"******"]] : filling');

        // creating the index
        // @todo check this index
        gremlin_query("g.V.has('root', true).each{ g.idx('racines').put('token', 'ROOT', it); };");
        $this->logTime('g.idx("ROOT")');

        // special case for the initial Rawstring.
        gremlin_query("g.idx('racines')[['token':'ROOT']].has('atom','Sequence').each{ g.idx('atoms').put('atom', 'Sequence', it); };");
        $this->logTime('g.idx("racines") ROOT special');

        // creating the neo4j Index
        gremlin_query("g.V.has('index', true).each{ g.idx('racines').put('token', it.token, it); };");
        $this->logTime('g.idx("racines")[[token:***]] indexing');
        
        gremlin_query("g.idx('racines')[['token':'Sequence']].out('INDEXED').has('in_for', true).inE('INDEXED').each{ g.removeEdge(it); }");
        // At least one index for sequence (might be needed during processing, even if empty initially)
        gremlin_query("sequences = g.addVertex(null, [token:'T_SEMICOLON', code:'Index for Sequence', index:true]); g.idx('racines').put('token', 'Sequence', sequences);");

        $this->logTime('Indexing racines done');

        // calculating the Unicode blocks
        gremlin_query("g.idx('atoms')[['atom':'String']].filter{it.code.replaceAll(/^['\"]/, '').size() > 0}.each{ it.setProperty('unicode_block', it.code.replaceAll(/^['\"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString()); };");
        gremlin_query("g.idx('atoms')[['token':'Rawstring']].filter{it.code.replaceAll(/^['\"]/, '').size() > 0}.each{ it.setProperty('unicode_block', it.code.replaceAll(/^['\"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString()); };");
        $this->logTime('Unicodes block');

        gremlin_query("g.idx('atoms')[['atom':'String']].has('noDelimiter', null).filter{ it.code in ['\"\"', \"''\"]}.each{ it.setProperty('noDelimiter', ''); };");
        $this->logTime('Unicodes block');

        // resolving the constants
        $extra_indices = array('constants', 'classes', 'interfaces', 'traits', 'functions', 'namespaces', 'files');
        foreach($extra_indices as $indice) {
            gremlin_query("g.dropIndex('$indice');");
            gremlin_query("g.createIndex('$indice', Vertex)");
        }
        $this->logTime('g.idx("last index")');
    }

    private function logTime($step) {
        static $begin, $end, $start;
    
        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }
    
        $this->log->log($step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }
}

?>
