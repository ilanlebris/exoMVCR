<?php

interface AnimalStorage{
    public function read($id);
    public function readAll();
    /**
     * ajoute à la base l'animal donné en argument
     * @param a animal à ajouter 
     * @return id identifiant de l'animal ainsi créé*/
    public function create(Animal $a);
    /**
     * supprime de la base l'animal correspondant à l'id
     * @param id identifiant à supprimer 
     * @return true si la suppression a été effectuée 
     * @return false si l'identifiant ne correspond à aucun animal. */ 
    public function delete($id);
    /**
     * met à jour dans la base l'animal d'identifiant 
     * donné, en le remplaçant par l'animal donné 
     * @param id identifiant de l'animal à modifier 
     * @param a animal modifier
     * @return true si la mise à jour a bien été effectuée
     * @return false si l'identifiant n'existe pas
     */
    public function update($id, Animal $a);
}