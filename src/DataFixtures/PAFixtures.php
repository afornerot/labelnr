<?php

namespace App\DataFixtures;

use App\Entity\PA;
use App\Repository\MaterialityRepository;
use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PAFixtures extends Fixture
{
    public function __construct(
        private ThematicRepository $thematicRepository,
        private MaterialityRepository $materialityRepository,
        private PARepository $paRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            ['thematic' => '1', 'materiality' => '003', 'code' => 'PA01', 'title' => 'Intégrer le Numérique Responsable (NR) dans la stratégie de l\'organisation'],
            ['thematic' => '1', 'materiality' => '003', 'code' => 'PA02', 'title' => 'Formaliser sa politique numérique responsable'],
            ['thematic' => '1', 'materiality' => '003', 'code' => 'PA03', 'title' => 'Déployer sa politique "numérique responsable'],

            ['thematic' => '2', 'materiality' => '002', 'code' => 'PA04', 'title' => 'Animer la démarche pour faire adhérer les parties prenantes internes et externes'],
            ['thematic' => '2', 'materiality' => '002', 'code' => 'PA05', 'title' => 'Intégrer l\'accessibilité numérique'],
            ['thematic' => '2', 'materiality' => '002', 'code' => 'PA06', 'title' => 'Favoriser et accompagner le développement des compétences en NR'],
            ['thematic' => '2', 'materiality' => '002', 'code' => 'PA07', 'title' => 'Faire de sa communication une vitrine de son engagement NR'],

            ['thematic' => '3', 'materiality' => '002', 'code' => 'PA08', 'title' => 'Favoriser la sobriété et l\'allongement de la durée de vie dès la phase d\'achats'],
            ['thematic' => '3', 'materiality' => '002', 'code' => 'PA09', 'title' => 'Adopter une gestion responsable de ses équipements (poste de travail, impression, téléphonie, consommables...)'],
            ['thematic' => '3', 'materiality' => '002', 'code' => 'PA10', 'title' => 'Adopter une gestion responsable de ses logiciels, applications et services distants'],
            ['thematic' => '3', 'materiality' => '002', 'code' => 'PA11', 'title' => 'Adopter une gestion responsable de ses infrastructures (réseaux, serveurs, centre de données...)'],
            ['thematic' => '3', 'materiality' => '002', 'code' => 'PA12', 'title' => 'Adopter une gestion responsable des données'],

            ['thematic' => '4', 'materiality' => '002', 'code' => 'PA13', 'title' => 'Encourager la mutualisation des outils et de la connaissance'],
            ['thematic' => '4', 'materiality' => '002', 'code' => 'PA14', 'title' => 'Valoriser le numérique comme levier d\'action'],

            ['thematic' => '5', 'materiality' => '003', 'code' => 'PA17', 'title' => 'Promouvoir le NR dans ses offres commerciales'],
            ['thematic' => '5', 'materiality' => '002', 'code' => 'PA18', 'title' => 'Réaliser une prestation en accord avec la démarche NR'],
            ['thematic' => '5', 'materiality' => '002', 'code' => 'PA19', 'title' => 'Intégrer le NR à l\'amélioration continue de ses prestations'],
        ];

        foreach ($data as $item) {
            $thematic = $this->thematicRepository->findOneBy(['code' => $item['thematic']]);
            $materiality = $this->materialityRepository->findOneBy(['code' => $item['materiality']]);
            if ($thematic) {
                $pa = $this->paRepository->findOneBy(['code' => $item['code']]);
                if (!$pa) {
                    $pa = new PA();
                    $pa->setCode($item['code']);
                    $pa->setMateriality($materiality);
                    $this->addReference('pa_'.$item['code'], $pa);
                }

                $pa->setTitle($item['title']);
                $pa->setThematic($thematic);
                $manager->persist($pa);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ThematicFixtures::class,
        ];
    }
}
