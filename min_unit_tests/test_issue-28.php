<?php
/**
 * Test class for bug 28
 * @link https://github.com/mrclay/minify/issues/28
 */

require_once '_inc.php';
require_once 'Minify/HTML/Helper.php';

/**
 * check that geturi differs if file count remains change, but files change
 * @see https://github.com/mrclay/minify/issues/28#issuecomment-6036867
 */
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

/**
 * tests that replacing older file (that is still older than newest file) generates new checksum
 * @see https://github.com/mrclay/minify/issues/28#issuecomment-6505012
 * @see Minify_HTML_Helper::getRawUri()
 * @see Minify_HTML_Helper::getChecksum()
 */
function test_bug28_geturi_older_file()
{
    $opts = array(
        'groupsConfigFile' => dirname(__FILE__) . '/_test_files/issue-28_groupsConfig.php',
    );

    $mtime = $_SERVER['REQUEST_TIME'];

    // file1 is the newest file in group
    $file1 = new stdClass();
    $file1->lastModified = $mtime;
    $file1->filepath = 'file1';

    // these files are older than file1
    $file2 = new stdClass();
    $file2->lastModified = $mtime - 10;
    $file2->filepath = 'file2';

    $file3 = new stdClass();
    $file3->lastModified = $mtime - 20;
    $file3->filepath = 'file2';

    $file4 = new stdClass();
    $file4->lastModified = $mtime - 5;
    $file4->filepath = 'file2';

    global $groupsConfig;

    $groupsConfig = array(
        'l' => array(
            $file1,
            $file2,
        )
    );
    $uri1 = Minify_HTML_Helper::getUri('l', $opts);

    $groupsConfig = array(
        'l' => array(
            $file1,
            $file3,
        )
    );
    $uri2 = Minify_HTML_Helper::getUri('l', $opts);

    $groupsConfig = array(
        'l' => array(
            $file1,
            $file4,
        )
    );
    $uri3 = Minify_HTML_Helper::getUri('l', $opts);

    error_log("u1=[$uri1]");
    error_log("u2=[$uri2]");
    error_log("u3=[$uri3]");

    assertTrue($uri1 !== $uri2, "older file replaced with even older file");
    assertTrue($uri1 !== $uri3, "older file replaced with newer file (but older than newest file)");
}

test_bug28_geturi();
test_bug28_geturi_older_file();