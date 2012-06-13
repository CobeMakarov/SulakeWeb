<?php
/*
 * @project Manhattan Project
 * @author Cobe Makarov
 * @description
 *
 */

class ControllerHelper
{
    private $Node;

    public function __construct(&$ViewObject, $Page)
    {
        $XML = new XML('Public/Themes/' . THEME . '/Map.xml');

        if (!$XML)
        {
            die('The theme: ' . THEME . ' does not have a proper page map!');
        }

        foreach($XML->XMLObject->controller as $RootNode)
        {
            if ($RootNode->id == $Page)
            {
                $this->Node = $RootNode;
                break;
            }
        }

        foreach($this->Node->html as $HTML)
        {
            $ViewObject->add($HTML);
        }

        foreach($this->Node->css as $CSS)
        {
            $ViewObject->css($CSS);
        }

        foreach($this->Node->js as $JAVASCRIPT)
        {
            $ViewObject->javascript($JAVASCRIPT);
        }
    }
}
?>
