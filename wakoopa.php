<?php
/*
Plugin Name: wakoopa widget
Plugin URI: http://www.afex2win.com/stuff/wakoopa-widget/
Description: Display your wakoopa badge or top 10 list in your sidebar
Version: 0.3b
Author: Keith 'afex' Thornhill
Author URI: http://www.afex2win.com/
*/

//$wakoopa_cachedir       = dirname(__FILE__) . DIRECTORY_SEPARATOR . "wakoopacache" . DIRECTORY_SEPARATOR;

function wakoopa_widget_init() {
    if ( !function_exists('register_sidebar_widget') )
		return;

    //global $wakoopa_cachedir;
    //if (!file_exists($wakoopa_cachedir)) {
    //    # try to create cachedir
    //    @mkdir($wakoopa_cachedir);
    //}

	function widget_wakoopa($args) {
		extract($args);

		$options = get_option('widget_wakoopa');
		$title = $options['title'];
		$username = $options['username'];
        $use_text = $options['use_text'];
        $list_type = $options['list_type'];
        
		echo $before_widget . $before_title . $title . $after_title;
		if ($username) {
            if (!$use_text) {
                switch ($list_type) {                        
                    case 'recent':
                        ?>
            		    <a href="http://wakoopa.com/<?=$username?>"><img src="http://wakoopa.com/<?=$username?>/badge/recent.png" alt="My recently used software" title="My recently used software" /></a>
            		    <?
                        break;
                        
                    case 'new':
                        ?>
            		    <a href="http://wakoopa.com/<?=$username?>"><img src="http://wakoopa.com/<?=$username?>/badge/new.png" alt="My newly used software" title="My newly used software" /></a>
            		    <?
                        break;
                        
                    case 'top':
                    default:
            		    ?>
            		    <a href="http://wakoopa.com/<?=$username?>"><img src="http://wakoopa.com/<?=$username?>/badge.png" alt="My top 10 software" title="My top 10 software" /></a>
            		    <?
                        break;
                }
    		} else {
    		    ?>
    		    <h2><a href="http://wakoopa.com/<?=$username?>">My top 10 software</a></h2><script type="text/javascript" src="http://wakoopa.com/<?=$username?>/widget"></script>
    		    <?
    		}
		} else {
		    ?>
		    Please input your wakoopa username.
		    <?
		}
		echo $after_widget;
	}

	function widget_wakoopa_control() {
		$options = get_option('widget_wakoopa');

		if ( !is_array($options) )
			$options = array('title'=>'wakoopa','username'=>'', 'use_text'=>false);

		if ( $_POST['wakoopa-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['wakoopa-title']));
			$options['username'] = strip_tags(stripslashes($_POST['wakoopa-username']));
			$options['use_text'] = ($_POST['wakoopa-text']?true:false);
			$options['list_type'] = $_POST['wakoopa-list-type'];
			update_option('widget_wakoopa', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$username = htmlspecialchars($options['username'], ENT_QUOTES);
		$use_text = $options['use_text'];
		$list_type = $options['list_type'];
		?>
		<p style="text-align:left;">
		    <label for="wakoopa-title"><?=__('Title:')?>
		        <input style="width: 200px;" id="wakoopa-title" name="wakoopa-title" type="text" value="<?=$title?>" />
		    </label>
		</p>
		<p style="text-align:left;">
		    <label for="wakoopa-username"><?=__('wakoopa Username:')?>
		        <input style="width: 100px;" id="wakoopa-title" name="wakoopa-username" type="text" value="<?=$username?>" /><br/>
		        <i>Don't have a wakoopa account? <a href="http://wakoopa.com/account/signup">Get one here</a></i>
		    </label>
		</p>
		<p style='text-align:left;'>
		    <input type="radio" name="wakoopa-list-type" value="top" id="wakoopa-list-type-top"<?= ($list_type=='top' ? ' checked' : '') ?>><label for="wakoopa-list-type-top">Top 10</label><br/>
		    <input type="radio" name="wakoopa-list-type" value="recent" id="wakoopa-list-type-recent"<?= ($list_type=='recent' ? ' checked' : '') ?>><label for="wakoopa-list-type-recent">Recently used</label><br/>
		    <input type="radio" name="wakoopa-list-type" value="new" id="wakoopa-list-type-new"<?= ($list_type=='new' ? ' checked' : '') ?>><label for="wakoopa-list-type-new">Newly used</label><br/>
		</p>
		<p style="text-align:left;">
		    <label for="wakoopa-text"><?=__('Use text instead of image? (Top 10 only)')?>
		        <input id="wakoopa-text" name="wakoopa-text" type="checkbox" <?=($use_text?'checked':'')?> />
		    </label>
		</p>
		<input type="hidden" id="wakoopa-submit" name="wakoopa-submit" value="1" />
		<?
	}

	register_sidebar_widget(array('wakoopa', 'widgets'), 'widget_wakoopa');

	register_widget_control(array('wakoopa', 'widgets'), 'widget_wakoopa_control', 300, 200);
}


// hooks
add_action('widgets_init', 'wakoopa_widget_init');

?>