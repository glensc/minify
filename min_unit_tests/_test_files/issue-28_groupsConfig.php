<?php

/**
 * workaround for $groupsConfig needing to be a file
 * @see Minify_HTML_Helper::setGroup()
 */

global $groupsConfig;
return $groupsConfig;