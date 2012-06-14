<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

class ArticlesController implements Controller
{
    private $Manhattan;

    /*
     * Only used to transfer our variable as needed!
     */
    public function __construct($Manhattan)
    {
        $this->Manhattan = $Manhattan;
    }

    public function action()
    {
        if ($this->check())
        {
            if (!AUTHENICATED)
            {
                $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'index', true);
                return;
            }

            if (AUTHENICATED && !ACTIVATED)
            {
                $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'characters', true);
                return;
            }

            $Id = $_GET[0];

            $View = new View();

            new ControllerHelper($View, 'page-hard-article');

            $Article = $this->Manhattan->GetModel()->prepare('SELECT * FROM sulake_news WHERE id = ?')
                    ->bind(array($Id))->execute();

            if ($Article->num_rows() == 0)
            {
                $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'me', true);
            }

            while($A = $Article->fetch_array())
            {
                $View->set(array(
                    'article_title' => $A['title'],
                    'article_author' => $A['author'],
                    'article_date' => $A['date'],
                    'article_story' => nl2br($A['story']),
                    'article_image' => $A['image'],
                    'article_id' => $Id,
                    'page-tagline' => $A['title']));
            }

            foreach($_SESSION['habbo'] as $Key => $Value)
            {
                $View->set(array('habbo_' . $Key => $Value));
            }

            $View->set(array(
                'page-title' => $this->Manhattan->Config['Site']['Title'],
                'site_title' => $this->Manhattan->Config['Site']['Title'],
                'users_online' => 0,
                'query_count' => $this->Manhattan->GetModel()->_count,
                'exec_time' => round(microtime(true) - $this->Manhattan->Start, 3)));

            echo $View->output();
        }
        else
        {
            $this->Manhattan->GetRouter()->Direct($this->Manhattan, 'error');
        }
    }

    public function check()
    {
        return file_exists('Public/Themes/' . THEME . '/page-hard-article.html');
    }
}
?>
