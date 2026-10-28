--TEST--
ZEND_ROPE_END: String size overflow guard (32-bit)
--SKIPIF--
<?php
if (PHP_INT_SIZE != 4) die("skip this test is for 32bit platform only");
?>
--FILE--
<?php
/* On 32-bit PHP, ZSTR_MAX_LEN = SIZE_MAX - ZSTR_MAX_OVERHEAD (~4GB - 24).
 * The rope accumulates lengths into a size_t without an overflow check.
 * All rope elements can reference the same zend_string (shared refcount),
 * so 65538 references to a 65537-byte string give a logical length of
 * 65538 * 65537 = 4295163906 = 0x100030002, which wraps to 196610 on
 * 32-bit. The fix adds a guard that raises a fatal error instead of
 * corrupting the heap. Total memory usage is only ~2.5 MB. */
$code = '$a = str_repeat("A", 65537); $b = "$a' . str_repeat('$a', 65537) . '";';
eval($code);
echo "Should not reach here\n";
?>
--EXPECTF--
Fatal error: String size overflow in %s(%d) : eval()'d code on line %d
