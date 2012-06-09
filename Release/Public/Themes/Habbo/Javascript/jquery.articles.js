var _id = 0;

$(document).ready(function()
{
    $.post('post', {load_news: true}, function(data)
    {
        $('.article-container').fadeOut('slow', function()
        {
            _id = 1;
            $(this).html(data);
            $(this).fadeIn('slow');
        })
    });

    $('#article-next').live('click', function()
    {

        _default = _id;

        _requested = _id + 1;

        _id = _requested;

        $.post('post', {ready_marquee: true, article_id: _requested}, function(data)
        {
            if (data == 'is_null')
            {
                _id = _default;
                return;
            }

            $('.article-container').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        });
    })

    $('#article-prev').live('click', function()
    {
        if (_id == 1)
        {
            return;
        }

        _default = _id;

        _requested = _id - 1;

        _id = _requested;

        $.post('post', {ready_marquee: true, article_id: _requested}, function(data)
        {
            if (data == 'is_null')
            {
                _id = _default;
                return;
            }

            $('.article-container').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        });
    })
})

