<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class MeController implements Controller
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

            
            $Exists = file_get_contents('Storage/Cache/Looks/' . $_SESSION['habbo']['look'] . '.png');
            $HabboLook = file_get_contents('http://www.habbo.com/habbo-imaging/avatarimage?figure=' . $_SESSION['habbo']['look'] . '.gif');

            if (($Exists && $HabboLook) && ($Exists != $HabboLook))
            {
                file_put_contents(
                    'Storage/Cache/Looks/' . $_SESSION['habbo']['look'] . '.png',
                    file_get_contents('http://www.habbo.com/habbo-imaging/avatarimage?figure=' . $_SESSION['habbo']['look'] . '.gif'));
            }
            
            $View = new View();

            new ControllerHelper($View, 'page-me');

            foreach($_SESSION['habbo'] as $Key => $Value)
            {
                $View->set(array('habbo_' . $Key => $Value));
            }

            $View->set(array(
                'page-title' => $this->Manhattan->Config['Site']['Title'],
                'page-tagline' => $_SESSION['habbo']['username'],
                'site_title' => $this->Manhattan->Config['Site']['Title'],
                'users_online' => 0,
                'query_count' => $this->Manhattan->GetModel()->_count,
                'exec_time' => round(microtime(true) - $this->Manhattan->Start, 3)));

            echo $View->output();
        }
        else
        {
            $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'error');
        }
    }

    public function check()
    {
        return file_exists('Public/Themes/' . THEME . '/page-me.html');
    }
}
?>
