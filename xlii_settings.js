/** 
 * @author 42functions
 * @version 1.0
 */

/* ADMIN PAGE ---------------------------------------------------------------------------------- */
(function($){
    // Append sortable functionality
    $(function(){ 
        $('#xlii_categories .container').sortable(); 
        $('#xlii_categories').sortable({
            placeholder : 'xlii_placeholder_category',
            revert : true,
            helper : 'original',
            cursor : 'crosshair'
        }); 
    });
    $('#xlii_categories .container').live('sortcreate', function(){
        $(this).sortable('option', {
            connectWith : '#xlii_categories .container',
            placeholder : 'xlii_placeholder_sidebar',
            revert : true,
            helper : 'original',
            cursor : 'crosshair'
        });
    });

    // Append new category support
    $('#xlii_cat_add').live('click', function(){
        $('#xlii_categories').append('<li>' + $('#xlii_template').html() + '<ul class = "container"></ul></li>');
        $('#xlii_categories li:last-child .item-edit').trigger('click');
        $('#xlii_categories li:last-child .container').sortable();
    });

    // Edit name functionality
    $('#xlii_categories .item.category').live('dblclick', function(){ $(this).find('.item-edit').trigger('click'); });
    $('#xlii_categories .item.category .item-edit').live('click', function(){
        if($('#xlii_edit_title').length)
            $('#xlii_edit_title').trigger('blur');
        if(!$(this).hasClass('inactive'))
        {
            $(this).parent().siblings('.item-title').html('<input type = "text" name = "title" id = "xlii_edit_title" value = "' + $(this).parent().siblings('.item-title').html() + '" /> ');
            $(this).addClass('inactive');
            $('#xlii_edit_title').focus();
        }
    });

    $('#xlii_edit_title').live('keyup', function(event){ if(event.keyCode == 13) $(this).trigger('blur'); });
    $('#xlii_edit_title').live('blur', function(){
        $(this).parent()
            .siblings('input')
                .val('category:' + this.value);

        $(this).parent()
            .html(this.value)
            .siblings('.controll')
                .children('.item-edit').addClass('queue');
        
        // Set timeout to allow people to press the edit button while in the editing mode
        setTimeout(function(){$('.item-edit.queue').removeClass('queue').removeClass('inactive'); }, 100);
    });
    
    $('#xlii_categories li .container').live('sortstart', function(){ if($('#xlii_edit_title').length) $('#xlii_edit_title').trigger('blur'); });

    // Append removal support
    $('#xlii_categories .item-remove').live('click', function(){ var parent = $(this).parents('li:eq(0)'); if(parent.find('.item.sidebar').length == 0) parent.remove(); });
    $('#xlii_categories .item.category').live('mouseenter', function(){ if($(this).parents('li:eq(0)').find('.item.sidebar').length == 0) $('.item-remove', this).addClass('active'); });
    $('#xlii_categories .item.category').live('mouseleave', function(){ $('.item-remove.active', this).removeClass('active'); });

    // Disable autosubmit when pressed enter
    $('#xlii_form').live('submit', function(){
        return $('#xlii_edit_title').length == 0;
    });

})(jQuery)