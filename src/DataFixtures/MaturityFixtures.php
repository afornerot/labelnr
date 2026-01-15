<?php

namespace App\DataFixtures;

use App\Entity\Maturity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MaturityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['code' => 'MATU-1', 'title' => 'Initial'],
            ['code' => 'MATU-2', 'title' => 'Reproductible'],
            ['code' => 'MATU-3', 'title' => 'Défini'],
            ['code' => 'MATU-4', 'title' => 'Géré'],
            ['code' => 'MATU-5', 'title' => 'Optimisé'],
        ];

        foreach ($data as $item) {
            $maturity = new Maturity();
            $maturity->setCode($item['code']);
            $maturity->setTitle($item['title']);
            $manager->persist($maturity);

            $this->addReference('maturity_'.$item['code'], $maturity);
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
