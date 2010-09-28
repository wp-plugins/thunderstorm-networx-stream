<?php
/*
Plugin Name: thunderstorm-networx-stream
Plugin URI: http://www.thunderstorm-networx.de/
Description: Ermöglicht das Anzeigen der Aktivität eines Nutzers über die thunderstorm-networx-API
Version: 0.1.1.2
Author: thunderstorm-networx.de
Author URI: http://www.thunderstorm-networx.de/
*/

add_action( 'widgets_init', 'thunderstorm_stream_widget' );

$options['configuration'] = array (
    array(
        "name"      =>      "<strong>Dein thunderstorm-networx Benutzername:</strong>",
        "desc"      =>      "Gebe hier deinen Benutzernamen ein, den Du bei tunderstorm-networx gewählt hast. <a href=\"http://www.thunderstorm-networx.de/register/\">Hier</a> kannst du Dich kostenlos registrieren, falls du das noch nicht gemacht hast.",
        "id"        =>      "thunderstorm_api_user",
        "type"      =>      "textbox",
        "default"   =>      ""
    ),
    array(
        "name"      =>      "<strong>Anzahl</strong>",
        "desc"      =>      "Gebe hier die maximale Anzahl der anzuzeigenden Elemente an!",
        "id"        =>      "thunderstorm_api_user_activity_count",
        "type"      =>      "textbox",
        "default"   =>      "20"
    ),
);

function thunderstorm_stream_widget() {
	register_widget( 'thunderstorm_stream_widget_' );
}
class thunderstorm_stream_widget_ extends WP_Widget {
    /**
    * Widget setup.
    */
    function thunderstorm_stream_widget_() {
        /* Widget settings. */
        $widget_ops = array( 'classname' => 'thunderstorm_stream_widget', 'description' => 'thunderstorm-stream' );

        /* Widget control settings. */
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'thunderstorm_stream_widget' );

        /* Create the widget. */
        $this->WP_Widget( 'thunderstorm_stream_widget', 'thunderstorm-stream', $widget_ops, $control_ops );
    }

    /**
    * How to display the widget on the screen.
    */
    function widget( $args, $instance ) {
        extract( $args );

        /* Before widget (defined by themes). */
        echo $before_widget;

        echo $before_title . 'thunderstorm-stream' . $after_title;
        thunderstorm_stream_box();

        /* After widget (defined by themes). */
        echo $after_widget;
    }
}

################################################################################

add_action('admin_menu', 'thunderstorm_stream');

function thunderstorm_stream() {
    global $options;
    
    if ('plugin_save'== $_REQUEST['action'] ) {
        foreach ($options['configuration'] as $value) {
            if( !isset( $_REQUEST[ $value['id'] ] ) ) {
                //Do nothing
            } else {
                update_option( $value['id'], stripslashes($_REQUEST[ $value['id']]));
            }
        }

        if(stristr($_SERVER['REQUEST_URI'],'&saved=true')) {
            $location = $_SERVER['REQUEST_URI'];
        } else {
            $location = $_SERVER['REQUEST_URI'] . "&saved=true";
        }

        header("Location: $location");
        die;
    }

    add_menu_page('thunderstorm-stream', 'thunderstorm-stream', 'manage_options', 'thunderstorm-stream', 'thunderstorm_stream_admin');
}

