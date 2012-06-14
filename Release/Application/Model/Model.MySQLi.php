<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class mMySQLi implements Model
{
    public $Query, $Count = 0;

    private $Link, $Host, $Name, $User, $Password, $Connected, $STMT;

    public function __construct($_database)
    {
        include('DataObject.MySQLi.php');

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

        $this->Link = new MySQLi(
                $this->Host,
                $this->User,
                $this->Password,
                $this->Name);

        if ($this->Link->connect_error)
        {
            trigger_error($this->Link->connect->errno);
        }
        else
        {
            $this->Connected = true;
        }
    }

    public function disconnect()
    {
        $this->Link->close();

        $this->Connected = false;
    }

    public function secure($_variable)
    {
        return $this->Link->real_escape_string($_variable);
    }

    public function prepare($Query)
    {
        $this->Query = $Query;

        if (!$this->STMT = $this->Link->prepare($Query))
        {
            die($this->STMT->error);
        }

        return $this;
    }

    public function bind($_params)
    {
        $_types = '';

        foreach($_params as $_key => $_value)
        {
            $_types .= $this->type($_value);
        }

        //Fill our arguments variable with an array of the parameter types
        $_arguments = array($_types);

        //Make sure we have the correct parameters
        $this->retrieve($_params, $_arguments);

        //Bind the parameters
        call_user_func_array(array($this->STMT, 'bind_param'), $_arguments);

        return $this;
    }

    //@credits : Jos Piek
    private function retrieve(array &$array, array &$out)
    {
        //Make sure the system is at a usuable version
        if (strnatcmp(phpversion(),'5.3') >= 0)
        {
            foreach($array as $key => $value)
            {
                $out[] =& $array[$key];
            }
        }
        else
        {
            $out = $array;
        }
    }

    private function type($_variable)
    {
        return substr(gettype($_variable), 0, 1); // Return first character!!
    }

    public function execute()
    {
        if(!$this->STMT->execute())
        {
            return $this->STMT->error;
        }

        $this->Count++;

        return new DataObjectSQLi(null, $this->STMT);
    }
}
?>
