<?php

class AnimalBuilder{

    private $data;
    private $error;

    const NAME_REF = "Name";
    const SPECIES_REF = "species";
    const AGE_REF = "age";

    public function __construct($data){
        $this->data = $data;
        $this->error = null;
    }

    public function getData(){
        return $this->data;
    }

    public function getError(){
        return $this->error;
    }

    public function createAnimal(){
        return new Animal($this->data[self::NAME_REF], $this->data[self::SPECIES_REF], $this->data[self::AGE_REF]);
    }

    public function isValid(){
        $name = $this->data[self::NAME_REF];
        $species = $this->data[self::SPECIES_REF];
        $age = $this->data[self::AGE_REF];
        $text = $name . $species .$age;
        if(preg_match("/[><\"'&]+/", $text) !== 0){
            $this->error .= "Character non valide : > < \" ' &<br>";
        }
        if($name === ""){
            $this->error .= "il manque un nom<br>";
        }
        if($species === ""){
            $this->error .= "il manque une espèce<br>";
        }
        if($age < 0){
            $this->error .= "l'age doit être positif<br>";
        }
        return ($this->error === null) ? true : false;
    }

}