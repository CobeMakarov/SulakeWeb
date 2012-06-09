<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description Placeholder
 *
 */

include('Application/Bootstrap.php'); ## Set it off!!

$Page = $_GET['request'];

$URI = str_replace(FORMATTED, null, $_SERVER['REQUEST_URI']);

$Array = explode('?', $URI);

if ($Array) // $_GET Request :)
{
    $Manhattan->GetRequests()->FormatGET($Array[1]);
}

if (strlen($Page) == 0)
{
    $Page = 'index'; ## HTTP Request Trick?
}

define('CURRENT', $Page);

$Manhattan->GetRouter()->Direct($Manhattan, $Page);
?>
