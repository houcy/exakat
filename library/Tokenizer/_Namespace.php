<?php

namespace Tokenizer;

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');
    static public $atom = 'Namespace';

    public function _check() {
        // namespace {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'Sequence'),
        );
        
        $this->actions = array('insert_global_ns' => 1,
                               'keepIndexed'      => true);
        $this->checkAuto();

        // namespace myproject {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('atom'  => 'Sequence'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE',
                                                        2 => 'BLOCK'),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // namespace myproject ; 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_SEMICOLON'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE'),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAMESPACE").each{ fullcode.setProperty('fullcode', "namespace " + it.getProperty('fullcode'));} 
fullcode.filter{ it.out('NAMESPACE').count() == 0}.each{ fullcode.setProperty('fullcode', "namespace Global");} 

GREMLIN;
    }
}

?>