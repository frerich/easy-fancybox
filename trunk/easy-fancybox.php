<?php
/*
Plugin Name: Easy FancyBox
Plugin URI: http://4visions.nl/en/wordpress-plugins/easy-fancybox/
Description: Easily enable the <a href="http://fancybox.net/">FancyBox 1.3.3 jQuery extension</a> on all image, SWF, YouTube and Vimeo links. Multi-Site compatible and supports iFrame and Flash movies in overlay viewport. Happy with it? Please leave me a small <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Easy%20FancyBox&amp;item_number=1%2e3%2e3&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=us">TIP</a> for development and support on this plugin and please consider a DONATION to the <a href="http://fancybox.net/">FancyBox project</a>.
Version: 1.3.3.4.1
Author: RavanH
Author URI: http://4visions.nl/
*/

// FUNCTIONS //

function easy_fancybox_settings(){
	return array(
		'autoAttribute' => array (
			'id' => 'fancybox_autoAttribute',
			'title' => __('Auto-enable','easy-fancybox'),
			'label_for' => 'fancybox_autoAttribute',
			'input' => 'text',
			'class' => 'regular-text',
			'options' => array(),
			'hide' => 'true',
			'default' => 'jpg gif png bmp jpeg jpe swf',
			'description' => __('Enter file types FancyBox should be automatically enabled for. Clear field to switch off auto-enabling.','easy-fancybox')
			),
		'autoDetect' => array (
			'id' => 'fancybox_autoDetect',
			'title' => __('Auto-detect','easy-fancybox'),
			'label_for' => '',
			'input' => 'multiple',
			'class' => '',
			'options' => array(
				'autoAttributeYoutube' => array (
					'id' => 'fancybox_autoAttributeYoutube',
					'label_for' => '',
					'input' => 'checkbox',
					'class' => '',
					'options' => array(),
					'hide' => 'true',
					'default' => '1',
					'description' => __('YouTube links.','easy-fancybox')
					),
				'autoAttributeYoutubeShortURL' => array (
					'id' => 'fancybox_autoAttributeYoutubeShortURL',
					'label_for' => '',
					'input' => 'checkbox',
					'class' => '',
					'options' => array(),
					'hide' => 'true',
					'default' => '',
					'description' => __('Short URL YouTube links.','easy-fancybox')
					),
				'autoAttributeVimeo' => array (
					'id' => 'fancybox_autoAttributeVimeo',
					'label_for' => '',
					'input' => 'checkbox',
					'class' => '',
					'options' => array(),
					'hide' => 'true',
					'default' => '1',
					'description' => __('Vimeo links.','easy-fancybox')
					)
				),
			'hide' => 'true',
			'description' => __('Select which external video content sites links should automatically be detected and FancyBox enabled.','easy-fancybox')
			),
		'titlePosition' => array (
			'id' => 'fancybox_titlePosition',
			'title' => __('Title Position','easy-fancybox'),
			'label_for' => 'fancybox_titlePosition',
			'input' => 'select',
			'class' => '',
			'options' => array(
				'over' => __('Overlay','easy-fancybox'),
				'inside' => __('Inside','easy-fancybox'),
				'outside' => __('Outside','easy-fancybox')
				),
			'default' => 'over',
			'description' => __('Position of the overlay content title.','easy-fancybox')
			),
		'transitionIn' => array (
			'id' => 'fancybox_transitionIn',
			'title' => __('Transition In','easy-fancybox'),
			'label_for' => 'fancybox_transitionIn',
			'input' => 'select',
			'class' => '',
			'options' => array(
				'elastic' => __('Elastic','easy-fancybox'),
				'fade' => __('Fade in','easy-fancybox'),
				'none' => __('None','easy-fancybox')
				),
			'default' => 'elastic',
			'description' => __('Transition effect when opening the overlay.','easy-fancybox')
			),
		'transitionOut' => array (
			'id' => 'fancybox_transitionOut',
			'title' => __('Transition Out','easy-fancybox'),
			'label_for' => 'fancybox_transitionOut',
			'input' => 'select',
			'class' => '',
			'options' => array(
				'elastic' => __('Elastic','easy-fancybox'),
				'fade' => __('Fade out','easy-fancybox'),
				'none' => __('None','easy-fancybox')
				),
			'default' => 'elastic',
			'description' => __('Transition effect when closing the overlay.','easy-fancybox')
			),
		);
}

