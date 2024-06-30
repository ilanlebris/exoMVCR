<?php

require_once('model/Animal.php');
require_once("view/ViewJSON.php");

class Controller{

    private $view;
    private $animalsTab;

    public function __construct(View $view, AnimalStorage $animalsTab){
        $this->view = $view;
        $this->animalsTab = $animalsTab;
    }

    public function getView(){
        return $this->view;
    }

    public function showInformation($id) {
        if (array_key_exists($id,$this->animalsTab->readAll())){
            $this->view->prepareAnimalPage($this->animalsTab->read($id), $id);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showInformationJSON($id){
        if (array_key_exists($id,$this->animalsTab->readAll())){
            ViewJSON::prepareAnimalJSON($this->animalsTab->read($id));
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showHome(){
        $this->view->prepareHomePage();
    }

    public function showList(){
        $this->view->prepareListPage($this->animalsTab->readAll());
    }

    public function saveNewAnimal(array $data){
        $animalBuilder = new AnimalBuilder($data);
        if($animalBuilder->isValid() === true){
            $animalCree = $animalBuilder->createAnimal();
            $id = $this->animalsTab->create($animalCree);
            $this->view->displayAnimalCreationSuccess($id);
        } else {
            $this->view->prepareAnimalCreationPage($animalBuilder);
        }
    }

    public function saveUpdate(array $data, $id){
        $animalBuilder = new AnimalBuilder($data);
        if($animalBuilder->isValid() === true){
            $animalModifie = $animalBuilder->createAnimal();
            if($this->animalsTab->update($id, $animalModifie)){
                $this->view->displayAnimalUpdateSuccess($id);
            } else {
                $this->view->prepareAnimalUpdatePage($animalBuilder, $id);
            }
        } else {
            $this->view->prepareAnimalUpdatePage($animalBuilder, $id);
        }
    }

    public function confirmDelete($id){
        if($this->animalsTab->delete($id) === true){
            $this->view->displayAnimalDelete("Succès de la suppression");
        } else {
            $this->view->displayAnimalDelete("Echec de la suppression");
        }
    }
}