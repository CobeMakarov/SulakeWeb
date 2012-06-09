var preload_index_content = null;
var _online;

$(document).ready(function()
{
    grabOnline();

    setTimeout('grabOnline()', 10000);

    preload_index_content = $('.form-box .content').html();

    $('#register-show').live('click', function()
    {
        $.post('post', {show_register: true}, function(data)
        {
            $('.form-box .content').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        })
    })

    $('#login-show').live('click', function()
    {
        $('.form-box .content').fadeOut('slow', function()
        {
            $(this).html(preload_index_content);
            $(this).fadeIn('slow');
        })
    })

    $('#login-submit').live('click', function()
    {
        var _email = $('#login-email').val();
        var _password = $('#login-password').val();

        $.post('post', {try_login: true, login_email: _email, login_password: _password}, function(data)
        {
            if (data != "LOGIN = GOOD;")
            {
                alert(data);
                return false;
            }

            window.location = "characters";
        })
    })

    $('#register-submit').live('click', function()
    {
        var _email = $('#register-email').val();
        var _password = $('#register-password').val();

        $.post('post', {try_register: true, register_email: _email, register_password: _password}, function(data)
        {
            if (data == "REGISTER = GOOD;")
            {
                window.location = " ";
                return false;
            }

            alert(data);
        })
    })

    $('#character-submit').live('click', function()
    {
        var _username = $('#creation-username').val();

        $.post('post', {create_character: true, creation_username: _username}, function(data)
        {
            if (data == "CREATION = GOOD;")
            {
                window.location = " ";
                return false;
            }

            alert(data);
        })
    })

    if (document.URL.indexOf("characters") >= 0)
    {
        $.post('post', {load_characters: true}, function(data)
        {
            /*
            newHeight = 230;

            var data_split = data.split('$({});');

            character_count = data_split[0];

            data_string = data_split[1];

            newHeight = 230 + ((character_count * 2.5) * 10);
            */

            $('.form-box .content').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
                //$('#character-form').animate({height: newHeight + "px"});
            })
        })
    }
})

function activateUser(_id)
{
    $.post('post', {activate_character:true, activation_id: _id}, function(data)
    {
        if (data == 'ACTIVATION = GOOD;')
        {
            window.location = "me";
            return false;
        }

        alert('Something went wrong! Try again later..')
    })
}

function grabOnline()
{
    _online = $('.right-container .content').html();

    $.post('post', {grab_online_count: true}, function(data)
    {
        if (data == _online)
        {
            return;
        }

        _online = data;

        $('.right-container .content').fadeOut('slow', function()
        {
            $(this).html(data);
            $(this).fadeIn('slow');
        })
    })
}