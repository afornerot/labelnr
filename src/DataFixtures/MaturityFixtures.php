<?php

namespace App\DataFixtures;

use App\Entity\Maturity;
use App\Repository\MaturityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MaturityFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private MaturityRepository $maturityRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            ['code' => '001', 'title' => 'NA', 'value' => null],
            ['code' => '002', 'title' => 'Faible', 'value' => 0],
            ['code' => '003', 'title' => 'Partiel', 'value' => 500],
            ['code' => '004', 'title' => 'Raisonnable', 'value' => 1000],
        ];

        foreach ($data as $item) {
            $maturity = $this->maturityRepository->findOneBy(['code' => $item['code']]);
            if (!$maturity) {
                $maturity = new Maturity();
                $maturity->setCode($item['code']);
                $this->addReference('maturity_'.$item['code'], $maturity);
            }

            $maturity->setTitle($item['title']);
            $maturity->setValue($item['value']);
            $manager->persist($maturity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MaterialityFixtures::class,
        ];
    }
}
