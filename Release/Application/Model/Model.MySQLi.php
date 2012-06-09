<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class mMySQLi implements Model
{
    public $_query, $_count = 0;

    private $_link, $_host, $_name, $_user, $_password, $_connected, $_STMT;

    public function __construct($_database)
    {
        include('DataObject.MySQLi.php');

        $this->_host = $_database['host'];
        $this->_name = $_database['name'];
        $this->_user = $_database['user'];
        $this->_password = $_database['password'];

        $this->connect();
    }

    public function connect()
    {
        if ($this->_connected)
        {
            return;
        }

        $this->_link = new MySQLi(
                $this->_host,
                $this->_user,
                $this->_password,
                $this->_name);

        if ($this->_link->connect_error)
        {
            trigger_error($this->_link->connect->errno);
        }
        else
        {
            $this->_connected = true;
        }
    }

    public function disconnect()
    {
        $this->_link->close();

        $this->_connected = false;
    }

    public function secure($_variable)
    {
        return $this->_link->real_escape_string($_variable);
    }

    public function prepare($_query)
    {
        $this->_query = $_query;

        if (!$this->_STMT = $this->_link->prepare($_query))
        {
            die($this->_STMT->error);
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
        call_user_func_array(array($this->_STMT, 'bind_param'), $_arguments);

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
        $_type = gettype($_variable);

        return substr($_type, 0, 1); // Return first character!!
    }

    public function execute()
    {
        if(!$this->_STMT->execute())
        {
            return $this->_STMT->error;
        }

        $this->_count++;

        return new DataObjectSQLi(null, $this->_STMT);
    }
}
?>
