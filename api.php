<?php

function count_all() {
    $count = array();

    # activity
    $sql_activity = "SELECT id FROM ". DB_PREFIX. "bp_activity WHERE hide_sitewide < 1";
    $count[] = array('activity' => mysql_num_rows(mysql_query($sql_activity)));

    # friendships
    $sql_friendships = "SELECT id FROM ". DB_PREFIX. "bp_friends WHERE is_confirmed > 0";
    $count[] = array('friendships' => mysql_num_rows(mysql_query($sql_friendships)));

    # groups
    $sql_groups = "SELECT id FROM ". DB_PREFIX. "bp_groups";
    $count[] = array('groups' => mysql_num_rows(mysql_query($sql_groups))-1);

    # links
    $sql_links = "SELECT id FROM ". DB_PREFIX. "bp_links WHERE status = 1";
    $count[] = array('links' => mysql_num_rows(mysql_query($sql_links)));

    # events
    $sql_events = "SELECT id FROM ". DB_PREFIX. "jet_events";
    $count[] = array('events' => mysql_num_rows(mysql_query($sql_events)));

    # users
    $sql_users = "SELECT id FROM ". DB_PREFIX. "users WHERE spam = 0 AND deleted = 0 AND user_activation_key = ''";
    $count[] = array('users' => mysql_num_rows(mysql_query($sql_users)));

    $count[] = array('all' => $count[0]['activity']+$count[1]['friendships']+$count[2]['groups']+$count[3]['links']+$count[4]['events']+$count[5]['users']);

    return $count;
}

function last_activity($type = 'all', $c = '20') {
    $activity = array();
    if ($type == 'all') {
        $sql = "SELECT * FROM ". DB_PREFIX. "bp_activity WHERE hide_sitewide = 0 ORDER BY date_recorded DESC";
    } else {
        $sql = "SELECT * FROM ". DB_PREFIX. "bp_activity WHERE type = '". $type. "' hide_sitewide = 0 ORDER BY date_recorded";
    }

    if (!empty($sql)) {
        if ($r = mysql_query($sql)) {
            $count = 0;
            while ($item = mysql_fetch_object($r)) {
                if (strlen($item->content) != 0 && $count <= $c) {
                    $activity[] = $item;
                    $count++;
                }
            }
        }
    }

    return $activity;
}

function user_activity($user = 0, $type = 'all', $c = '20') {
    $activity = array();
    if ($type == 'all') {
        $sql = "SELECT * FROM ". DB_PREFIX. "bp_activity WHERE user_id = ". $user. " AND hide_sitewide = 0 ORDER BY date_recorded DESC";
    } else {
        $sql = "SELECT * FROM ". DB_PREFIX. "bp_activity WHERE user_id = ". $user. " AND type = '". $type. "' hide_sitewide = 0 ORDER BY date_recorded DESC";
    }

    if (!empty($sql)) {
        if ($r = mysql_query($sql)) {
            $count = 0;
            while ($item = mysql_fetch_object($r)) {
                if (strlen($item->content) != 0 && $count <= $c) {
                    $activity[] = $item;
                    $count++;
                }
            }
        }
    }

    return $activity;
}

?>
