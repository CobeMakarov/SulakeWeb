<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class ErrorController implements Controller
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
        $_view = new View('404');
        
        $Array = explode('/', CURRENT);
        $Current = null;

        if ($Array)
        {
            $Request = end($Array);
            $Current = $Request . '.php';
        }
        else
        {
            $Current = CURRENT . '.php';
        }

        $_view->set(array(
            'page-title' => '404',
            'page-tagline' => 'File Not Found!',
            'current_date' => date('h:m'),
            'website_name' => URL,
            'webmaster' => WEBMASTER,
            'url' => $Current));

        echo $_view->output();
    }

    public function check()
    {

    }
}
?>
