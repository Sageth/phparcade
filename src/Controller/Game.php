<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 10/15/2017
 * Time: 12:48 PM
 */

namespace PhpArcade\Controller;

class Game extends \PhpArcade\Controller\Controller
{
    public function indexAction($id, $passedName)
    {
        $game = \Games::getGame($id);
        $actualName = $game['name'];
        $actualNameWithHtml = $actualName . '.html';

        if ($actualNameWithHtml != urldecode($passedName)) {
            header('Location: /game/' . $id . '/' . urlencode($actualNameWithHtml));
            exit();
        } else {
            $_GET['params'] = 'game/' . $id . '/' . $actualName;
            //require_once 'plugins/site/themes/responsive/game.php';
        }

        $params = [
            0 => $actualName,
            1 => $id
        ];

        $templates = new \League\Plates\Engine('plugins/site/themes/responsive');
        echo $templates->render('game', ['params' => $params, 'page' => 'game']);

        return true;
    }
}