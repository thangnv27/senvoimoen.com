/**
 * @Author: Ngo Van Thang
 * @Email: ngothangit@gmail.com
 */
var shortname = "ppo";
var CustomJS = {
    uploadSlider: function($){
        var custom_uploader;
        $('#upload_slide_img_button').click(function(e) {
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#slide_img').val(attachment.url);
            });

            //Open the uploader dialog
            custom_uploader.open();
        });

        $("#publish").click(function(event){
            var valid = true;
            if($("#slide_img").length > 0 && $("#slide_img").val().length == 0){
                $("#slide_img").css('border', '1px solid red');
                valid = false;
            }
            if($("#slide_order").length > 0 && !$.isNumeric($("#slide_order").val())){
                $("#slide_order").css('border', '1px solid red');
                valid = false;
            }
            if(valid == false){
                event.stopImmediatePropagation();
                return false;
            }
        });
    },
    uploadAds: function($){
        var custom_uploader;
        $('#upload_media_button').click(function(e) {
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                frame: 'select', // 'post'
                state: 'upload_media',
                multiple: false,
                //library: { type : 'image' },
                button: { text: 'Close' }
            });
            custom_uploader.states.add([
                new wp.media.controller.Library({
                    id: 'upload_media',
                    title:  'Upload Media',
                    priority:   20,
                    toolbar:    'select',
                    filterable: 'uploaded',
                    library:    wp.media.query( custom_uploader.options.library ),
                    multiple:   custom_uploader.options.multiple ? 'reset' : false,
                    editable:   true,
                    displayUserSettings: false,
                    displaySettings: true,
                    allowLocalEdits: true
                    //AttachmentView: ?
                }),
            ]);

            //Open the uploader dialog
            custom_uploader.open();
        });
    },
    uploadMetaFields: function($){
        var fields = new Array('slide_img', 'logo_img');

        $.each(fields, function(index, field){
            $("#publish").click(function(event){
                var valid = true;
                if($('#' + field).length > 0 && $('#' + field).val().length == 0){
                    $('#' + field).css('border', '1px solid red');
                    valid = false;
                }
                if($("#order_item").length > 0 && !$.isNumeric($("#order_item").val())){
                    $("#order_item").css('border', '1px solid red');
                    valid = false;
                }
                if(valid == false){
                    event.stopImmediatePropagation();
                    return false;
                }
            });
        });

        $("select.chosen-select").chosen({width: "38%"});
    },
    sendFeedback:function($){
        $("form#frmFeedback").submit(function(){
            $("#message").html("<p>Sending...</p>").show();
            $("form#frmFeedback input[type='submit']").val("Sending..").attr('disabled', true);
            $.ajax({
                url: ajaxurl, type: "POST", dataType: "json", cache: false,
                data: $(this).serialize(),
                success: function(response, textStatus, XMLHttpRequest){
                    if(response){
                        $("#message").html(response.message);
                    }
                },
                error: function(MLHttpRequest, textStatus, errorThrown){
                    $("#message").html(errorThrown);
                },
                complete:function(){
                    $("form#frmFeedback input[type='submit']").val("Gửi").removeAttr('disabled');
                }
            });
            return false;
        });
    }
};
function uploadByField(field){
    jQuery(document).ready(function($){
        var custom_uploader;

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#' + field).val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();
    });
}
function uploadProductChildIMG(field){
    jQuery(document).ready(function($){
        var custom_uploader;

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#' + field).val(attachment.url);
            $('#img_' + field).attr('src', attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();
    });
}

// Run
jQuery(document).ready(function($){
    CustomJS.uploadSlider($);
    CustomJS.uploadAds($);
    CustomJS.uploadMetaFields($);
    CustomJS.sendFeedback($);
    
    $("select.select-chosen").chosen();
    
    $('#' + shortname + '_primaryColor, #' + shortname + '_primaryBigColor, #' + shortname + '_primaryBigBgColor, #' + shortname + '_linkColor, #' + shortname + '_linkHVColor, #' + shortname + '_secondaryColor, #' + shortname + '_linkMenu, #' + shortname + '_linkHVMenu,#' + shortname + '_footerColor,#' + shortname + '_footerBgColor, #' + shortname + '_bgColor, #tag_meta_color').each(function(){
        var $el = $(this);
        $el.css({
            width: 100,
            height:36,
            'float':'left',
            margin:'0 0 0 -3px'
        })
        .before('<div class="colorSelector"><div style="background-color:#' + $el.val() + '"></div></div>')
        .after('<div style="clear:both;"></div>')
        .ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).ColorPickerHide();
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
                $(this).prev('div.colorSelector').children('div').css('backgroundColor', '#' + this.value);
            },
            onChange: function (hsb, hex, rgb) {
                $el.val(hex).prev('div.colorSelector').children('div').css('backgroundColor', '#' + hex);
            }
        })
        .bind('keyup', function(){
            $(this).ColorPickerSetColor(this.value);
            $(this).prev('div.colorSelector').children('div').css('backgroundColor', '#' + this.value);
        })
        .prev('div.colorSelector').click(function(){
            $(this).next('input').click();
        });
    });
});