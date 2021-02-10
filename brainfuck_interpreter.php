<?php
/**
 * @throws Exception
 */
function compile(string $raw) : CompiledScript {
	/** @var Entry[] $goto */
	$goto = [];
	$code = '';
	$ignore = false;
	$temp = 0;
	for ($index = 0, $max = strlen($raw); $index < $max; $index++) {
		$char = $raw[$index];
		switch ($char) {
			case '#':
				$ignore = true;
				break;
			case "\n":
				$ignore = false;
				break;
		}
		if (!$ignore) {
			$code .= $char;
			$realIndex = strlen($code) - 1;
			
			switch ($char) {
				case '[':
					$entry = new Entry();
					$entry->start = $realIndex;
					$goto[] = $entry;
					$temp = count($goto) - 1;
					break;
				case ']':
					if ($temp !== count($goto) - 1) {
						$temp--;
					}
					$goto[$temp--]->end = $realIndex;
					break;
			}
		}
	}	
	return CompiledScript::compile($code, $goto);
}

/**
 * @throws Exception
 */
function run(CompiledScript $script) {
	$code = $script->getCode();
	$redirectStart = $script->getStart();
	$redirectEnd = $script->getEnd();
	/** @var int[] $stack */
	$stack = [];
	$head = 0;

	for ($index = 0, $max = strlen($code); $index < $max; $index++) {
		if (!isset($stack[$head])) {
			$stack[$head] = 0;
		}
		switch ($code[$index]) {
			case '+':
				$stack[$head]++;
				break;
			case '-':
				$stack[$head]--;
				break;
			case '>':
				$head++;
				break;
			case '<':
				$head--;
				break;
			case ',':
				$stack[$head] = ord(fread(STDIN, 1));
				break;
			case '.':
				echo chr($stack[$head]);
				break;
			case '[':
				if ($stack[$head] === 0) {
					$index = $redirectStart[$index];
				}
				break;
			case ']':
				if ($stack[$head] !== 0) {
					$index = $redirectEnd[$index];
				}
				break;
		}
	}
}

class Entry {
	public int $start;
	public int $end;
}
