<?php

namespace brainfuck;

use Generator;

function run(CompiledScript $script, ?Environment $environment = null) {
	if ($environment === null) {
		$environment = Environment::new();
	}
	
	$code = $script->getCode();
	$redirectStart = $script->getStart();
	$redirectEnd = $script->getEnd();
	
	for ($index = 0, $max = strlen($code); $index < $max; $index++) {
		if (!isset($environment->stack[$environment->head])) {
			$environment->stack[$environment->head] = 0;
		}
		
		switch ($code[$index]) {
			case '+':
				$environment->stack[$environment->head]++;
				break;
			case '-':
				$environment->stack[$environment->head]--;
				break;
			case '>':
				$environment->head++;
				break;
			case '<':
				$environment->head--;
				break;
			case ',':
				$environment->stack[$environment->head] = (ord(fread($environment->input, 1)));
				break;
			case '.':
				fwrite($environment->output, chr($environment->stack[$environment->head]));
				break;
			case '[':
				if ($environment->stack[$environment->head] === 0) {
					$index = $redirectStart[$index];
				}
				break;
			case ']':
				if ($environment->stack[$environment->head] !== 0) {
					$index = $redirectEnd[$index];
				}
				break;
		}
	}
}


final class Environment {
	public array $stack = [];
	public int $head = 0;
	/** @var resource */
	public $output = STDOUT;
	/** @var resource */
	public $input = STDIN;
	
	public static function new() : Environment {
		return new self();
	}
}

function read($stream) : Generator {
	while (!feof($stream)) {
		yield fread($stream, 1);
	}
}