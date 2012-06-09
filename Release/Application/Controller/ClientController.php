<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class ClientController implements Controller
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

            $View = new View('page-client');

            $View->javascript('swfobject');

            $this->Manhattan->GetModel()->prepare('UPDATE users SET auth_ticket = ? WHERE id = ?')
                    ->bind(array('sulake.' . $_SESSION['habbo']['username'], $_SESSION['habbo']['id']))->execute();

            $_SESSION['habbo']['auth_ticket'] = 'sulake.' . $_SESSION['habbo']['username'];

            $this->Manhattan->GetModel()->prepare('UPDATE users SET ip_last = ?, ip_reg = ? WHERE id = ?')
                    ->bind(array(IP_ADDRESS, IP_ADDRESS, $_SESSION['habbo']['id']))->execute();

            foreach($_SESSION['habbo'] as $Key => $Value)
            {
                $View->set(array('habbo_' . $Key => $Value));
            }

            $View->set(array(
                'page-title' => $this->Manhattan->Config['Site']['Title'],
                'page-tagline' => 'Index'));

            echo $View->output();
        }
        else
        {
            $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'error');
        }
    }

    public function check()
    {
        return file_exists('Public/Themes/' . THEME . '/page-client.html');
    }
}
?>
