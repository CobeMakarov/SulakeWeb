<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class DataObjectSQLi implements DataObject
{
    ################################################
    //Our query object
    private $_STMT, $rows = array(), $assoc = false;

    public function __construct($_object, $_link)
    {
        $this->_STMT = $_link;

        mysqli_stmt_store_result($_link);
    }

    public function result()
    {
        //
    }

    ################################################
    //credits : Jos Piek
    private function stmt_assoc(&$stmt, array &$out)
    {
        $data = mysqli_stmt_result_metadata($stmt);

        $fields = array($this->_STMT);

        $out = array();

        while ($field = mysqli_fetch_field($data))
        {
            $fields[] =& $out[$field->name];
        }

        call_user_func_array('mysqli_stmt_bind_result', $fields);
    }

    public function fetch_array()
    {
        if (!$this->assoc)
        {
            $this->assoc = true;

            $this->stmt_assoc($this->_STMT, $this->rows);
        }

        if (!$this->_STMT->fetch())
        {
            $this->assoc = false;

            $this->rows = array();
        }

        $data = array();

        foreach ($this->rows as $key => $value)
        {
            $data[$key] = $value;
        }

        return ($this->assoc) ? $data : false;
    }

    public function num_rows()
    {
        return $this->_STMT->num_rows();
    }
}
?>
