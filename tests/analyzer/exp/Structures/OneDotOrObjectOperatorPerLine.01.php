<?php

$expected     = array("'a' . 'b' . 'c'",
                      '$a4->b->c',
                      '$a3->b( )->c( )',
                      '$a2->b( )->c',
                      '$a1->b->c',
);

$expected_not = array("'a' . 'b' . 'c' . 'd' . 'e'",
                      '$f4->b->c',
                      '$f3->b( )->c( )',
                      '$f2->b( )->c',
                      '$f1->b->c',
                      "\$a4->b('a' . 'c');"
);

?>