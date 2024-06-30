<?php 

require_once('model/AnimalBuilder.php');

class View{

    private $title;
    private $content;
    private $router;
    private $menu;
    private $feedback;

    public function __construct(Router $router, $feedback){
        $this->router = $router;
        $this->feedback = $feedback;
        $this->content="";
        $this->menu = array(
            array("url/exoMVCR/site.php", "Accueil"),
            array("url/exoMVCR/site.php/action/liste", "Liste"),
            array("url/exoMVCR/site.php/action/nouveau", "Ajout d'un animal")
        );
    }

    public function render(){
        $menu = $this->prepareMenu();
        $content = <<<EOT
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="style.css">
                <title>$this->title</title>
            </head>
            <body>
                <p>Status de l'opération: <strong>$this->feedback</strong></p>
            <nav>$menu</nav>
            <h1>$this->title</h1>
            $this->content
            </body>
            </html>
            EOT;
            echo $content;
        }
        
        public function prepareTestPage(){
            $this->title = "test";
            $this->content .= "<p>content test</p>";
            $this->render();
        }
        
        public function prepareHomePage(){
        $this->title = "Page d'accueil";
        $this->content .= "<p>Bienvenue</p>";
        $this->render(); 
    }

    public function prepareAnimalPage(Animal $animal, $id){
        $this->title = "Page sur " . $animal->getName();
        $updateUrl = $this->router->getAnimalUpdateURL($id);
        $deleteUrl = $this->router->getAnimalDeleteURL($id);
        $this->content .= "<p>" . $animal->getName() . " est un animal de l'espèce " . $animal->getSpecies() . ", il est agé de " . $animal->getAge() . " an(s)</p>
                        <a href='" . $updateUrl . "'>Modifier</a>
                        <a href='" . $deleteUrl . "'>Supprimer</a>";
        $this->render();
    }
    
    public function prepareUnknownAnimalPage(){
        $this->title = "Page d'erreur";
        $this->content .= "<p>Animal inconnu</p>";
        $this->render();       
    }
    
    public function prepareListPage(Array $animals){
        $this->title = "Liste des animaux";
        $this->content .= "<ul>";
        foreach(array_keys($animals) as $animal){
            $this->content .= "<li id=\"$animal\"><a href=" . $this->router->getAnimalURL($animal) . " >" . $animals[$animal]->getName() . "</a>
            <button class=\"detail\" type=\"button\" name=\"detail\" value=\"$animal\" data-toggle=\"false\">afficher details</button>
            </li>\n";
        }
        $this->content .= "</ul>";
        $script = file_get_contents("./src/view/detail.js");
        $this->content .="<script>$script</script>";
        $this->render();
    }

    public function prepareMenu(){
        $text = "<h2>Menu</h2>
        <ul>";
        foreach ($this->menu as $elt) {
            $text .= "<li><a href=" . $elt[0] . ">" . $elt[1] . "</a>";
        }
        $text .= "</ul>";
        return $text;
    }
    
    public function prepareDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }
    
    public function prepareAnimalCreationPage(AnimalBuilder $animal){
        $saveUrl = $this->router->getAnimalSaveURL();
        $error = $animal->getError();
        $data = $animal->getData();
        $name = $data[AnimalBuilder::NAME_REF];
        $species = $data[AnimalBuilder::SPECIES_REF];
        $age = $data[AnimalBuilder::AGE_REF];
        $this->title = "Nouveau animal";
        $this->content = <<<EOT
        <form action=$saveUrl method="post">
            <strong class="error">$error</strong>
            <ul>
                <li>
                    <label for="name">Nom:</label>
                    <input type="text" id="name" name="name" value="$name">
                </li>
                <li>
                    <label for="species">Espece:</label>
                    <input type="text" id="species" name="species" value="$species">
                </li>
                <li>
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="$age" min="0">
                </li>
            </ul>
            <button type="submit">Sauvegarder</button>
        </form>
      
        EOT;

        $this->render();
    }

    public function prepareAnimalUpdatePage(AnimalBuilder $animal, $id){
        $saveUrl = $this->router->getAnimalSaveUpdateURL($id);
        $error = $animal->getError();
        $data = $animal->getData();
        $name = $data[AnimalBuilder::NAME_REF];
        $species = $data[AnimalBuilder::SPECIES_REF];
        $age = $data[AnimalBuilder::AGE_REF];
        $this->title = "Modification d'un animal";
        $this->content = <<<EOT
        <form action=$saveUrl method="post">
            <strong class="error">$error</strong>
            <ul>
                <li>
                    <label for="name">Nom:</label>
                    <input type="text" id="name" name="name" value="$name">
                </li>
                <li>
                    <label for="species">Espece:</label>
                    <input type="text" id="species" name="species" value="$species">
                </li>
                <li>
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="$age" min="0">
                </li>
            </ul>
            <button type="submit">Sauvegarder</button>
        </form>
      
        EOT;

        $this->render();
    }

    public function prepareAnimalDeletePage($id){
        $deleteUrl = $this->router->getAnimalConfimDeleteURL($id);
        $returnUrl = $this->router->getAnimalURL($id);
        $this->title = "Suppression d'un animal";
        $this->content = <<<EOT
        <form action=$deleteUrl method="post">
            <button type="submit">Confirmer</button>
        </form>
        <form action=$returnUrl method="post">
            <button type="submit">Annuler</button>
        </form>
      
        EOT;

        $this->render();
    }

    public function displayAnimalCreationSuccess($id){
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Succès de l'insertion");
    }

    public function displayAnimalUpdateSuccess($id){
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Succès de la modification");
    }

    public function displayAnimalDelete($stat){
        $this->router->POSTredirect($this->router->getListURL(), $stat);
    }

}