function easy_fancybox() {
	$easy_fancybox_array = easy_fancybox_settings();
	
	// begin output FancyBox settings
	echo "
<!-- Easy FancyBox plugin for WordPress - RavanH (http://4visions.nl/en/wordpress-plugins/easy-fancybox/) -->
<script type=\"text/javascript\">
jQuery(document).ready(function($){";
	
	$file_types = array_filter( explode( ' ', get_option( 'fancybox_autoAttribute', $easy_fancybox_array['autoAttribute']['default']) ) );
	
	// add auto-detection image/swf links
	if(!empty($file_types)) {
		echo "
	$('";
		foreach ($file_types as $type)
			echo 'a[href$=".'.$type.'"],a[href$=".'.strtoupper($type).'"],';
		echo "')
		.attr('rel', 'gallery')
		.addClass('fancybox');";
	}

	// add auto-attribution for Youtube links
	if( "1" == get_option("fancybox_autoAttributeYoutube", $easy_fancybox_array['autoDetect']['options']['autoAttributeYoutube']['default']) )
		echo "
	$('a[href*=\"youtube.com/watch\"]').addClass('fancybox-youtube');";

	// add auto-attribution for Vimeo links
	if( "1" == get_option("fancybox_autoAttributeVimeo", $easy_fancybox_array['autoDetect']['options']['autoAttributeVimeo']['default']) )
		echo "
	$('a[href*=\"vimeo.com/\"]').addClass('fancybox-vimeo');
	";

	// image/swf fancybox settings
	echo "
	$('a.fancybox').fancybox({";
	foreach ($easy_fancybox_array as $key => $values)
		if('true'!=$values['hide'])
			echo "
		'".$key."'	: '".get_option($values['id'], $values['default'])."',";
	
	if( "over" == get_option("fancybox_titlePosition", $easy_fancybox_array['titlePosition']['default']) )
		echo"
		'onComplete'	: function() {
			$('#fancybox-wrap').hover(function() {
				$('#fancybox-title').show();
			}, function() {
				$('#fancybox-title').hide();
			});
		},";
	echo"
		'autoDimensions': false,
		'titleFromAlt'	: true
	});
	";
	
	// iframe/swf/youtube/vimeo settings
	echo"
	var fb_opts = {
		'titleShow'	: false,
		'padding'	: 0,
		'autoScale'	: false,
		'transitionIn'	: 'none',
		'transitionOut'	: 'none',
		'swf'		: {
		   	'wmode'			: 'opacity',
			'allowfullscreen'	: 'true'
		}
	};
	$('a.fancybox-iframe').fancybox(
		$.extend(fb_opts, {
			'type'		: 'iframe',
			'height'	: '90%',
			'width'		: '70%'
		})
	);
	$('a.fancybox-swf').fancybox(
		$.extend(fb_opts, {
			'margin'	: 0,
			'type'		: 'swf'
		})
	);
	$('a.fancybox-youtube').click(function(){
		$.fancybox(
			$.extend(fb_opts, {
				'type'		: 'swf',
				'width'		: 640,
				'height'	: 385,
				'href'		: this.href.replace(new RegExp('watch\\\?v=', 'i'), 'v/')
			})
		);
		return false;
	});";
	if( "1" == get_option("fancybox_autoAttributeYoutubeShortURL", $easy_fancybox_array['autoDetect']['options']['autoAttributeYoutubeShortURL']['default']) )
		echo "
	$('a[href*=\"youtu.be/\"]').click(function(){
		$.fancybox(
			$.extend(fb_opts, {
				'type'		: 'swf',
				'width'		: 640,
				'height'	: 385,
				'href'		: this.href.replace(new RegExp('youtu.be', 'i'), 'www.youtube.com/v')
			})
		);
		return false;
	});";
	echo "
	$('a.fancybox-vimeo').click(function() {
		$.fancybox(
			$.extend(fb_opts, {
				'width'		: 640,
				'height'	: 360,
				'href'		: this.href.replace(new RegExp('([0-9])', 'i'), 'moogaloop.swf?clip_id=$1')
			})
		);
		return false;
	});
});
</script>
";
}

// FancyBox Media Settings Section on Settings > Media admin page
function easy_fancybox_settings_section() {
	echo '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Easy%20FancyBox&item_number=&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=us" title="'.__('Donate to Easy FancyBox plugin development with PayPal - it\'s fast, free and secure!','easy-fancybox').'"><img src="https://www.paypal.com/en_US/i/btn/x-click-but7.gif" style="border:none;float:right;margin:0 0 10px 10px" alt="'.__('Donate to Easy FancyBox plugin development with PayPal - it\'s fast, free and secure!','easy-fancybox').'" /></a><p>'.__('To manualy enable FancyBox for a link to an attached image or swf movie file, you can use the tags class="fancybox" or class="fancybox-swf". To make a link to any web page show in a FancyBox overlay, use class="fancybox-iframe". Use the tags class="fancybox-youtube" on a YouTube link and class="fancybox-vimeo" on a Vimeo link to manually enable FancyBox for it. Read more on <a href="http://4visions.nl/en/wordpress-plugins/easy-fancybox/">Easy FancyBox for WordPress</a>.','easy-fancybox').'</p><p>'.__('The settings listed below determine the image overlay behaviour controlled by FancyBox.','easy-fancybox').' '.__('Some setting like Title Position and Transition are ignored for swf video and iframe content overlays to improve browser compatibility and readability.','easy-fancybox').'</p>';
}
// FancyBox Media Settings Fields
function easy_fancybox_settings_fields($args){
	switch($args['input']) {
		case 'multiple':
			foreach ($args['options'] as $options)
				easy_fancybox_settings_fields($options);
			echo $args['description'];
			break;
		case 'select':
			echo '
			<select name="'.$args['id'].'" id="'.$args['id'].'">';
			foreach ($args['options'] as $optionkey => $optionvalue) {
				$selected = (get_option($args['id'], $args['default']) == $optionkey) ? ' selected="selected"' : '';
				echo '
				<option value="'.esc_attr($optionkey).'"'.$selected.'>'.$optionvalue.'</option>';
			}
			echo '
			</select> ';
			if( empty($args['label_for']) )
				echo '<label for="'.$args['id'].'">'.$args['description'].'</label> <em>'.__('Default:','easy-fancybox').' '.$args['options'][$args['default']].'</em>';
			else
				echo $args['description'].' <em>'.__('Default:','easy-fancybox').' '.$args['options'][$args['default']].'</em>';
			break;
		case 'checkbox':
			$value = esc_attr( get_option($args['id'], $args['default']) );
			if ($value == "1")
				$checked = ' checked="checked"';
			else
				$checked = '';
			if ($args['default'] == "1")
				$default = __('Checked','easy-fancybox');
			else
				$default = __('Unchecked','easy-fancybox');
			echo '
			<label><input type="checkbox" name="'.$args['id'].'" id="'.$args['id'].'" value="1" '.$checked.'/> '.$args['description'].'</label> <em>'.__('Default:','easy-fancybox').' '.$default.'</em><br />';
			break;
		case 'text':
			echo '
			<input type="text" name="'.$args['id'].'" id="'.$args['id'].'" value="'.esc_attr( get_option($args['id'], $args['default']) ).'" class="'.$args['class'].'"/><br />';
			if( empty($args['label_for']) )
				echo '<label for="'.$args['id'].'">'.$args['description'].'</label> <em>'.__('Default:','easy-fancybox').' '.$args['default'].'</em>';
			else
				echo $args['description'].' <em>'.__('Default:','easy-fancybox').' '.$args['default'].'</em>';
			break;
		default:
			echo $args['description'];
	}
}


function easy_fancybox_admin_init(){
	load_plugin_textdomain('easy-fancybox', false, dirname(plugin_basename( __FILE__ )));

	add_settings_section('fancybox_section', __('FancyBox','easy-fancybox'), 'easy_fancybox_settings_section', 'media');
	
	$easy_fancybox_array = easy_fancybox_settings();
	foreach ($easy_fancybox_array as $key => $value) {
		add_settings_field( 'fancybox_'.$key, $value['title'], 'easy_fancybox_settings_fields', 'media', 'fancybox_section', $value);
		if ($value['input']=='multiple')
			foreach ($value['options'] as $_key => $_value)
				register_setting( 'media', 'fancybox_'.$_key );	
		else
			register_setting( 'media', 'fancybox_'.$key ); 
	}
}

function easy_fancybox_enqueue() {
	// check if fancy.php is moved one dir up like in WPMU's /mu-plugins/
	// NOTE: don't use WP_PLUGIN_URL to avoid problems when installed in /mu-plugins/
	$efb_subdir = (file_exists(dirname(__FILE__).'/easy-fancybox')) ? 'easy-fancybox' : '';

	// ENQUEUE
	// register main fancybox script
	wp_enqueue_script('jquery.fancybox', plugins_url($efb_subdir, __FILE__).'/fancybox/jquery.fancybox-1.3.3.pack.js', array('jquery'), '1.3.3');
	
	if( "none" != get_option("fancybox_transitionIn") || "none" != get_option("fancybox_transitionOut") ) {
		// first get rid of previously registered variants of jquery.easing (by other plugins)
		wp_deregister_script('jquery.easing');
		wp_deregister_script('jqueryeasing');
		wp_deregister_script('jquery-easing');
		wp_deregister_script('easing');
		// then register our version
		wp_enqueue_script('jquery.easing', plugins_url($efb_subdir, __FILE__).'/fancybox/jquery.easing-1.3.pack.js', array('jquery'), '1.3');
	}
	
	// first get rid of previously registered variants of jquery.mousewheel (by other plugins)
	wp_deregister_script('jquery.mousewheel');
	wp_deregister_script('jquerymousewheel');
	wp_deregister_script('jquery-mousewheel');
	wp_deregister_script('mousewheel');
	// then register our version
	wp_enqueue_script('jquery.mousewheel', plugins_url($efb_subdir, __FILE__).'/fancybox/jquery.mousewheel-3.0.4.pack.js', array('jquery'), '3.0.4');
	
	// register style
	wp_enqueue_style('jquery.fancybox', plugins_url($efb_subdir, __FILE__).'/jquery.fancybox.css.php', false, '1.3.3');
}

// HOOKS //

add_action('wp_enqueue_scripts', 'easy_fancybox_enqueue', 999);
add_action('wp_head', 'easy_fancybox');

add_action('admin_init','easy_fancybox_admin_init');
