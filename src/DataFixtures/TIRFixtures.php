<?php

namespace App\DataFixtures;

use App\Entity\TIR;
use App\Repository\MaturityRepository;
use App\Repository\PARepository;
use App\Repository\TIRRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TIRFixtures extends Fixture
{
    public function __construct(
        private MaturityRepository $maturityRepository,
        private PARepository $paRepository,
        private TIRRepository $tirRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            ['pa' => 'PA01', 'maturity' => '002', 'code' => 'TIR01', 'title' => 'Formaliser l\'engagement de la direction'],
            ['pa' => 'PA01', 'maturity' => '002', 'code' => 'TIR02', 'title' => 'Définir la stratégie NR et les moyens associés'],

            ['pa' => 'PA02', 'maturity' => '002', 'code' => 'TIR03', 'title' => 'Identifier les principaux enjeux sociaux et environnementaux du numérique pour l\'organisation'],
            ['pa' => 'PA02', 'maturity' => '002', 'code' => 'TIR04', 'title' => 'Définir des indicateurs de NR pour le pilotage de la politique NR'],
            ['pa' => 'PA02', 'maturity' => '002', 'code' => 'TIR05', 'title' => 'Définir et planifier un plan d’action numérique responsable'],

            ['pa' => 'PA03', 'maturity' => '002', 'code' => 'TIR06', 'title' => 'Identifier et accompagner les porteurs de projet de la démarche NR'],
            ['pa' => 'PA03', 'maturity' => '002', 'code' => 'TIR07', 'title' => 'Évaluer et analyser les indicateurs NR'],

            ['pa' => 'PA04', 'maturity' => '002', 'code' => 'TIR08', 'title' => 'Identifier les parties prenantes avec qui interagir sur le NR et se mettre en lien avec elles'],
            ['pa' => 'PA04', 'maturity' => '002', 'code' => 'TIR09', 'title' => 'Partager des informations générales sur le thème du NR (actualités, chiffres clefs, interviews…)'],
            ['pa' => 'PA04', 'maturity' => '002', 'code' => 'TIR10', 'title' => 'Communiquer en toute transparence sur les engagements et les indicateurs NR de l\'organisation'],
            ['pa' => 'PA04', 'maturity' => '002', 'code' => 'TIR11', 'title' => 'Faire émerger des bonnes pratiques NR émanant des collaborateurs et les partager en interne et en externe'],
            ['pa' => 'PA04', 'maturity' => '002', 'code' => 'TIR12', 'title' => 'Participer ou organiser des événements pour sensibiliser les parties prenantes au NR'],

            ['pa' => 'PA05', 'maturity' => '002', 'code' => 'TIR13', 'title' => 'Permettre aux collaborateurs d\'accéder et d\'utiliser facilement les services informatiques de l\'organisation'],
            ['pa' => 'PA05', 'maturity' => '002', 'code' => 'TIR14', 'title' => 'Favoriser l\'inclusion numérique et l\'employabilité de tous les collaborateurs'],

            ['pa' => 'PA06', 'maturity' => '002', 'code' => 'TIR15', 'title' => 'Intégrer le NR dans le plan de formation de l\'organisation'],
            ['pa' => 'PA06', 'maturity' => '002', 'code' => 'TIR16', 'title' => 'Professionnaliser le numérique responsable dans la gestion des compétences de l\'organisation'],
            ['pa' => 'PA06', 'maturity' => '002', 'code' => 'TIR17', 'title' => 'Valoriser le partage de compétences autour du NR'],
            ['pa' => 'PA06', 'maturity' => '002', 'code' => 'TIR18', 'title' => 'Procéder à une veille NR afin de maintenir à niveau les compétences et identifier les innovations du secteur'],

            ['pa' => 'PA07', 'maturity' => '002', 'code' => 'TIR19', 'title' => 'Intégrer le NR dans les campagnes et supports de communication généraux de l\'organisation'],
            ['pa' => 'PA07', 'maturity' => '002', 'code' => 'TIR20', 'title' => 'Disposer de sites Internet sobres et accessibles'],
        ];

        foreach ($data as $item) {
            $pa = $this->paRepository->findOneBy(['code' => $item['pa']]);
            $maturity = $this->maturityRepository->findOneBy(['code' => $item['maturity']]);
            if ($pa && $maturity) {
                $tir = $this->tirRepository->findOneBy(['code' => $item['code']]);
                if (!$tir) {
                    $tir = new TIR();
                    $tir->setCode($item['code']);
                    $tir->setMaturity($maturity);
                    $this->addReference('maturity_'.$item['code'], $maturity);
                }

                $tir->setTitle($item['title']);
                $tir->setPa($pa);
                $manager->persist($tir);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PAFixtures::class,
        ];
    }
}
