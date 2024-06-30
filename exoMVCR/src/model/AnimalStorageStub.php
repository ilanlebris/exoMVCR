<?php

require_once("AnimalStorage.php");


class AnimalStorageStub implements AnimalStorage{

    public $animalsTab;

    public function __construct(){
        $this->animalsTab = array(
            'medor' => new Animal('Médor', 'chien', '5'),
            'felix' => new Animal('Félix', 'chat', '6'),
            'denver' => new Animal('Denver', 'dinosaure', '6000000'),
        );
    }

    public function read($id){
        foreach(array_keys($this->animalsTab) as $animal){
            if($animal === $id){
                return $this->animalsTab[$animal];
            }
        }
        return NULL;
    }

    public function readAll(){
        return $this->animalsTab;
    }

    public function create($animal){
        ;
    }

    public function delete($id){
        ;
    }

    public function update($animal, $id){}
}