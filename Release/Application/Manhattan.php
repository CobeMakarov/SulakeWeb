<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class Manhattan
{
    public $Config = array(), $Start;

    private $Model, $Router, $Request, $Cache;

    public function __autoload()
    {
        include('Manhattan.php');
    }

    public function __construct()
    {
        $this->GrabConfig();

        $this->IncludeInterfaces();

        $this->StartModel();

        $this->IncludeLibrary();

        $this->Start = microtime(true);
    }

    private function GrabConfig()
    {
        foreach (glob('Config/*.php') as $File)
        {
            include $File;
        }

        $this->Config = $Config;

        date_default_timezone_set($this->Config['Site']['Timezone']);
    }

    private function IncludeLibrary()
    {
        foreach (glob('Application/Library/*.php') as $Class)
        {
            include $Class;
        }

        $this->Request = new Request();
        $this->Router = new Router();
        $this->Cache = new Cache();
        new User($this, $_SESSION['habbo']);
    }

    private function IncludeInterfaces()
    {
        foreach (glob('Application/Interfaces/*.php') as $Interface)
        {
            include $Interface;
        }
    }

    private function StartModel()
    {
       if (!include ('Model/Model.' . $this->Config['Database']['Type']) . '.php')
       {
           die(NULL_ERROR . 'Unknown Database Type!');
       }

       $ClassName = ($this->Config['Database']['Type'] == 'MySQL') ? 'MySQL' : 'm' . $this->Config['Database']['Type'];

       $this->Model = new $ClassName($this->Config['Database']);
    }

    public function GetModel()
    {
        return $this->Model;
    }

    public function GetRequests()
    {
        return $this->Request;
    }

    public function GetRouter()
    {
        return $this->Router;
    }

    public function GetCache()
    {
        return $this->Cache;
    }

    public function GetHash($Variable)
    {
        return $this->Config['Site']['Hash']($Variable);
    }
}
?>
