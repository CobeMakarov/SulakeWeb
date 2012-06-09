<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class User
{
    private $User;

    public function __construct($SiteInstance, $Session)
    {
        if (!is_array($Session))
        {
            return null;
        }

        $Check = $SiteInstance->GetModel()->prepare('SELECT * FROM users WHERE id = ?')
                ->bind(array($Session['id']))->execute();

        if ($Check->num_rows() == 0)
        {
            return null;
        }

        while($SI = $Check->fetch_array())
        {
            $this->User = $SI; ## Just to update our credentials.
        }

        foreach($_SESSION['habbo'] as $Key => $Value)
        {
            $_SESSION['habbo'][$Key] = $this->User[$Key]; ## Update it to the session as well.
        }
    }
}
?>
