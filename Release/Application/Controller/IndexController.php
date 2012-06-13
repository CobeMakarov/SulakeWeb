<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class IndexController implements Controller
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
            if (AUTHENICATED)
            {
                if (ACTIVATED)
                {
                    $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'me', true);
                }
                else
                {
                    $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'characters', true);
                }
            }

            $View = new View();

            new ControllerHelper($View, 'page-index');

            $View->set(array(
                'page-title' => $this->Manhattan->Config['Site']['Title'],
                'page-tagline' => 'Index',
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
        return file_exists('Public/Themes/' . THEME . '/page-index.html');

        while($QueryObject = mysql_fetch_array($Query))
        {
            //If needed
            foreach($QueryObject as $ArrayKey => $ArrayValue)
            {

            }
        }
    }
}
?>
