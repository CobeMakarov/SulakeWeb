<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class CharactersController implements Controller
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

            $View = new View();

            new ControllerHelper($View, 'page-characters');
            
            $View->set(array(
                'page-title' => $this->Manhattan->Config['Site']['Title'],
                'page-tagline' => 'Characters',
                'site_title' => $this->Manhattan->Config['Site']['Title'],
                'users_online' => 0));

            echo $View->output();
        }
        else
        {
            $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'error');
        }
    }

    public function check()
    {
        return file_exists('Public/Themes/' . THEME . '/page-characters.html');
    }
}
?>
