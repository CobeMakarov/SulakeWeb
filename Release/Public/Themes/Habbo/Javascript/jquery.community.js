var _roomList = null;
var _leaderboardKey = 1;

$(document).ready(function()
{
    grabRooms();

    setTimeout('grabRooms()', 10000);

    grabLeaderboard();

    setTimeout('grabLeaderboard()', 10000);
})

function grabRooms()
{
    $.post('post', {grab_room_group: true}, function(data)
    {
        if (data == _roomList)
        {
            return;
        }

        _roomList = data;

        $('#room-list-to-populate').fadeOut('slow', function()
        {
            $(this).html(data);
            $(this).fadeIn('slow');
        })
    })
}

function grabLeaderboard()
{
    $.post('post', {grab_leaderboard: true, leaderboard_key: _leaderboardKey}, function(data)
    {
        $('#leaderboard-to-populate').fadeOut('slow', function()
        {
            $(this).html(data);
            $(this).fadeIn('slow');

            if (_leaderboardKey == 2) //What's your limit of leaderboards or count of leaderboards?
            {
                _leaderboardKey = 0;
            }
            else
            {
                _leaderboardKey++;
            }
        })
    })
}