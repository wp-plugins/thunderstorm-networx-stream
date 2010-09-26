<?php

// Konfiguration laden
require_once dirname(__FILE__). '/config.php';
include API_DIR. '/settings.php';

header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
// JSON-Header
if (!is_dev()) {
    header("Content-type: application/json");
}

// Alle Fehler anzeigen, wenn Debug-Mode
if ($_GET['debug'] == 1) {
    error_reporting(E_ALL);
}

$out_ar = array();

// Wenn keine Sprache angegeben wurde, Fehler melden
if (empty($_GET['lang']) || $_GET['lang'] == '') {
    $out_ar = array('status' => 'error',
        'error' => 'No language defined!',
        'todo' => 'Please define a language (e.g. http://api.thunderstorm-networx.de/?lang=the_language)'
    );
    die(output());
}

require_once dirname(__FILE__). '/api.php';

switch (strtolower($a)) {
    // Wenn keine Aktion angegeben ist, Fehler melden!
    default: {
        $out_ar = array('status' => $lang['error'],
            'error' => $lang['unknown_action'],
            'todo' => $lang['unknown_action_desc'],
        );
        output();
        break;
    }

    case '': {
        $out_ar = array('status' => $lang['error'],
            'error' => $lang['no_action'],
            'todo' => $lang['no_action_desc'],
        );
        output();
        break;
    }

    case 'info': {
        $out_ar = array('status' => 'ok',
            'api_version' => $lang['api_version'],
            'time' => NOW,
            'time_c' => date($tf, NOW)
        );
        output();
        break;
    }

    case 'show': {
        $out_ar = array('status' => 'ok',
            'action' => 'show',
            'what' => $w
        );
        output();
        break;
    }

    case 'ca': {
        $out_ar = array('status' => 'ok',
            'action' => 'ca/count_all',
            'return' => count_all()
        );
        output();
        break;
    }

    case 'count_all': {
        $out_ar = array('status' => 'ok',
            'action' => 'ca/count_all',
            'return' => count_all()
        );
        output();
        break;
    }

    case 'la': {
        $out_ar = array('status' => 'ok',
            'action' => 'la/last_actiy',
            'type' => $t,
            'count' => $c,
            'return' => last_activity($t, $c)
        );
        output();
        break;
    }

    case 'last_actiy': {
        $out_ar = array('status' => 'ok',
            'action' => 'la/last_actiy',
            'type' => $t,
            'count' => $c,
            'return' => last_activity($t, $c)
        );
        output();
        break;
    }

    case 'ua': {
        if (get_user_id($user) != false) {
            $out_ar = array('status' => 'ok',
                'action' => 'ua/user_activity',
                'type' => $t,
                'count' => $c,
                'return' => user_activity(get_user_id($user), $t, $c)
            );
        }else {
            $out_ar = array('status' => $lang['error'],
                'error' => $lang['no_user'],
                'todo' => $lang['no_user_desc'],
            );
        }
        output();
        break;
    }
}

?>
