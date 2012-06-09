<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class MySQL implements Model
{
    public $_query, $_count = 0;

    private $_link, $_host, $_name, $_user, $_password, $_connected;

    public function __construct($_database)
    {
        include('DataObject.MySQL.php');

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

        try
        {
            $this->_link = mysql_connect(
                    $this->_host,
                    $this->_user,
                    $this->_password);

            mysql_select_db($this->_name, $this->_link);
        }
        catch(Exception $e)
        {
            trigger_error($e->getMessage());
        }

        $this->_connected = true;
    }

    public function disconnect()
    {
        $this->_link->close();

        $this->_connected = false;
    }

    public function secure($_variable)
    {
        return mysql_real_escape_string($_variable, $this->_link);
    }

    public function prepare($_query)
    {
        $this->_query = $_query;

        return $this;
    }

    public function bind($_params)
    {
        $parameter_count = substr_count($this->_query, '?');

        $parameter_key = 0;

        $parameter_real_count = count($_params);

        for($i = 0; $i < $parameter_count; $i++)
        {
           if ($parameter_key > $parameter_real_count)
           {
               break;
           }

           $this->_query = preg_replace('/\?/', '"' . $_params[$parameter_key] . '"', $this->_query, 1);

           $parameter_key++;
        }

        return $this;
    }

    public function execute()
    {
        $this->_count++;

        return new DataObjectSQL($this->_query, $this->_link);
    }
}
?>
