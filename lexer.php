<?php

namespace brainfuck;

use Iterator;

function compile(Iterator $raw) : CompiledScript {
	/** @var Node[] */
	$mem = [];
	/** @var Node[] */
	$goto = [];
	$code = '';
	$ignore = false;
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
					$mem[] = $entry;
					break;
				case ']':
					/** @var Node $node */
					$node = array_pop($mem);
					$node->end = $realIndex;
					$goto[] = $node;
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