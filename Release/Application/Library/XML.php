<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class XML
{
    public $XMLObject;

    public function __construct($XML)
    {
        $this->XMLObject = simplexml_load_file($XML);

        return $this->XMLObject;
    }
}
?>
