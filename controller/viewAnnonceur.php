<?php

namespace App\controller;

use App\model\Annonce;
use App\model\Annonceur;
use App\model\Photo;

class viewAnnonceur
{

    function afficherAnnonceur($twig, $chemin, $n, $cat)
    {
        $annonceur = annonceur::find($n);
        if (!isset($annonceur)) {
            echo "404";
            return;
        }
        $tmp = annonce::where('id_annonceur', '=', $n)->get();

        $annonces = [];
        foreach ($tmp as $a) {
            $a->nb_photo = Photo::where('id_annonce', '=', $a->id_annonce)->count();
            if ($a->nb_photo > 0) {
                $a->url_photo = Photo::select('url_photo')
                    ->where('id_annonce', '=', $a->id_annonce)
                    ->first()->url_photo;
            } else {
                $a->url_photo = $chemin . '/img/noimg.png';
            }

            $annonces[] = $a;
        }
        $template = $twig->load("annonceur.html.twig");
        echo $template->render(array(
            'nom' => $annonceur,
            "chemin" => $chemin,
            "annonces" => $annonces,
            "categories" => $cat
        ));
    }
}
