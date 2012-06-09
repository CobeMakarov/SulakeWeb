var _online;
var look = 'hr-115-42.hd-190-1.ch-215-62.lg-285-91.sh-290-62'; // BOY LOOK HERE

$(document).ready(function()
{
    grabOnline();

    setTimeout('grabOnline()', 10000);

    $('#login-password').keyup(function(event)
    {
        if (event.which != 13)
        {
            return;
        }

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

    $('#register-password').keyup(function(event)
    {
        if (event.which != 13)
        {
            return;
        }

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

        $.post('post', {create_character: true, creation_username: _username, creation_look: look}, function(data)
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
        $('#gender-girl').live('click', function()
        {
            look = 'hr-115-42.hd-190-1.ch-215-62.lg-285-91.sh-290-62'; // GIRL LOOK HERE
        })

        $.post('post', {load_characters: true}, function(data)
        {
            $('ul.characters').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
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

        alert(data);
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