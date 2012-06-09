$(document).ready(function()
{
    $.post('post', {grab_staff:true}, function(data)
    {
        $('#staff-population').html(data);
    })
})