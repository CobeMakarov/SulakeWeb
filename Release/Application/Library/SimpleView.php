<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class SimpleView
{
    private $Content;

    public function __construct($file)
    {
        $this->Content = file_get_contents('Public/Themes/' . THEME . '/Widgets/' . $file . '.html');
    }

    public function replace($Search, $Replace)
    {
        $this->Content = str_ireplace(
                '['.$Search.']',
                $Replace,
                $this->Content
                );
    }

    public function result()
    {
        return $this->Content;
    }
}
?>
