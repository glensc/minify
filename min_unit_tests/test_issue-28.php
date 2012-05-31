<?php
/**
 * Test class for bug 28
 * @link https://github.com/mrclay/minify/issues/28
 */

require_once '_inc.php';
require_once 'Minify/HTML/Helper.php';

function test_bug28_mtime()
{
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

function test_bug28_geturi()
{
    $opts = array(
        'groupsConfigFile' => dirname(__FILE__) . '/_test_files/issue-28_groupsConfig.php',
    );

    $mtime = $_SERVER['REQUEST_TIME'];

    $file1 = new stdClass();
    $file1->lastModified = $mtime;
    $file1->filepath = 'file1';

    $file2 = new stdClass();
    $file2->lastModified = $mtime;
    $file2->filepath = 'file2';

    global $groupsConfig;

    $groupsConfig = array(
        'l' => array(
            $file1,
        )
    );
    $uri1 = Minify_HTML_Helper::getUri('l', $opts);

    $groupsConfig = array(
        'l' => array(
            $file1,
            $file2,
        )
    );
    $uri2 = Minify_HTML_Helper::getUri('l', $opts);

    error_log("u1=[$uri1]");
    error_log("u2=[$uri2]");

    assertTrue($uri1 !== $uri2, "uri of group with one and two files should not be same");
}

test_bug28_mtime();
test_bug28_geturi();