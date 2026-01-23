<?php

namespace App\DataFixtures;

use App\Entity\Thematic;
use App\Repository\ThematicRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThematicFixtures extends Fixture
{
    public function __construct(
        private ThematicRepository $thematicRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            ['code' => '1', 'title' => 'Stratégie et gouvernance'],
            ['code' => '2', 'title' => 'Soutien à la stratégie NR'],
            ['code' => '3', 'title' => 'Cycle de vie des services numériques'],
            ['code' => '4', 'title' => 'Etendre sa démarche NR'],
            ['code' => '5', 'title' => 'Produits et services des ESN'],
        ];

        foreach ($data as $item) {
            $thematic = $this->thematicRepository->findOneBy(['code' => $item['code']]);
            if (!$thematic) {
                $thematic = new Thematic();
                $thematic->setCode($item['code']);
                $this->addReference('thematic_'.$item['code'], $thematic);
            }

            $thematic->setTitle($item['title']);
            $manager->persist($thematic);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MaturityFixtures::class,
        ];
    }
}
