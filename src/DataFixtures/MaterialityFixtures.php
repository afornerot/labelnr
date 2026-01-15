<?php

namespace App\DataFixtures;

use App\Entity\Materiality;
use App\Repository\MaterialityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MaterialityFixtures extends Fixture
{
    public function __construct(
        private MaterialityRepository $materialityRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            ['code' => 'MAT-L', 'title' => 'Faible'],
            ['code' => 'MAT-M', 'title' => 'Moyenne'],
            ['code' => 'MAT-H', 'title' => 'Forte'],
        ];

        foreach ($data as $item) {
            $materiality = $this->materialityRepository->findByCode($item['code']);
            if (!$materiality) {
                $materiality = new Materiality();
                $materiality->setCode($item['code']);
                $manager->persist($materiality);
            }
            $materiality->setTitle($item['title']);

            $this->addReference('materiality_'.$item['code'], $materiality);
        }

        $manager->flush();
    }
}
