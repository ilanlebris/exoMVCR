<?php

require_once("model/Animal.php");

class ViewJSON{
    public static function prepareAnimalJSON(Animal $animal){
        header('Content-Type: application/json;');
        $array = array("name"=>$animal->getName(), "species"=>$animal->getSpecies(), "age"=>$animal->getAge());
        echo json_encode($array);
    }
}