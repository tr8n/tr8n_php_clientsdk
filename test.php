<?php


function test($a, $b) {
    return $b($a);
}


echo(test("Hello", function($hello){
   return $hello . " World";
}));