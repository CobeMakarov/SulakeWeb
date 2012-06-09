$(document).ready(function()
{
    $('#add-article').live('click', function()
    {
        $.post('post', {ase_show_add_article: true}, function(data)
        {
            $('#changed-content').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
                $('#message-container').html('');
                $('#extra-stuff').html('');
            })
        })
    })

    $('#add-article-submit').live('click', function()
    {
        var title = $('#add-article-title').val();
        var author = $('#add-article-author').val();
        var date = $('#add-article-date').val();
        var image = $('#add-article-image').val();
        var story = $('#add-article-story').val();

        $.post('post', {ase_add_article: true, title: title, author: author, date: date, image: image, story: story}, function(data)
        {
            $('#message-container').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        })
    })

    $('#manage-chatlogs').live('click', function()
    {
        $.post('post', {ase_manage_chatlogs: true}, function(data)
        {
            $('#changed-content').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
                $('#message-container').html('');
                $('#extra-stuff').html('');
            })
        })
    })

    $('#manage-users').live('click', function()
    {
        $.post('post', {ase_show_manage_users: true}, function(data)
        {
            $('#changed-content').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
                $('#message-container').html('');
                $('#extra-stuff').html('');
            })
        })
    })

    $('#search-user-submit').live('click', function()
    {
        var name = $('#search-user-username').val();

        $.post('post', {ase_grab_user: true, name: name}, function(data)
        {
            $('#extra-stuff').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        })
    })

    $('#manage-bans').live('click', function()
    {
        $.post('post', {ase_show_manage_bans: true}, function(data)
        {
            $('#changed-content').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
                $('#message-container').html('');
                $('#extra-stuff').html('');
            })
        })

        $.post('post', {ase_show_fade_bans: true}, function(data)
        {
           $('#extra-stuff').fadeOut('slow', function()
           {
               $(this).html(data);
               $(this).fadeIn('slow');
           })
        })
    })

    $('#ban-user-submit').live('click', function()
    {
        var username = $('#ban-user-username').val();

        $.post('post', {ase_ban_user: true, username: username}, function(data)
        {
            $('#message-container').fadeOut('slow', function()
            {
                $(this).html(data);
                $(this).fadeIn('slow');
            })
        })
    })
})