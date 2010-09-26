<?php

/* MarkUp
|------------------------------------------------------------------|
| $a       =       string:action           default: NULL           |
| $c       =       int:count               default: 10             |
| $w       =       string:what             default:default         |
| $tf      =       string:time_format      default:DATE_RFC822     |
|------------------------------------------------------------------|
 */

define('NOW', time());

if (!empty($_GET['a'])) {
    $a = $_GET['a'];
} else {
    $a = NULL;
}

if (!empty($_GET['c'])) {
    $c = $_GET['c'];
} else {
    $c = 10;
}

if (!empty($_GET['w'])) {
    $w = $_GET['w'];
} else {
    $w = 'default';
}

if (!empty($_GET['tf'])) {
    $tf = $_GET['tf'];
} else {
    $tf = DATE_RFC822;
}

if (!empty($_GET['t'])) {
    $t = $_GET['t'];
} else {
    $t = 'all';
}

if (!empty($_GET['user'])) {
    $user = $_GET['user'];
} else {
    $user = '';
}

?>
