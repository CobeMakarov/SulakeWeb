<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class Router
{
    public function Direct($SiteInstance, $Request, $HardRoute = false)
    {
        if ($HardRoute)
        {
            header('Location: ' . $Request);
        }

        $ClassName = ucfirst($Request) . 'Controller'; ## Application/Controller/{$Request}Controller.php

        if (!file_exists('Application/Controller/' . $ClassName . '.php'))
        {
            $this->Direct($SiteInstance, 'error');
            return;
        }

        include('Application/Controller/' . $ClassName . '.php');

        $Controller = new $ClassName($SiteInstance);

        $Controller->action();
    }
}
?>
