<?php
/*
Plugin Name: 42functions Improved widget area
Version: 1.0
Author: 42functions
Author URI: http://42functions.nl/
Description: Take your widget area to the next level by categorising it.
*/ 

// 42functions author: Ferdy Perdaan (Ferdy@42functions.nl)

// Only load our plugin when we need to


include_once('xlii_widget.php');

if(!is_admin())
    return;

add_action('admin_menu', 'xlii_iwa_adminmenu');

function xlii_iwa_adminmenu() { add_options_page('Improved Widget Area', 'Improved Widget Area', 9, 'xlii-iwa', 'xlii_iwa_adminpage'); }

global $pagenow;

wp_register_script('xlii_iwa_settings', substr(dirname(__FILE__), strlen(ABSPATH) - 1).'/xlii_settings.js', array('jquery','jquery-ui-sortable'));
wp_register_script('xlii_iwa_widgetarea', substr(dirname(__FILE__), strlen(ABSPATH) - 1).'/xlii_widgetarea.js', array('jquery'));
wp_register_style('xlii_iwa_style', substr(dirname(__FILE__), strlen(ABSPATH) - 1).'/xlii_iwa.css');

if($pagenow == 'options-general.php' && $_GET['page'] == 'xlii-iwa')
{
    wp_enqueue_script('xlii_iwa_settings');
    wp_enqueue_style('xlii_iwa_style');
    
    function xlii_iwa_adminpage()
    {
        global $wp_registered_sidebars;
    
        if(isset($_POST['save']) && isset($_POST['xlii_cat']))
            xlii_iwa_adminprocess(isset($_POST['xlii_enable']) && $_POST['xlii_enable'], $_POST['xlii_cat']);
    
    
        $theme   = get_current_theme();
        $options = get_option('xlii_iwa', array());
        $options = isset($options[$theme]) ? $options[$theme] : array('enabled' => true, 'cat' => array('other' => array()));
    
        // Format categories
        if(isset($_GET['autoformat']) || !count($options['cat']))
        {
            $options['cat'] = array();
            foreach($wp_registered_sidebars as &$sidebar)
            {
                if(!isset($sidebar['category']))
                {
                    if(count($cat = preg_split('/\-|\_/', $sidebar['id'])) >= 2)
                        $cat = $cat[0];
                    else
                        $cat = 'other';
                }
                else
                {
                    $cat = $sidebar['category'];
                }    
                if(!isset($options['cat'][$cat]))
                    $options['cat'][$cat] = array();
            
                $options['cat'][$cat][] = $sidebar['id'];
            }
        
            $_GET['autoformat'] = true;
            unset($sidebar);
        }
        else
        {
            // Make sure every sidebar is categorised
            $ids = array();
            foreach($options['cat'] as &$category)
                $ids = array_merge($ids, $category);
                
            if(!isset($options['cat']['other']))    
                $options['cat']['other'] = array_diff(array_keys($wp_registered_sidebars), $ids);
            else
                $options['cat']['other'] = array_merge($options['cat']['other'], array_diff(array_keys($wp_registered_sidebars), $ids));

            unset($category);
        }
    
        if(!count($options['cat']['other']))
            unset($options['cat']['other']);

        ?>
    
        <?php screen_icon(); ?>
        <h2><?php _e('Improved Widget Area', 'xlii_iwa'); ?></h2>
        <div id = "xlii_admin" class="wrap">	
            <div id = "xlii_header">
                <div class = "theme">
            	    <?php _e('Active theme', 'xlii_smt'); ?>
                	<span class = "name"><?php echo $theme; ?></span>
                </div>
            </div>
        
            <?php if(count($wp_registered_sidebars)): ?>
                <form id = "xlii_form" action="<?php echo attribute_escape( $_SERVER['REQUEST_URI'] ); ?>" method="post">
                    <div class = "enable">
                        <input type = "checkbox" name = "xlii_enable" id = "xlii_enable" <?php checked($options['enabled']); ?> /><label for = "xlii_enable"><?php _e('Enable an improved widget area for this theme.', 'xlii_iwa'); ?></label>
                    </div>
                	<ul id = "xlii_categories">
                    <?php
                
                    foreach($options['cat'] as $category => &$content)
                    {
                        echo '<li>'.xlii_iwa_generate_item('category', $category, $category).'<ul class = "container">';
                        foreach($content as $id)
                            echo '<li class = "sidebar">'.xlii_iwa_generate_item('sidebar', $id, $wp_registered_sidebars[$id]['name']).'</li>';
                        echo '</ul></li>';
                    }
                    ?>
                    </ul>
                    <span id = "xlii_cat_add"><?php _e('Add new category', 'xlii_iwa'); ?></span>
                    <div class = "buttons">
                		<span class="submit"><input name="save" value="<?php _e('Save state', 'xlii_iwa'); ?>" type="submit" /></span>
            		    <?php if(!isset($_GET['autoformat'])): ?>
                            <a class = "autoformat button" href = "<?php echo attribute_escape( $_SERVER['REQUEST_URI'] ).'&autoformat'; ?>"><?php _e('Autoformat categories', 'xlii_iwa'); ?></a>
                        <?php endif;  
                        if(isset($_POST['save'])) _e('Settings saved');
                        ?>
                	</div>
                </form>
                <div id = "xlii_template"><?php echo xlii_iwa_generate_item('category', 'Sidebar', 'Sidebar'); ?></div>
            <?php else: _e('Your current theme doesn\'t support sidebars.', 'xlii_iwa'); endif; ?>
        </div>
        <?php
    }

    function xlii_iwa_adminprocess($enabled, array $values, $output = true)
    {
        global $wp_registered_sidebars;
        
        $categories = array();
        $category = null;
        $id = array();
        foreach($values as $value)
        {
            $value = explode(':', $value);
            if($value[0] == 'category')
            {
                $categories[$value[1]] = array();
                $category = &$categories[$value[1]];
            }
            else if(isset($wp_registered_sidebars[$value[1]]))
            {
                $id[] = $category[] = $value[1];
            }   
        }
        
        // Clean up empty categories
        foreach($categories as $key => &$content)
        {
            if(!count($content))
                unset($categories[$key]);
        }
        
        // Make sure every sidebar is added
        if(count($diff = array_diff(array_keys($wp_registered_sidebars), $id)))
        {
            if(isset($categories['other']))
                $categories['other'] = array_merge($categories['other'], $diff);
            else
                $categories['other'] = $diff;
        }
        
        $options = get_option('xlii_iwa', array());
        $options[get_current_theme()] = array('enabled' => $enabled && count($categories) >= 2, 'cat' => $categories);
        
        if($enabled && count($categories) < 2 && $output)
            echo '<div class = "error fade"><p>'.__('Not enough categories have been added, we automaticly disabled the plugin for the current theme.').'</p></div>';
        
        update_option('xlii_iwa', $options);
    }

    function xlii_iwa_generate_item($type, $id, $content)
    {
        return '<div class="item '.$type.'">
                    <span class="item-title">'.$content.'</span>'.($type == 'category' ? '<span class = "controll"><span class = "item-remove">'.__('Remove category').'</span><span class = "item-edit">'.__('Edit name').'</span></span>' : '').'
                    <input type = "hidden" name = "xlii_cat[]" value = "'.$type.':'.$id.'" />
                </div>';          
    }
}
else if($pagenow == 'widgets.php')
{
    $theme   = get_current_theme();
    $options = get_option('xlii_iwa', array());

    if(isset($options[$theme]) && $options[$theme]['enabled'])
    {
        wp_enqueue_script('xlii_iwa_widgetarea');
        wp_enqueue_style('xlii_iwa_style');
    
        // Append category list to page
        add_action('admin_head', 'xlii_iwa_head');
        function xlii_iwa_head()
        {
            $options = get_option('xlii_iwa', array());
            
            echo '<script style = "text/javscript">var xlii_iwa = '.json_encode($options[get_current_theme()]['cat']).';</script>';
        }
    }
}