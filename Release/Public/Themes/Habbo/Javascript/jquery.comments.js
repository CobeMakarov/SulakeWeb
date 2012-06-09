$(document).ready(function()
{
    var _article = $('#article-id').html();

    $.post('post', {load_comments: true, article: _article}, function(data)
    {
        $('#comment-container').html(data);
    })

    $('#comment-submit').live('click', function()
    {
        var _comment = $('#comment-data').val();

        $.post('post', {submit_comment: true, comment: _comment, article: _article}, function(data)
        {
            $('#message-data').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        });
    })
})