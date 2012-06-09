<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class Cache
{
    public $Directory;

    public function __construct()
    {
        $this->Directory = '../Storage/Cache/';
    }

    private function CreateDirectory($Location)
    {
        file_put_contents($Location . '/.ignore', null); ## Just create the file for now.
    }

    private function CreateFile($Location)
    {
        file_put_contents($Location, null);
    }

    private function ReadFile($Location)
    {
        return file_get_contents($Location);
    }

    public function Store($FileName, $FileContents, $Location = 'Logs')
    {
        if (!file_exists($this->Directory . $Location)) ## Folder doesn't exist.
        {
            $this->CreateDirectory($this->Directory . $Location);
        }

        if (!file_exists($this->Directory . $Location . '/' . $FileName)) ## File doesn't exist!
        {
            $this->CreateFile($this->Directory . $Location . '/' . $FileName);
        }

        $Contents = $this->ReadFile($this->Directory . $Location . '/' . $FileName);

        if (strlen($Contents) > 1)
        {
            file_put_contents($this->Directory . $Location . '/' . $FileName, $Contents . "\r\n" . base64_encode($FileContents));
        }
        else
        {
            file_put_contents($this->Directory . $Location . '/' . $FileName, base64_encode($FileContents));
        }
    }

    public function Read($FileName, $Location = 'Logs')
    {
        if (!file_exists($this->Directory . $Location . '/' . $FileName))
        {
            return null; ## Not exists ;)
        }

        $Return = array();

        foreach(file($this->Directory . $Location . '/' . $FileName) as $Line)
        {
            array_push($Return, base64_decode($Line));
        }

        return $Return;
    }
}
?>
