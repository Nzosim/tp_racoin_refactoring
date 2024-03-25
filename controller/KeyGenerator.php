<?php

// Définition de l'espace de noms
namespace controller;

// Importation de la classe ApiKey du modèle
use model\ApiKey;

// Définition de la classe KeyGenerator
class KeyGenerator {

    // Méthode pour afficher la page de génération de clé
    function show($twig, $menu, $chemin, $cat) {
        // Chargement du template
        $template = $twig->load("key-generator.html.twig");
        // Définition du menu
        $menu = array(array('href' => $chemin, 'text' => 'Acceuil'),
                array('href' => $chemin."/search", 'text' => "Recherche")
        );
        // Rendu du template avec les variables définies
        echo $template->render(array("breadcrumb" => $menu, "chemin" => $chemin, "categories" => $cat));
    }

    // Méthode pour générer une clé
    function generateKey($twig, $menu, $chemin, $cat, $nom) {
        // Suppression des espaces dans le nom
        $nospace_nom = str_replace(' ', '', $nom);

        // Si le nom est vide après suppression des espaces
        if($nospace_nom === '') {
            // Chargement du template d'erreur
            $template = $twig->load("key-generator-error.html.twig");
            // Définition du menu
            $menu = array(
                array('href' => $chemin,'text' => 'Acceuil'),
                array('href' => $chemin."/search",'text' => "Recherche")
            );

            // Rendu du template d'erreur avec les variables définies
            echo $template->render(array("breadcrumb" => $menu, "chemin" => $chemin, "categories" => $cat));
        } else {
            // Chargement du template de résultat
            $template = $twig->load("key-generator-result.html.twig");
            // Définition du menu
            $menu = array(
                array('href' => $chemin,'text' => 'Acceuil'),
                array('href' => $chemin."/search", 'text' => "Recherche")
            );

            // Génération d'une clé unique de 13 caractères
            $key = uniqid();
            // Création d'un nouvel objet ApiKey
            $apikey = new ApiKey();

            // Définition des propriétés de l'objet ApiKey
            $apikey->id_apikey = $key;
            $apikey->name_key = htmlentities($nom);
            // Sauvegarde de l'objet ApiKey dans la base de données
            $apikey->save();

            // Rendu du template de résultat avec les variables définies
            echo $template->render(array("breadcrumb" => $menu, "chemin" => $chemin, "categories" => $cat, "key" => $key));
        }

    }

}

?>