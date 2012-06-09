<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

interface DataObject
{
    public function __construct($_object, $_link);

    public function result();

    public function fetch_array();

    public function num_rows();
}
?>
