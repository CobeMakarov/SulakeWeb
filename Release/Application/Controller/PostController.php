<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class PostController implements Controller
{
    private $Manhattan;

    /*
     * Only used to transfer our variable as needed!
     */
    public function __construct($Manhattan)
    {
        $this->Manhattan = $Manhattan;
    }

    public function action()
    {
        foreach($_POST as $key => $value)
        {
            switch($key)
            {
                case 'show_register':
                    if (!$_POST[$key])
                    {
                        exit;
                    }
                    //Template HACK
                    $output = new SimpleView('page-register');

                    echo $output->result();
                    break;

                case 'try_register':
                    if (!$_POST[$key])
                    {
                        exit;
                    }

                    if (!isset($_POST['register_email']))
                    {
                        die('You left the e-mail field blank!');
                    }

                    if (!filter_var($_POST['register_email'], FILTER_VALIDATE_EMAIL))
                    {
                        die('That is not a valid e-mail address!');
                    }

                    if (!isset($_POST['register_password']))
                    {
                        die('You left the password field blank!');
                    }

                    $Email = $this->Manhattan->GetModel()->secure($_POST['register_email']);

                    $Password = $this->Manhattan->GetHash($_POST['register_password']);

                    $Accounts = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_users WHERE email = ?')
                            ->bind(array($Email))->execute();

                    if ($Accounts->num_rows() > 0)
                    {
                        die('There is already an account associated with '.$Email);
                    }
                    else
                    {
                        $this->Manhattan->GetModel()->prepare('INSERT INTO sulake_users (email, password) VALUES (?, ?)')
                                ->bind(array($Email, $Password))->execute();
                        die('REGISTER = GOOD;');
                    }
                    break;

                case 'try_login':
                    if (!$_POST[$key])
                    {
                        exit;
                    }

                    if (!isset($_POST['login_email']))
                    {
                        die('You left the e-mail field blank!');
                    }

                    if (!filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL))
                    {
                        die('The e-mail you entered is incorrect!');
                    }

                    if (!isset($_POST['login_password']))
                    {
                        die('You left the password field blank!');
                    }

                    $Email = $this->Manhattan->GetModel()->secure($_POST['login_email']);

                    $Password = $this->Manhattan->GetHash($_POST['login_password']);

                    $Account = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_users WHERE email = ? AND password = ?')
                            ->bind(array($Email, $Password))->execute();

                    if ($Account->num_rows() > 0)
                    {
                        $_SESSION['account']['logged_in'] = true;
                        $_SESSION['account']['master_email'] = $Email;
                        die('LOGIN = GOOD;');
                    }
                    else
                    {
                        die('Incorrect email/password sequence!');
                    }
                    break;

                case 'load_characters':
                    if (!$_POST[$key])
                    {
                        exit;
                    }

                    $Id = $_SESSION['account']['master_email'];

                    $Accounts = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE mail = ?')
                            ->bind(array($Id))->execute();

                    if ($Accounts->num_rows() ==  0)
                    {
                        die('<div align="center">You do not have any characters!</div>');
                    }
                    else
                    {
                        $Output = null;

                        while($a = $Accounts->fetch_array())
                        {
                            $Simple = new SimpleView('widget-character');
                            $Simple->replace('username', $a['username']);
                            $Simple->replace('look', $a['look']);
                            $Simple->replace('id', $a['id']);

                            $Output = $Output . $Simple->result();
                        }

                        echo $Output;
                    }
                    break;

                case 'create_character':
                    if (!$_POST[$key])
                    {
                        exit;
                    }

                    if (!isset($_POST['creation_username']) || empty($_POST['creation_username']))
                    {
                        die('You left the username field blank!');
                    }

                    $Look = $_POST['creation_look'];

                    $Character = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE username = ?')
                            ->bind(array($this->Manhattan->GetModel()->secure($_POST['creation_username'])))->execute();

                    if ($Character->num_rows() > 0)
                    {
                        die('That username is taken, try again!');
                    }

                    $Limit = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE mail = ?')
                            ->bind(array($_SESSION['account']['master_email']))->execute()->num_rows();

                    if ($Limit > 2)
                    {
                        die('You already have the maximum amount of characters!');
                    }

                    $this->Manhattan->GetModel()->prepare('INSERT INTO users (mail, username, password, motto, account_created, ip_reg, look) VALUES (?, ?, ?, ?, ?, ?, ?)')
                            ->bind(array($_SESSION['account']['master_email'],
                                $_POST['creation_username'],
                                '',
                                $this->Manhattan->Config['Site']['Title'] . ' new user!',
                                date('m.d.y'),
                                $_SESSION['REQUEST_ADDR'],
                                $Look
                                ))->execute();

                    die('CREATION = GOOD;');
                    break;

                case 'activate_character':
                    if (!$_POST[$key])
                    {
                        exit;
                    }

                    if (!isset($_POST['activation_id']))
                    {
                        die('Something went wrong! Try again later..');
                    }

                    $Account = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE id = ?')
                            ->bind(array($_POST['activation_id']))->execute();

                    while($A = $Account->fetch_array())
                    {
                        $UserBan = $this->Manhattan->GetModel()->prepare('SELECT null FROM bans WHERE value = ?')
                                ->bind(array($A['username']))->execute();

                        if ($UserBan->num_rows() > 0)
                        {
                            die('Seems this account has been banned!');
                        }

                        $IpBan = $this->Manhattan->GetModel()->prepare('SELECT null FROM bans WHERE value = ?')
                                ->bind(array($_SERVER['REQUEST_ADDR']))->execute();

                        if ($IpBan->num_rows() > 0)
                        {
                            die('Seems your IP has been banned!');
                        }

                        foreach($A as $Key => $Value)
                        {
                            $_SESSION['habbo'][$Key] = $Value;
                        }
                    }

                    die("ACTIVATION = GOOD;");
                    break;

                case 'load_news':

                    $Articles = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_news LIMIT 1')->execute();

                    while($Array = $Articles->fetch_array())
                    {
                        $Template = new SimpleView('widget-article');
                        $Template->replace('id', $Array['id']);
                        $Template->replace('image', $Array['image']);
                        $Template->replace('title', $Array['title']);
                        $Template->replace('author', $Array['author']);
                        $Template->replace('story', substr($Array['story'], 0, 20));
                        die($Template->result());
                    }
                    break;

                case 'clear_session':
                    session_destroy();
                    break;

                case 'ready_marquee':

                    if (!isset($_POST['article_id']))
                    {
                        exit;
                    }

                    $Article = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_news WHERE id = ?')
                            ->bind(array($_POST['article_id']))->execute();

                    if (!$Article || $Article->num_rows() == 0)
                    {
                        $Template = new SimpleView('widget-article');
                        $Template->replace('id', 0);
                        $Template->replace('image', 'web_promo_07.png');
                        $Template->replace('title', '0' . $_POST['article-id'] . '. Article not found!');
                        $Template->replace('author', 'SulakeWEB');
                        $Template->replace('story', substr('No article has been found with the id: 0' . $_POST['article-id'], 0, 20));
                        die($Template->result());
                    }

                    while($Array = $Article->fetch_array())
                    {
                        $Template = new SimpleView('widget-article');
                        $Template->replace('id', $Array['id']);
                        $Template->replace('image', $Array['image']);
                        $Template->replace('title', $Array['title']);
                        $Template->replace('author', $Array['author']);
                        $Template->replace('story', substr($Array['story'], 0, 20));
                        die($Template->result());
                    }
                    break;

                case 'grab_online_group':

                    $Users = $this->Manhattan->GetModel()->prepare('SELECT username FROM users WHERE online = ?')
                            ->bind(array('1'))->execute();

                    if ($Users->num_rows() == 0)
                    {
                        die('No users online!');
                    }

                    $Output = null;
                    $Key = 0;

                    while($U = $Users->fetch_array())
                    {
                        if ($Key == (count($U) - 1))
                        {
                            $Output = $Output . $U['username'];
                            break;
                        }
                        else
                        {
                            $Output = $Output . $U['username'] . ', ';
                        }
                    }

                    die($Output);
                    //die('Disabled.');
                    break;

                case 'grab_room_group':

                    $Rooms = $this->Manhattan->GetModel()->prepare('SELECT caption, users_now FROM rooms ORDER BY users_now DESC LIMIT 5')
                            ->execute();

                    $Output = null;

                    $Key = 0;

                    while($R = $Rooms->fetch_array())
                    {
                        $Key++;

                        $SimpleView = new SimpleView('widget-room');
                        $SimpleView->replace('key', $Key);
                        $SimpleView->replace('name', $R['caption']);
                        $SimpleView->replace('count', $R['users_now']);
                        $Output = $Output . $SimpleView->result();
                    }

                    die($Output);
                    break;

                case 'grab_leaderboard':
                    if (!isset($_POST['leaderboard_key']))
                    {
                        exit;
                    }

                    switch($_POST['leaderboard_key'])
                    {
                        case 1: //Richest Users!!

                            $Users = $this->Manhattan->GetModel()->prepare('SELECT username, credits FROM users ORDER BY credits DESC LIMIT 5')
                                ->execute();

                            $Output = null;

                            $Combined = null;

                            $Key = 0;

                            $List = new SimpleView('widget-leaderboard');
                            $List->replace('title', 'Richest Users');
                            $List->replace('description', 'Here are the richest users in all of the hotel, check if you are on the list!');

                            while($U = $Users->fetch_array())
                            {
                                $Key++;

                                $SimpleView = new SimpleView('widget-room');
                                $SimpleView->replace('key', $Key);
                                $SimpleView->replace('name', $U['username']);
                                $SimpleView->replace('count', $U['credits']);

                                $Combined = $Combined . $SimpleView->result();
                            }

                            $List->replace('list', $Combined);

                            $Output = $List->result();

                            die($Output);
                            break;

                        case 2: //Respected Users!!

                            $Users = $this->Manhattan->GetModel()->prepare('SELECT username, respect FROM users ORDER BY respect DESC LIMIT 5')
                                ->execute();

                            $Output = null;

                            $Combined = null;

                            $Key = 0;

                            $List = new SimpleView('widget-leaderboard');
                            $List->replace('title', 'Highest Respected Users');
                            $List->replace('description', 'Here are the highest respected users in all of the hotel, check if you are on the list!');

                            while($U = $Users->fetch_array())
                            {
                                $Key++;

                                $SimpleView = new SimpleView('widget-room');
                                $SimpleView->replace('key', $Key);
                                $SimpleView->replace('name', $U['username']);
                                $SimpleView->replace('count', $U['respect']);

                                $Combined = $Combined . $SimpleView->result();
                            }

                            $List->replace('list', $Combined);

                            $Output = $List->result();

                            die($Output);
                            break;
                    }
                    break;

                case 'submit_comment':

                    if (!isset($_POST['comment']))
                    {
                        die('You left the field blank!');
                    }

                    if (!isset($_POST['article']))
                    {
                        die('Something went horribly wrong.');
                    }

                    if (strlen($_POST['comment']) <= 4)
                    {
                        die('Your message is too short!');
                    }

                    if ($_POST['article'] == 0)
                    {
                        die('Something went horribly wrong..');
                    }

                    $Current = $_POST['article'];

                    $Comments = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_news_comments WHERE article = ?')
                            ->bind(array($Current))->execute();

                    while($C = $Comments->fetch_array())
                    {
                        if (($C['username'] == $_SESSION['habbo']['username']) && ($C['date'] == date('m-d-y'))) //Woah, Don't spam me bro!
                        {
                            die('You already commented on this article today!');
                        }
                    }

                    $Comment = $this->Manhattan->GetModel()->secure($_POST['comment']);

                    $this->Manhattan->GetModel()->prepare('INSERT INTO sulake_news_comments(username, date, comment, article) VALUES (?, ?, ?, ?)')
                            ->bind(array($_SESSION['habbo']['username'], date('m-d-y'), $Comment, $Current))->execute();

                    die('Comment has been submitted!');
                    break;

                case 'load_comments':

                    if (!isset($_POST['article']))
                    {
                        die('Something went horribly wrong.');
                    }

                    $Id = $_POST['article'];
                    $Title = null;

                    $Comments = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_news_comments WHERE article = ? ORDER BY date DESC')
                            ->bind(array($Id))->execute();

                    $Article = $this->Manhattan->GetModel()->prepare('SELECT title FROM sulake_news WHERE id =  ?')
                            ->bind(array($Id))->execute();

                    while($A = $Article->fetch_array())
                    {
                        $Title = $A['title'];
                    }

                    if ($Comments->num_rows() == 0)
                    {
                        die('No comments to load!');
                    }

                    $Output = null;

                    while($C = $Comments->fetch_array())
                    {
                        $SimpleView = new SimpleView('widget-comment');
                        $SimpleView->replace('username', $C['username']);
                        $SimpleView->replace('date', $C['date']);
                        $SimpleView->replace('comment', $C['comment']);
                        $SimpleView->replace('article', $Title);

                        //Here comes ze queries!
                        $User = $this->Manhattan->GetModel()->prepare('SELECT look FROM users WHERE username = ?')
                                ->bind(array($C['username']))->execute();

                        while ($U = $User->fetch_array())
                        {
                            $SimpleView->replace('look', $U['look']);
                        }

                        $Output = $Output . $SimpleView->result();
                    }

                    die($Output);
                    break;

                case 'grab_staff':

                    $SimpleViewaff = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE rank >= ? ORDER BY rank DESC')
                        ->bind(array(5))->execute();

                    if ($SimpleViewaff->num_rows() == 0)
                    {
                        die('Wierdly enough.. there are no staff members!!');
                    }

                    $Output = null;

                    while($_s = $SimpleViewaff->fetch_array())
                    {
                        $SimpleView = new SimpleView('widget-staff');
                        $SimpleView->replace('username', $_s['username']);
                        $SimpleView->replace('rank', $_s['rank']);
                        $SimpleView->replace('look', $_s['look']);
                        $SimpleView->replace('motto', $_s['motto']);

                        $Output = $Output . $SimpleView->result();
                    }

                    die($Output);
                    break;

                case 'grab_online_count':

                    $Count = $this->Manhattan->GetModel()->prepare('SELECT * FROM server_status')->execute();

                    while ($C = $Count->fetch_array())
                    {
                        die($C['users_online'] . ' online right now!');
                    }
                    break;

                case 'update_motto':
                    if (!isset($_POST['motto']) || empty($_POST['motto']))
                    {
                        die('You left the field blank!');
                    }

                    if (strlen($_POST['motto']) <= 2)
                    {
                        die('Your motto is too short!');
                    }

                    if (strlen($_POST['motto']) >= 30)
                    {
                        die('Your motto is too long!');
                    }

                    if ($_POST['motto'] == $_SESSION['habbo']['motto'])
                    {
                        die('Your motto is already '. $_POST['motto']);
                    }

                    $Motto = $this->Manhattan->GetModel()->secure($_POST['motto']);

                    $this->Manhattan->GetModel()->prepare('UPDATE users SET motto = ? WHERE id = ?')
                            ->bind(array($Motto, $_SESSION['habbo']['id']))->execute();

                    die('Your motto has successfully been changed to '.$Motto);
                    break;

                case 'ase_show_add_article':

                    $View = new SimpleView('widget-add-article');

                    die($View->result());

                    break;

                case 'ase_add_article':

                    if (!isset($_POST['title']) || strlen($_POST['title']) <= 1)
                    {
                        die('<b>Error: You left the title empty!</b>');
                    }

                    if (!isset($_POST['author']) || strlen($_POST['author']) <= 1)
                    {
                        die('<b>Error: You left the author empty!</b>');
                    }

                    if (!isset($_POST['date']) || strlen($_POST['date']) <= 1)
                    {
                        die('<b>Error: You left the date empty!</b>');
                    }

                    if (!isset($_POST['image']) || strlen($_POST['image']) <= 1)
                    {
                        die('<b>Error: You left the image empty!</b>');
                    }

                    if (!isset($_POST['story']) || strlen($_POST['story']) <= 1)
                    {
                        die('<b>Error: You left the story empty!</b>');
                    }

                    $Article = array();
                    foreach($_POST as $Key => $Value)
                    {
                        $Article[$Key] = $this->Manhattan->GetModel()->secure($Value);
                    }

                    $this->Manhattan->GetModel()->prepare('INSERT INTO sulake_news(title, author, date, image, story) VALUES(?, ?, ?, ?, ?)')
                            ->bind(array($Article['title'], $Article['author'], $Article['date'], $Article['image'], $Article['story']))->execute();

                    die($Article['title'] . ' has been added successfully!');
                    break;

                case 'ase_manage_chatlogs':

                    $Chatlogs = $this->Manhattan->GetModel()->prepare('SELECT * FROM chatlogs')->execute();

                    if ($Chatlogs->num_rows() == 0)
                    {
                        die('<b>No chatlogs to manage!</b>');
                    }

                    $Output = null;

                    while($C = $Chatlogs->fetch_array())
                    {
                        $Chatlog = new SimpleView('widget-chatlog');

                        $Chatlog->replace('id', $C['id']);
                        $Chatlog->replace('user', $C['user_name']);
                        $Chatlog->replace('message', $C['message']);
                        $Chatlog->replace('time', date('F j, Y, g:i a', $C['timestamp']));
                        $Chatlog->replace('room', $C['room_id']);

                        $Output = $Output . $Chatlog->result();
                    }

                    die($Output);
                    break;

                case 'ase_show_manage_users':

                    $View = new SimpleView('widget-search-user');

                    die($View->result());

                    break;

                case 'ase_grab_user':
                    if (!isset($_POST['name']) || strlen($_POST['name']) < 1)
                    {
                        die('You left the username empty.');
                    }

                    $Name = $this->Manhattan->GetModel()->secure($_POST['name']);

                    $User = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE username = ?')
                            ->bind(array($Name))->execute();

                    if ($User->num_rows() == 0)
                    {
                        die('User not found!');
                    }

                    while($U = $User->fetch_array())
                    {
                        if ($_SESSION['habbo']['rank'] <= $U['rank'])
                        {
                            die('You do not have the authority to view ' . $Name);
                        }

                        $View = new SimpleView('widget-ase-user');

                        foreach($U as $Key => $Value)
                        {
                            $View->replace($Key, $Value);
                        }

                        die($View->result());
                    }
                    break;

                case 'ase_show_manage_bans':
                    $SimpleView = new SimpleView('widget-ase-bans');
                    die($SimpleView->result());
                    break;

                case 'ase_show_fade_bans':
                    $Bans = $this->Manhattan->GetModel()->prepare('SELECT * FROM bans')->execute();

                    if ($Bans->num_rows() == 0)
                    {
                        die('There aren\'t any bans on ' . $this->Manhattan->Config['Site']['Title']);
                    }

                    $Output = null;

                    while($B = $Bans->fetch_array())
                    {
                        $Ban = new SimpleView('widget-ban');

                        foreach($B as $Key => $Value)
                        {
                            $Ban->replace($Key, $Value);
                        }

                        $Output = $Output . $Ban->result();
                    }

                    die($Output);
                    break;

                case 'ase_ban_user':
                    if (!isset($_POST['username']) || strlen($_POST['username']) < 1)
                    {
                        die('You left the username field blank!');
                    }

                    $Name = $_POST['username'];

                    $User = $this->Manhattan->GetModel()->prepare('SELECT * FROM users WHERE username = ?')
                            ->bind(array($Name))->execute();

                    if ($User->num_rows() == 0)
                    {
                        die("<b>$Name</b> doesn't exist!"); ## Felt lazy so did a internal variable!
                    }

                    while($U = $User->fetch_array())
                    {
                        if ($_SESSION['habbo']['rank'] <= $U['rank'])
                        {
                            die('You do not have the authority to ban ' . $Name);
                        }
                    }

                    $this->Manhattan->GetModel()->prepare('INSERT INTO bans (value, reason, added_by) VALUES(?, ?, ?)')
                            ->bind(array($Name, 'Generic Ban', $_SESSION['habbo']['username']))->execute();

                    die($Name . ' has been banned successfully!');
                    break;
            }
        }
    }

    public function check()
    {
        return !empty($this->Manhattan->GetRequests()->RetrievePOST);
    }
}
?>
