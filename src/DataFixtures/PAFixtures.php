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
