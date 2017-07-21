<?php
/** 
 * @author 42functions
 * @version 1.0
 */

add_action('setup_theme', 'xlii_iwa_register_widgets');

function xlii_iwa_register_widgets()
{
    class XLII_IWA_Widget_Sidebar extends WP_Widget 
    {
        protected static $_exclude = array();
        private $_sidebar;
        
    	function XLII_IWA_Widget_Sidebar() 
    	{
    		$widget_ops = array('classname' => 'xlii_iwa_widget_sidebar', 'description' => __( 'Renders the widgets of another sidebar.') );
    		$this->WP_Widget('xlii_iwa_sidebar', __('42functions duplicate sidebar'), $widget_ops);
    	}

        function applyFilters($args)
        {
            if($args[0]['id'] == $this->_sidebar['render'])
            {
                global $wp_registered_sidebars, $wp_registered_widgets;
            
                $args[0]['after_widget'] = $wp_registered_sidebars[$this->_sidebar['current']]['after_widget'];
                $args[0]['before_title'] = $wp_registered_sidebars[$this->_sidebar['current']]['before_title'];
                $args[0]['after_title'] = $wp_registered_sidebars[$this->_sidebar['current']]['after_title'];
	        
    	        // Substitute HTML id and class attributes into before_widget
    		    $classname_ = '';
        		foreach ( (array) $wp_registered_widgets[$args[0]['widget_id']]['classname'] as $cn ) {
        			if ( is_string($cn) )
        				$classname_ .= '_' . $cn;
        			elseif ( is_object($cn) )
        				$classname_ .= '_' . get_class($cn);
        		}
        		$classname_ = ltrim($classname_, '_');
        		$args[0]['before_widget'] = sprintf($wp_registered_sidebars[$this->_sidebar['current']]['before_widget'], $args[0]['widget_id'], $classname_);
            }
		    return $args;
        }

    	function widget( $args, $instance ) 
    	{
    	    if($instance['sidebar'] && $args['id'] != $instance['sidebar'] && !in_array($args['id'], self::$_exclude))
    	    {
    	        // Prevent the sidebar renderer gets into a loop
    	        $this->_sidebar = array(
    	           'current' => self::$_exclude[] = $args['id'],
    	           'render' => $instance['sidebar']
    	        );
    	        
    	        add_filter('dynamic_sidebar_params', array(&$this, 'applyFilters'));
		        dynamic_sidebar($this->_sidebar['render']);
		        remove_filter('dynamic_sidebar_params', array(&$this, 'applyFilters'));
		        
		        unset(self::$_exclude[array_search($_sidebar['current'], self::$_exclude)]);
		    }
    	}

    	function update( $new_instance, $old_instance ) 
    	{
    		return $new_instance;
    	}

    	function form( $instance ) 
    	{
    	    global $wp_registered_sidebars;
	    
    		//Defaults
    		$instance = wp_parse_args( (array) $instance, array( 'sidebar' => '' ) );
    	    ?>
		
    		<p><label for="<?php echo $this->get_field_id('sidebar'); ?>"><?php _e('Sidebar:'); ?></label> <select class="widefat" id="<?php echo $this->get_field_id('sidebar'); ?>" name="<?php echo $this->get_field_name('sidebar'); ?> />
    		<?php
    		foreach($wp_registered_sidebars as &$sidebar)
    		{
    		    if($sidebar['id'] != 'wp_inactive_widgets')
    		        echo '<option value = "'.$sidebar['id'].'"'.($instance['sidebar'] == $sidebar['id'] ? 'selected="selected"' : '').'>'.$sidebar['name'].'</option>';  
    		}
    		?>
    		</select>
    		</p>
		
            <?php 
    	}
    }

    register_widget('XLII_IWA_Widget_Sidebar');
}