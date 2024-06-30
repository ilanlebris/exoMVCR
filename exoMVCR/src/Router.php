<?php 

require_once("view/View.php");
require_once("control/Controller.php");
require_once("model/AnimalStorage.php");

class Router{

    public function main(AnimalStorage $animals){
        $feedback = "Pas d'action";
        if(key_exists('feedback',$_SESSION)){
            $feedback = $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }

        $view = new View($this,$feedback);
        $controller = new Controller($view, $animals);
        $param_url = array();
        $server_path = "";

        if(key_exists('PATH_INFO', $_SERVER)){
            $server_path = substr($_SERVER['PATH_INFO'],1);
            if(strlen($server_path) >= 1 && $server_path[-1] == "/"){
                $server_path = substr($server_path, 0, -1);
            }
            $param_url = explode("/", $server_path);
        }

        if(!empty($server_path)){
            if($param_url[0] === "action"){
                if($param_url[1] === "liste"){
                    $controller->showList();
                }
                elseif($param_url[1] === "nouveau"){
                    $view->prepareAnimalCreationPage(new AnimalBuilder(array(AnimalBuilder::NAME_REF => "", AnimalBuilder::SPECIES_REF => "", AnimalBuilder::AGE_REF => 0)));
                } 
                elseif($param_url[1] === "json"){
                    $id = $param_url[2];
                    $controller->showInformationJSON($id);
                }
                elseif($param_url[1] === "sauverNouveau"){
                    $name = htmlspecialchars($_POST["name"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
                    $species = htmlspecialchars($_POST["species"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
                    $age = htmlspecialchars($_POST["age"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
                    $controller->saveNewAnimal(array(AnimalBuilder::NAME_REF => $name, AnimalBuilder::SPECIES_REF => $species, AnimalBuilder::AGE_REF => $age));
                } 
                /*Modification d'un animal*/
                elseif($param_url[1] === "modifier" && $param_url[2] !== null){
                    $id = $param_url[2];
                    if(key_exists($id, $animals->readAll())){
                        $name = $animals->read($id)->getName();
                        $species = $animals->read($id)->getSpecies();
                        $age = $animals->read($id)->getAge();
                        $view->prepareAnimalUpdatePage(new AnimalBuilder(array(AnimalBuilder::NAME_REF => $name, AnimalBuilder::SPECIES_REF => $species, AnimalBuilder::AGE_REF => $age)), $id);
                    } else {
                        $view->prepareUnknownAnimalPage();
                    }
                } elseif($param_url[1] === "sauverModifier"){
                    $name = htmlspecialchars($_POST["name"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
                    $species = htmlspecialchars($_POST["species"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
                    $age = htmlspecialchars($_POST["age"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
                    $controller->saveUpdate(array(AnimalBuilder::NAME_REF => $name, AnimalBuilder::SPECIES_REF => $species, AnimalBuilder::AGE_REF => $age), $param_url[2]);
                }
                /*Suppression d'un animal*/
                elseif($param_url[1] === "supprimer" && $param_url[2] !== null){
                    $id = $param_url[2];
                    if(key_exists($id, $animals->readAll())){
                        $view->prepareAnimalDeletePage($id);
                    } else {
                        $view->prepareUnknownAnimalPage();
                    }
                } elseif($param_url[1] === "confirmerSuppression"){
                    $controller->confirmDelete($param_url[2]);
                }
                else{
                    $controller->showHome();
                }
            }
            else{
                $controller->showInformation($param_url[0]);
            }
        }
        else {
            $controller->showHome();
        }
    }

    public function getAnimalURL($id){
        return "url/exoMVCR/site.php/" . $id;
    }

    public function getListURL(){
        return "url/exoMVCR/site.php/action/liste";
    }

    public function getAnimalCreationURL(){
        return $_SERVER["SCRIPT_NAME"]."/action/nouveau";
    }

    public function getAnimalSaveURL(){
        return $_SERVER["SCRIPT_NAME"]."/action/sauverNouveau";
    }

    public function getAnimalUpdateURL($id){
        return $_SERVER["SCRIPT_NAME"]."/action/modifier/" . $id;
    }

    public function getAnimalSaveUpdateURL($id){
        return $_SERVER["SCRIPT_NAME"]."/action/sauverModifier/" . $id;
    }

    public function getAnimalDeleteURL($id){
        return $_SERVER["SCRIPT_NAME"]."/action/supprimer/" . $id;
    }

    public function getAnimalConfimDeleteURL($id){
        return $_SERVER["SCRIPT_NAME"]."/action/confirmerSuppression/" . $id;
    }

    public function POSTredirect($url, $feedback){
        http_response_code(303);
        $_SESSION["feedback"] = $feedback;
        header('Location: ' . $url);
        die();
    }
}