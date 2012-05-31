<?php
/**
 * Test class for bug 28
 * @link https://github.com/mrclay/minify/issues/28
 */

require_once '_inc.php';
require_once 'Minify/HTML/Helper.php';

function test_bug28() {
	$fiveSecondsAgo = $_SERVER['REQUEST_TIME'] - 5;

	$file1 = new stdClass();
	$file1->lastModified = $fiveSecondsAgo;

	$file2 = new stdClass();
	$file2->lastModified = $fiveSecondsAgo;

	$m1 = Minify_HTML_Helper::getLastModified(array($file1));
	error_log("last modified: $m1");

	$m2 = Minify_HTML_Helper::getLastModified(array($file1, $file2));
	error_log("last modified: $m2");

	assertTrue($m1 !== $m2, "mtime of group with one and two files should not be same");
}

test_bug28();
