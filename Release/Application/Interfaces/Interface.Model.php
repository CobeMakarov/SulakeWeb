<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

interface Model
{
    public function __construct($_database);

    public function connect();

    public function disconnect();

    public function secure($_variable);

    public function prepare($_query);

    public function bind($_params);

    public function execute();
}
?>
