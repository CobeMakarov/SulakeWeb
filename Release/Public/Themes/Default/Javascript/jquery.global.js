var _online;

$(document).ready(function()
{
    grabOnline();

    setTimeout('grabOnline()', 10000);

    $('#settings-submit').live('click', function()
    {
        $.post('post', {update_motto: true, motto: $('#settings-motto').val()}, function(data)
        {
            $('#alert-container').html(data);
        })
    })
})

function clearSession()
{
    $.post('post', {clear_session:true}, function()
    {
        window.location = "index.php";
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