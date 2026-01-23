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
            ['code' => '001', 'title' => 'Exclu', 'value' => null],
            ['code' => '002', 'title' => 'Important', 'value' => 500],
            ['code' => '003', 'title' => 'Essentiel', 'value' => 1000],
        ];

        foreach ($data as $item) {
            $materiality = $this->materialityRepository->findOneBy(['code' => $item['code']]);
            if (!$materiality) {
                $materiality = new Materiality();
                $materiality->setCode($item['code']);
                $this->addReference('materiality_'.$item['code'], $materiality);
            }

            $materiality->setTitle($item['title']);
            $materiality->setValue($item['value']);
            $manager->persist($materiality);
        }

        $manager->flush();
    }
}