function thunderstorm_stream_admin() {
    global $options;
    
    if (!current_user_can('manage_options'))  {
        wp_die(l('Sie haben nicht die benötigten Berechtigungen, um diese Seite zu besuchen!'));
    } else {
        ?>
            <form method="post" id="myForm">
                <table class="form-table">
                    <?php
                    foreach ($options['configuration'] as $value) {
                        switch ($value['type']) {
                            case "textbox": {
                                ?>
                                <tr>
                                    <td valign="top" colspan="2">
                                        <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="320px">
                                        <?php echo $value['desc'] ?>
                                    </td>
                                    <td valign="top">
                                        <input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php get_option($value['id'])?printf(get_option($value['id'])): printf($value['default']) ?>" />
                                    </td>
                                </tr>
                                <?php
                                break;
                            }

                            case "textarea": {
                                ?>
                                <tr>
                                    <td valign="top" colspan="2">
                                        <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="320px">
                                        <?php echo $value['desc'] ?>
                                    </td>
                                    <td valign="top">
                                        <textarea style="width:400px;height:120px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php get_option($value['id'])?printf(get_option($value['id'])): printf($value['default']) ?>"></textarea>
                                    </td>
                                </tr>
                                <?php
                                break;
                            }

                            case "radio": {
                                ?>
                                <tr>
                                    <td valign="top" colspan="2">
                                        <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="320px">
                                        <?php echo $value['desc'] ?>
                                    </td>
                                    <td valign="top">
                                        <?php
                                            foreach ($value["values"] as $xv) {
                                                if (get_option($value['id']) == $xv['value']) {
                                                    echo '<label><input type="radio" name="'. $value['id']. '" id="'. $value['id']. '" value="'. $xv["value"]. '" checked="checked" />'. $xv["string"]. '</label>&nbsp;&nbsp;';
                                                } else {
                                                    echo '<label><input type="radio" name="'. $value['id']. '" id="'. $value['id']. '" value="'. $xv["value"]. '" />'. $xv["string"]. '</label>&nbsp;&nbsp;';
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                break;
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="3"><input type="hidden" name="action" value="plugin_save" /><p><input name="theme_save" type="submit" class="button-primary" value="Änderungen speichern" /><input type="reset" value="Zurücksetzen" /></p></td>
                    </tr>
                </table>
            </form>
        <?php
    }
}

################################################################################

function thunderstorm_stream_box() {
    $url = 'http://api.thunderstorm-networx.de/?lang=de&a=ua&user='. get_option('thunderstorm_api_user'). '&c='. get_option('thunderstorm_api_user_activity_count');
    $handle = fopen ($url, "r");
    $contents = stream_get_contents($handle);
    fclose($handle);
    $json = json_decode($contents);
    if ($json->status != 'ok') {
        echo '<b>Es ist ein Fehler aufgetreten:</b> '. $json->error;
    } else {
        $elements = $json->return;
        foreach ($elements as $element) {
            if ($element->type == 'tweet') {
                $ptime = strtotime($element->date_recorded);
                $content = make_clickable($element->content);
                $user = get_option('thunderstorm_api_user');
                $link = 'http://www.thunderstorm-networx.de/activity/p/'. $element->id;
                echo '<p style="word-break: break-all;"><b><a href="http://www.thunderstorm-networx.de/members/'. $user. '" target="_blank">'. $user. '</a> am '. date('d.m.y \u\m H:i:s', $ptime). '</b><br />'. $content. ' [<a href="'. $link. '" target="_blank" title="Beitrag anzeigen">#</a>] <span style="font-size: 10px;">via Twitter</span></p>';
            } elseif ($element->type == 'created_group') {
                $ptime = strtotime($element->date_recorded);
                $user = get_option('thunderstorm_api_user');
                $link = 'http://www.thunderstorm-networx.de/activity/p/'. $element->id;
                echo '<p style="word-break: break-all;"><b><a href="http://www.thunderstorm-networx.de/members/'. $user. '" target="_blank">'. $user. '</a> am '. date('d.m.y \u\m H:i:s', $ptime). '</b><br />'. $user. ' hat eine Gruppe gegründet.</p>';
            } elseif ($element->type == 'created_group') {
                $ptime = strtotime($element->created_event);
                $user = get_option('thunderstorm_api_user');
                $link = 'http://www.thunderstorm-networx.de/activity/p/'. $element->id;
                echo '<p style="word-break: break-all;"><b><a href="http://www.thunderstorm-networx.de/members/'. $user. '" target="_blank">'. $user. '</a> am '. date('d.m.y \u\m H:i:s', $ptime). '</b><br />'. $user. ' hat eine Veranstaltung erstellt.</p>';
            } elseif ($element->type == 'activity_liked') {
                $ptime = strtotime($element->created_event);
                $user = get_option('thunderstorm_api_user');
                $link = 'http://www.thunderstorm-networx.de/activity/p/'. $element->id;
                echo '<p style="word-break: break-all;"><b><a href="http://www.thunderstorm-networx.de/members/'. $user. '" target="_blank">'. $user. '</a> am '. date('d.m.y \u\m H:i:s', $ptime). '</b><br />'. $user. ' gefällt (s)eine Aktivität.</p>';
            } else {
                $ptime = strtotime($element->date_recorded);
                $content = make_clickable($element->content);
                $user = get_option('thunderstorm_api_user');
                $link = 'http://www.thunderstorm-networx.de/activity/p/'. $element->id;
                echo '<p style="word-break: break-all;"><b><a href="http://www.thunderstorm-networx.de/members/'. $user. '" target="_blank">'. $user. '</a> am '. date('d.m.y \u\m H:i:s', $ptime). '</b><br />'. $content. ' [<a href="'. $link. '" target="_blank" title="Beitrag anzeigen">#</a>]</p>';
            }
        }
    }
    echo '<div style="font-size: 10px">Powered by <a href="http://www.thunderstorm-networx.de/">thunderstorm-networx</a></div>';
}

################################################################################

?>