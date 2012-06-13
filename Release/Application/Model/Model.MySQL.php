<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class MySQL implements Model
{
    public $Query, $Count = 0;

    private $Link, $Host, $Name, $User, $Password, $Connected;

    public function __construct($_database)
    {
        include('DataObject.MySQL.php');

        $this->Host = $_database['Host'];
        $this->Name = $_database['Name'];
        $this->User = $_database['User'];
        $this->Password = $_database['Password'];

        $this->connect();
    }

    public function connect()
    {
        if ($this->Connected)
        {
            return;
        }

        try
        {
            $this->Link = mysql_connect(
                    $this->Host,
                    $this->User,
                    $this->Password);

            mysql_select_db($this->Name, $this->Link);
        }
        catch(Exception $e)
        {
            trigger_error($e->getMessage());
        }

        $this->Connected = true;
    }

    public function disconnect()
    {
        $this->Link->close();

        $this->Connected = false;
    }

    public function secure($_variable)
    {
        return mysql_real_escape_string($_variable, $this->Link);
    }

    public function prepare($Query)
    {
        $this->Query = $Query;

        return $this;
    }

    public function bind($_params)
    {
        $parameterCount = substrCount($this->Query, '?');

        $parameter_key = 0;

        $parameter_realCount = count($_params);

        for($i = 0; $i < $parameterCount; $i++)
        {
           if ($parameter_key > $parameter_realCount)
           {
               break;
           }

           $this->Query = preg_replace('/\?/', '"' . $_params[$parameter_key] . '"', $this->Query, 1);

           $parameter_key++;
        }

        return $this;
    }

    public function execute()
    {
        $this->Count++;

        return new DataObjectSQL($this->Query, $this->Link);
    }
}
?>
