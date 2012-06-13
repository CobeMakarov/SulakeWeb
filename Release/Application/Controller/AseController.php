<?php
/*
 * @project Manhattan Project
 * @author Cobe Makarov
 * @description
 *
 */

class AseController implements Controller
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

        if ($_SESSION['habbo']['rank'] < 5)
        {
            $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'error');
            return;
        }

        $View = new View();

        new ControllerHelper($View, 'page-ase-base');

        foreach($_SESSION['habbo'] as $Key => $Value)
        {
            $View->set(array('habbo_' . $Key => $Value));
        }

        $View->set(array(
            'page-title' => $this->Manhattan->Config['Site']['Title'],
            'page-tagline' => 'ASE',
            'site_title' => $this->Manhattan->Config['Site']['Title'],
            'users_online' => 0,
            'query_count' => $this->Manhattan->GetModel()->_count,
            'exec_time' => round(microtime(true) - $this->Manhattan->Start, 3)));

        echo $View->output();
    }

    public function check()
    {
        return true;
    }
}
?>
