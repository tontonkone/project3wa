<?php
namespace Src\controller\frontController;

use Src\core\Rendering;
use Src\controller\HomeController;

class UserController extends HomeController{


    public function displayHome()
    {
        $articles = $this->articlesRepository->selectAllElements();
        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("homePage", compact("title", 'titleH1', 'articles'));
    }

}