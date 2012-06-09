<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 * @warning Contains lazy function naming!
 */

class View extends Manhattan
{
    private $Template, $CSS, $Javascript, $Body;

    public $Parameters = array();

    public function __construct($_view)
    {
        $this->Template = file_get_contents('Public/Themes/' . THEME . '/page-background.html');

        if (!file_exists('Public/Themes/' . THEME . '/' . $_view . '.html'))
        {
            return; //blah
        }

        $this->Body = $this->Body . file_get_contents('Public/Themes/' . THEME . '/'.$_view.'.html');
    }

    public function add($_file)
    {
        if (!file_exists('Public/Themes/' . THEME . '/' . $_file . '.html'))
        {
            trigger_error('Public/Themes/' . THEME . '/' . $_file . '.html does not exist on this web server!');
        }

        $this->Body = $this->Body . file_get_contents('Public/Themes/' . THEME . '/'.$_file.'.html');
    }

    public function javascript($_file)
    {
        if (!file_exists('Public/Themes/' . THEME . '/Javascript/' . $_file . '.js'))
        {
            return; //blah
        }

        $this->Javascript = $this->Javascript . "\r\n" . '        <script type="text/javascript" src="Public/Themes/' . THEME . '/Javascript/' . $_file . '.js"></script>';
    }

    public function css($_file)
    {
        if (!file_exists('Public/Themes/' . THEME . '/Cascading/' . $_file . '.css'))
        {
            return; //blah
        }

        $this->CSS = $this->CSS . "\r\n" . '        <link rel="stylesheet" type="text/css" href="./Public/Themes/' . THEME . '/Cascading/' . $_file . '.css" />';
    }

    public function set($_array)
    {
        foreach($_array as $_key => $_value)
        {
            $this->Parameters['${'.$_key.'}'] = $_value;
        }
    }

    private function parse($_page)
    {
        return str_replace(
                array_keys($this->Parameters),
                array_values($this->Parameters),
                $_page);
    }

    public function output()
    {
        $this->set(array(
            'page-css' => $this->CSS,
            'page-js' => $this->Javascript,
            'page-body' => $this->parse($this->Body)));

        return $this->parse($this->Template);
    }

    public function clear()
    {
        $this->Template = null;
    }
}
?>
