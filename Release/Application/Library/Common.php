<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description Common Functions NOT in a class
 *
 */

function CreatePassword($String1, $String2, $String3)
{
    $HashLength = strlen($String3);

    $String31 = substr($String3, 0, ($HashLength / 2));

    $String32 = substr($String3, (($HashLength / 2) + 1), $HashLength);

    $StartString = sha1($String31);

    $EndString = sha1($String32);

    $Password = $StartString;

    $Password = $Password . sha1($String2 . $String1);

    $Password = $Password . $EndString;

    $Password = $Password . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

    return $Password;
}
?>
