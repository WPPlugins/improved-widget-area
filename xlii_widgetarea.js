/** 
 * @author 42functions
 * @version 1.0
 */

/* WIDGET PAGE --------------------------------------------------------------------------------- */
(function($){
    
    // Rebuild category list
    $(function(){
        if(typeof(xlii_iwa) != 'undefined')
        {
            document.body.id = 'xlii_iwa';
            var navigation = $('<ul id = "xlii_iwa_navigation"></ul>');
            var container = $('<ul id = "xlii_iwa_container"></ul>');
            var i = 0;
            $.each(xlii_iwa, function(category, element){
                navigation.append('<li rel = "xlii_sidebar_' + i + '">' + category + '</li>');
                container.append('<li id = "xlii_sidebar_' + i + '"></li>');

                $.each(element, function(key, id){ container.children('#xlii_sidebar_' + i).append($('#' + id).parent()); });
                i++;
            });
        }
        
        $('#widgets-right').addClass('xlii-right').html(
            '<div class="sidebar-holder-wrap">' + 
	            '<div class="sidebar-name">' +
		            '<div class="sidebar-name-arrow"><br></div>' + 
		            '<h3 id = "xlii_iwa_title"></h3>' +
		        '</div>' + 
        		'<div id = "xlii_iwa_holder" class = "sidebar-holder"></div>' +
            '</div>'
        );
        
        $('#xlii_iwa_holder').append(navigation).append(container).append('<br class = "_clear" />');
        $('#xlii_iwa_navigation li:first-child').trigger('click');
    });
    
    // Add navigation support
    $('#xlii_iwa_navigation li').live('click', function(){
        if(!$(this).hasClass('active'))
        {
            // Remove current active element
            $('#xlii_iwa_navigation .active, #xlii_iwa_container .active').removeClass('active');
            
            $('#' + $(this).addClass('active').attr('rel')).addClass('active');
            $('#xlii_iwa_title').html($(this).html());
        }
    });
    
    // Add toggle support
    $('.xlii-right .sidebar-name').live('click', function(){ $(this).siblings('.sidebar-holder').toggle(); });
    $('#xlii_iwa_container > li > .widgets-holder-wrap > .sidebar-name').live('click', function(){ 
        $(this).siblings('.widgets-sortables')
            .toggle()
            .sortable(
                $(this).parents('.widgets-holder-wrap:eq(0)').toggleClass('closed').hasClass('closed') ? 'disable' : 'enable'
            );
    });
    
})(jQuery);