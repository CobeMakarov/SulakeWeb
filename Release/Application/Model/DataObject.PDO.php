<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class DataObjectPDO implements DataObject
{
    ################################################
    //The PDO connection variable
    private $_STMT;

    public function __construct($_query, $_link)
    {
        $this->_STMT = $_link;
    }

    public function result()
    {
        return $this->_STMT->fetchColumn();
    }

    public function fetch_array()
    {
        return $this->_STMT->fetch(PDO::FETCH_ASSOC);
    }

    public function num_rows()
    {
        return $this->_STMT->rowCount();
    }
}
?>
