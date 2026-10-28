--TEST--
ZEND_ROPE_END: Normal string interpolation with multiple elements
--FILE--
<?php
/* Regression test: verify that normal rope interpolation still works correctly
 * after adding the overflow guard. This tests 3+ element interpolation which
 * uses ZEND_ROPE_END. */
$a = "Hello";
$b = "World";
$c = "!";

/* 3 elements -> ROPE_END */
$result = "$a $b$c";
var_dump($result);

/* Same variable referenced multiple times (shared zend_string) */
$x = str_repeat("AB", 100);
$result2 = "$x$x$x";
var_dump(strlen($result2));
var_dump($result2 === str_repeat("AB", 300));

/* Mixed literals and variables */
$y = "test";
$result3 = "prefix-{$y}-middle-{$y}-suffix";
var_dump($result3);

echo "Done\n";
?>
--EXPECT--
string(12) "Hello World!"
int(600)
bool(true)
string(30) "prefix-test-middle-test-suffix"
Done
