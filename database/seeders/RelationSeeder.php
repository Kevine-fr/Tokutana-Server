<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Relation;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création de 15 relations
        Relation::create([
            'title' => 'Ami',
            'subtitle' => 'Personne avec qui on a une relation d\'amitié.'
        ]);
        Relation::create([
            'title' => 'Collègue',
            'subtitle' => 'Personne avec qui on travaille.'
        ]);
        Relation::create([
            'title' => 'Famille',
            'subtitle' => 'Personne liée par des liens de parenté.'
        ]);
        Relation::create([
            'title' => 'Connaissance',
            'subtitle' => 'Personne que l\'on connaît mais avec qui on n\'a pas de relation proche.'
        ]);
        Relation::create([
            'title' => 'Partenaire',
            'subtitle' => 'Personne avec qui l\'on a une relation amoureuse ou professionnelle.'
        ]);
        Relation::create([
            'title' => 'Voisin',
            'subtitle' => 'Personne vivant à proximité de chez soi.'
        ]);
        Relation::create([
            'title' => 'Mentor',
            'subtitle' => 'Personne expérimentée qui conseille ou guide.'
        ]);
        Relation::create([
            'title' => 'Subordonné',
            'subtitle' => 'Personne travaillant sous la direction d\'un supérieur.'
        ]);
        Relation::create([
            'title' => 'Employeur',
            'subtitle' => 'Personne qui emploie quelqu\'un.'
        ]);
        Relation::create([
            'title' => 'Client',
            'subtitle' => 'Personne à qui l\'on fournit des services ou produits.'
        ]);
        Relation::create([
            'title' => 'Fournisseur',
            'subtitle' => 'Personne ou entreprise qui fournit des produits ou services.'
        ]);
        Relation::create([
            'title' => 'Ennemi',
            'subtitle' => 'Personne avec qui on a une relation de conflit.'
        ]);
        Relation::create([
            'title' => 'Ex',
            'subtitle' => 'Personne avec qui l\'on a eu une relation amoureuse, mais terminée.'
        ]);
        Relation::create([
            'title' => 'Parent',
            'subtitle' => 'Personne qui a un enfant, généralement en relation directe.'
        ]);
        Relation::create([
            'title' => 'Confident',
            'subtitle' => 'Personne à qui l\'on confie ses secrets et pensées personnelles.'
        ]);
    }
}
