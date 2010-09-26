<?php

function check_auth() {
    
}

function output() {
    global $out_ar;

    if (is_dev()) {
        $json = json_encode($out_ar);
        echo format($json);
    } else {
        echo json_encode($out_ar);
    }
}

function is_dev() {
    if ($_GET['dev'] == 1) {
        return true;
    } else {
        return false;
    }
}

function format($json) {
    global $lang;
    return '<html>
        <head>
            <Link href="prettify/prettify.css" Typ="text/css" REL="stylesheet" />
            <script Typ="text/javascript" src="prettify/prettify.js"></script>
        </head>
        <body>
            <h3>API OutPut</h3>
            <div style="border: 1px solid #aaa; padding: 8px;"><code>'. htmlentities($json). '</code></div>
            <div style="text-align:center;padding:10px;">'. $lang['api_version']. '</div>
        </body>
    </html>';
}

function check_type($type) {
    if ($type = 'new_wire_post' ||
            $type = 'new_forum_post' ||
            $type = 'new_blog_comment' ||
            $type = 'friendship_created' ||
            $type = 'joined_group' ||
            $type = 'created_group' ||
            $type = 'new_forum_topic' ||
            $type = 'new_status' ||
            $type = 'activity_liked' ||
            $type = 'tweet' ||
            $type = 'create_event') {
        return true;
    }
    return false;
}

function get_user_id($user) {
    $sql = "SELECT * FROM ". DB_PREFIX. "users WHERE user_login = '". $user. "'";
    if ($r = mysql_query($sql)) {
        if ($item = mysql_fetch_object($r)) {
            return $item->ID;
        }
    }
    return false;
}

?>