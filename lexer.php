<?php
namespace brainfuck;

use Iterator;

function compile(Iterator $raw) : CompiledScript {
	/** @var Node[] $goto */
	$goto = [];
	$code = '';
	$ignore = false;
	$temp = 0;
	foreach ($raw as $char) {
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
					$entry = new Node();
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

class Node {
	public int $start;
	public int $end;
}