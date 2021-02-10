<?php

use brainfuck\CompiledScript;
use function brainfuck\compile;
use function brainfuck\read;
use function brainfuck\run;

require('lexer.php');
require('AOT.php');
require('runtime.php');

function testFile(string $name) {
	$stream = fopen($name, 'rb+');
	if (!is_resource($stream)) {
		die('failed to open file');
	}
	return $stream;
}

$raw = getopt('e:')['e'] ?? null;
$compile = getopt('c:')['c'] ?? null;
$compiled = getopt('r:')['r'] ?? null;
if ($raw !== null) {
	run(compile(read(testFile($raw))));
} elseif ($compiled !== null) {
	run(new CompiledScript(stream_get_contents(testFile($compiled))));
} elseif ($compile !== null) {
	file_put_contents($compile . '.bfc', compile(read(testFile($compile)))->getByteCode());
}