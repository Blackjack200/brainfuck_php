<?php /** @noinspection PhpUnhandledExceptionInspection */
require('brainfuck_interpreter.php');
require('brainfuck_AOT.php');
file_put_contents('demo_compiled.bfc',compile(file_get_contents('demo.bf'))->getByteCode());
run(new CompiledScript(file_get_contents('demo_compiled.bfc')));