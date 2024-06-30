<?php

class Animal{

    private $name;
    private $species;
    private $age;

    public function __construct($name, $species, $age){
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;
    }

    public function getName(){
        return $this->name;
    }

    public function getSpecies(){
        return $this->species;

    }public function getAge(){
        return $this->age;
    }
}