var formSender = {
    to: '/sendmail/',
    $form: {},
    init: function() {
        $('form').each(function() {
            var $form = $(this);
            $form.find('[data-required]').each(function() {
                var $reqField = $(this);
                $reqField.on('keyup blur click', function() {
                    if ($form.hasClass('submited')) {
                        var $fieldwrap = $reqField.parent();
                        $fieldwrap.removeClass('_error');
                        $reqField.attr('data-required', 'complete');
                    }
                });
            });
            $form.find('button').addClass('_inactive');
            $form.on('submit', function(event) {
                $form.addClass('submited');
                formSender.sendIfValid($form, event);
            });
            $form.on('click', 'button._loading', function(e) {
                e.preventDefault();
                return false;
            })
        });
    },
    checkFields: function($form, hard) {
        var err = false;
        $form.find('[data-required]').each(function() {
            if ($(this).attr('data-required') == '' || $(this).attr('data-required') == 'err') {
                err = true;
            }
            if (hard)
                formSender.checkField($(this));
        });
        if (!err) {
            $form.find('button').removeClass('_inactive');
            return true;
        } else {
            $form.find('button').addClass('_inactive');
            return false;
        }
    },
    checkField: function($field) {
        err = false;
        var name = $field.attr('name');
        var $fieldwrap = $field.parent();
        pattern = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;
        if ($field.val() == '')
            err = true;
        else if (name == 'phone' && $field.val().indexOf('_') >= 0)
            err = true;
        else if (name == 'email' && !(pattern.test($field.val())))
            err = true;
        if (err) {
            $field.attr('data-required', 'err');
            $fieldwrap.addClass('_error');
            if (typeof $field.data('errmsg') !== 'undefined' && $fieldwrap.find('.fieldError').length <= 0) {
                $fieldwrap.append("<span class='fieldError'>" + $field.data('errmsg') + "</span>");
            }
        } else {
            $fieldwrap.removeClass('_error');
            $field.attr('data-required', 'complete');
        }
    },
    sendIfValid: function($form, e) {
        e.preventDefault();
        var formData = new FormData($form[0]);
        $form.find('[data-required], [data-required-or]').each(function() {
            formSender.checkField($(this));
        });
        if (formSender.checkFields($form, true)) {
            var to = typeof $form.attr('action') !== 'undefined' ? $form.attr('action') : formSender.to;
            if($form.data('callback-email') != ''){
                formData.append('email', $form.data('callback-email'));
            }
            else{
                formData.append('email', '');
            }
            formData.append('callback_type', $form.data('callback-type'));
            console.log($form.data('callback-type'));
            formData.append('url', window.location.href);
            
            $.ajax({
                beforeSend: function() {
                    $form.closest('.callback_form').addClass('_loading');
                },
                type: 'post',
                url: to,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $form.closest('.callback_form').removeClass('_loading');
                    $form.parents('.callback_form').addClass('_success');
                    var name = $form.find('[name="name"]').val();
                    var phone = $form.find('[name="phone"]').val();
                    $form.parents('.callback_form').find('.callback_form_success_name').text(name);
                    $form.parents('.callback_form').find('.callback_form_success_phone').text(phone);
                    console.log(response);
                    yaGoal('callback_button');
                },
                error: function(response) {
                    console.log(response);
                }
            });
        } else {
            console.log('not valid')
        }
    }
};
$(document).ready(function() {
    $('.callback_form_reload').on('click', function(){
        $(this).parents('.callback_form').find('form').trigger('reset');
        $(this).parents('.callback_form').removeClass('_success');
    });

    formSender.init();
    $('[name=phone]').inputmask({
        mask: '+7 (999) 999-99-99',
        showMaskOnHover: false,
    });
});
var yaGoal = function(yaId) {
    if (typeof yandexCounters === 'undefined') {
        yandexCounters = [];
    }
    if (yandexCounters.length == 0) {
        yandexCounters = Object.keys(window).filter(function(el) {
            return /^yaCounter.*?/i.test(el);
        });
    }
    if (yandexCounters.length > 0) {
        window[yandexCounters[0]]['reachGoal'](yaId);
    }
};
var gooGoal = function(gooCategory, gooAction) {
    if (typeof ga !== 'undefined') {
        ga('send', 'event', gooCategory, gooAction);
    }
};
