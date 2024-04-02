<?php

namespace App\controller;

use App\model\Annonce;
use App\model\Photo;
use App\model\Annonceur;

class getAnnonce
{
    protected $annonce = array();

        // Cette fonction est utilisée pour afficher toutes les annonces
        public function displayAllAnnonce($twig, $menu, $chemin, $cat)
        {
            // Charge le template pour l'affichage des annonces
            $template = $twig->load("index.html.twig");
    
            // Crée un menu de navigation
            $menu     = array(
                array(
                    'href' => $chemin,
                    'text' => 'Acceuil'
                ),
            );
    
            // Récupère toutes les annonces
            $this->getAll($chemin);
    
            // Rend le template avec les données nécessaires
            echo $template->render(array(
                "breadcrumb" => $menu,
                "chemin"     => $chemin,
                "categories" => $cat,
                "annonces"   => $this->annonce
            ));
        }
    
        // Cette fonction est utilisée pour récupérer toutes les annonces
        public function getAll($chemin)
        {
            // Récupère les 12 dernières annonces avec leurs annonceurs associés
            $tmp     = Annonce::with("Annonceur")->orderBy('id_annonce', 'desc')->take(12)->get();
    
            // Ce tableau contiendra les annonces traitées
            $annonce = [];
    
            // Traite chaque annonce
            foreach ($tmp as $t) {
                // Compte le nombre de photos pour cette annonce
                $t->nb_photo = Photo::where("id_annonce", "=", $t->id_annonce)->count();
    
                // S'il y a des photos, obtient l'URL de la première. Sinon, utilise une image par défaut.
                if ($t->nb_photo > 0) {
                    $t->url_photo = Photo::select("url_photo")
                        ->where("id_annonce", "=", $t->id_annonce)
                        ->first()->url_photo;
                } else {
                    $t->url_photo = '/img/noimg.png';
                }
    
                // Obtient le nom de l'annonceur pour cette annonce
                $t->nom_annonceur = Annonceur::select("nom_annonceur")
                    ->where("id_annonceur", "=", $t->id_annonceur)
                    ->first()->nom_annonceur;
    
                // Ajoute l'annonce traitée au tableau
                array_push($annonce, $t);
            }
    
            // Stocke les annonces traitées
            $this->annonce = $annonce;
        }
}
