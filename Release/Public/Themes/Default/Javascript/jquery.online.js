var _constantGroup = null;

$(document).ready(function()
{
    grabGroup();

    setTimeout(grabGroup(), '10000');
})

function grabGroup()
{
    $.post('post', {grab_online_group: true}, function(data)
    {
        if (data == _constantGroup)
        {
            return;
        }

        _constantGroup = data;

        $('#users-online-container').fadeOut('slow', function()
        {
            $(this).html(data);
            $(this).fadeIn('slow');
        })
    })
}