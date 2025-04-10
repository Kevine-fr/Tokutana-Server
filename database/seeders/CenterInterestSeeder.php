<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CenterInterest;

class CenterInterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création de 15 centres d'intérêts
        CenterInterest::create([
            'title' => 'Musique',
            'subtitle' => 'Passion pour les genres musicaux variés.'
        ]);
        CenterInterest::create([
            'title' => 'Voyage',
            'subtitle' => 'Explorer de nouveaux endroits et cultures.'
        ]);
        CenterInterest::create([
            'title' => 'Sport',
            'subtitle' => 'Pratique d\'activités physiques et sportives.'
        ]);
        CenterInterest::create([
            'title' => 'Cuisine',
            'subtitle' => 'Passion pour la préparation de repas et de recettes.'
        ]);
        CenterInterest::create([
            'title' => 'Lecture',
            'subtitle' => 'Appréciation de la lecture de livres, romans, etc.'
        ]);
        CenterInterest::create([
            'title' => 'Technologie',
            'subtitle' => 'Passion pour les nouvelles technologies et l\'innovation.'
        ]);
        CenterInterest::create([
            'title' => 'Cinéma',
            'subtitle' => 'Appréciation des films et séries.'
        ]);
        CenterInterest::create([
            'title' => 'Peinture',
            'subtitle' => 'Passion pour l\'art visuel et la création artistique.'
        ]);
        CenterInterest::create([
            'title' => 'Jeux vidéo',
            'subtitle' => 'Passion pour les jeux électroniques.'
        ]);
        CenterInterest::create([
            'title' => 'Photographie',
            'subtitle' => 'Art de capturer des moments à travers l\'objectif.'
        ]);
        CenterInterest::create([
            'title' => 'Mode',
            'subtitle' => 'Passion pour les tendances vestimentaires et l\'esthétique.'
        ]);
        CenterInterest::create([
            'title' => 'Fitness',
            'subtitle' => 'Engagement pour la santé et l\'exercice physique.'
        ]);
        CenterInterest::create([
            'title' => 'Nature',
            'subtitle' => 'Passion pour les environnements naturels et la faune.'
        ]);
        CenterInterest::create([
            'title' => 'Astrologie',
            'subtitle' => 'Intérêt pour les signes astrologiques et les prédictions.'
        ]);
        CenterInterest::create([
            'title' => 'Astronomie',
            'subtitle' => 'Passion pour l\'étude des étoiles et des planètes.'
        ]);
    }
}
