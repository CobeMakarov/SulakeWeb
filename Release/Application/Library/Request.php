<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class Request
{
    private $POST, $GET;

    public function __construct()
    {
       /*
        * Filter POST and GET automatically
        */

        foreach($_POST as $Key => $Value)
        {
            $_POST[$Key] = $this->Clean($Value);
        }

        foreach($_GET as $Key => $Value)
        {
            $_GET[$Key] = $this->Clean($Value);
        }

        $this->POST = $_POST;
        $this->GET = $_GET;
    }

    private function Clean($Request, $IgnoreHTML = false, $InsertBR = false) ## Jos
    {
        $Request = stripslashes(trim($Request));

        if (!$IgnoreHTML)
        {
            $Request = htmlentities($Request);
        }

        if ($InsertBR)
        {
            $Request = nl2br($Request);

        }

        return $Request;
    }

    public function FormatGET($String)
    {
        if (strpos($String, '&')) ## Multiple GETS
        {
            $Request = explode('&', $String);

            $RandomKey = 0;

            foreach($Request as $Line)
            {
                if (!strpos($String, '='))
                {
                    $_GET[$RandomKey] == $Line; ## {url}?value1&value2

                    $RandomKey++;
                    continue;
                }
                else
                {
                    $RequestExplode = explode('=' , $Line);

                    $_GET[$RequestExplode[0]] = $this->Clean($RequestExplode[1]); ## $_GET[key] = value;
                }
            }
        }
        else
        {
            if (!strpos($String, '='))
            {
                $_GET[0] = $String; ## {url}?value
            }
            else
            {
                $RequestExplode = explode('=' , $String);

                $_GET[$RequestExplode[0]] = $this->Clean($RequestExplode[1]); ## $_GET[key] = value;
            }
        }
    }

    public function RetrievePOST()
    {
        return $this->POST;
    }

    public function RetrieveGET()
    {
        return $this->GET;
    }
}
?>
