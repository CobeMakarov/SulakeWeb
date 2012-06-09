<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class CommunityController implements Controller
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
        if ($this->check())
        {
            if (!AUTHENICATED)
            {
                $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'index', true);
                return;
            }

            if (AUTHENICATED && !ACTIVATED)
            {
                $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'characters', true);
                return;
            }

            $View = new View('page-header');

            $View->add('page-article');
            $View->add('page-community');
            $View->add('page-footer');

            $View->css('boxes');
            $View->css('body');
            $View->css('news');
            $View->css('header');

            $View->javascript('jquery.global');
            $View->javascript('jquery.twitter');
            $View->javascript('jquery.community');
            $View->javascript('jquery.articles');
            $View->javascript('jquery.online');

            foreach($_SESSION['habbo'] as $Key => $Value)
            {
                $View->set(array('habbo_' . $Key => $Value));
            }

            $View->set(array(
                'page-title' => $this->Manhattan->Config['Site']['Title'],
                'page-tagline' => 'Community',
                'site_title' => $this->Manhattan->Config['Site']['Title'],
                'users_online' => 0,
                'query_count' => $this->Manhattan->GetModel()->_count,
                'exec_time' => round(microtime(true) - $this->Manhattan->Start, 3),
                'social-twitter' => $this->Manhattan->Config['Social']['Twitter'],
                'social-facebook' => $this->Manhattan->Config['Social']['Facebook']));

            echo $View->output();
        }
        else
        {
            $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'error');
        }
    }

    public function check()
    {
        return file_exists('Public/Themes/' . THEME . '/page-community.html');
    }
}
?>
