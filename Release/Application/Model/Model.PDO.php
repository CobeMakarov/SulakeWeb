<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class mPDO implements Model
{
    public $Query, $Count = 0;

    private $Link, $Host, $Name, $User, $Password, $Connected, $STMT, $Params;

    public function __construct($_database)
    {
        include('DataObject.PDO.php');

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
            $this->Link = new PDO(
                    'mysql:dbname='.$this->Name.
                    ';host='.$this->Host,
                    $this->User,
                    $this->Password);
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }

        $this->Connected = true;
    }

    public function disconnect()
    {
        $this->Link = null;

        $this->Connected = false;
    }

    public function secure($_variable)
    {
        return stripslashes(htmlentities($_variable));
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

    public function bind($Params)
    {
        if (!is_array($Params))
        {
            // -.-
        }

        $this->Params = $Params;

        return $this;
    }

    public function execute()
    {
        if(!$this->STMT->execute($this->Params))
        {
            return $this->STMT->error;
        }

        $this->Count++;

        return new DataObjectPDO(null, $this->STMT);
    }
}
?>
