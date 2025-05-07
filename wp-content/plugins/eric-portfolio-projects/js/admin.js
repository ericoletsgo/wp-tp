jQuery(document).ready(function($) {
    $('.upload_image_button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var imageField = button.prev('input[type="url"]');
        var imagePreview = button.parent().next('img');
        
        var frame = wp.media({
            title: 'Select or Upload Project Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            imageField.val(attachment.url);
            
            if (imagePreview.length) {
                imagePreview.attr('src', attachment.url);
            } else {
                button.parent().after('<img src="' + attachment.url + '" style="max-width:200px;height:auto;display:block;margin-top:5px;">');
            }
        });

        frame.open();
    });
}); 