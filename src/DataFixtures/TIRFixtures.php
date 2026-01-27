<?php

namespace App\DataFixtures;

use App\Entity\TIR;
use App\Repository\MaturityRepository;
use App\Repository\PARepository;
use App\Repository\TIRRepository;
use Bnine\FilesBundle\Service\FileService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TIRFixtures extends Fixture
{
    public function __construct(
        private MaturityRepository $maturityRepository,
        private PARepository $paRepository,
        private TIRRepository $tirRepository,
        private FileService $fileService,
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

            ['pa' => 'PA08', 'maturity' => '002', 'code' => 'TIR21', 'title' => 'Intégrer des clauses sociales et environnementales dans les appels d\'offre et contrats en lien avec les services numériques (matériels, logiciels, consommables...)'],
            ['pa' => 'PA08', 'maturity' => '002', 'code' => 'TIR22', 'title' => 'Choisir du matériel labélisé, certifié ou disposant d\'un critère de performance reconnu'],
            ['pa' => 'PA08', 'maturity' => '002', 'code' => 'TIR23', 'title' => 'Impliquer ses fournisseurs en communiquant avec eux sur la démarche'],

            ['pa' => 'PA09', 'maturity' => '002', 'code' => 'TIR24', 'title' => 'Inventorier les équipements numériques utilisés par l\'organisation et les affecter en fonction des besoins'],
            ['pa' => 'PA09', 'maturity' => '002', 'code' => 'TIR25', 'title' => 'Établir des paramétrages permettant de limiter les impacts liés à l\'utilisation des équipements'],
            ['pa' => 'PA09', 'maturity' => '002', 'code' => 'TIR26', 'title' => 'Responsabiliser les utilisateurs à la mise en œuvre des écogestes et des bonnes pratiques'],
            ['pa' => 'PA09', 'maturity' => '002', 'code' => 'TIR27', 'title' => 'Prolonger la durée de vie des équipements par un entretien régulier, en privilégiant la réutilisation du matériel ou sa réparation'],
            ['pa' => 'PA09', 'maturity' => '002', 'code' => 'TIR28', 'title' => 'Récupérer les équipements inutilisés ou hors d\'usage pour les orienter vers des filières de valorisation, en favorisant le don ou le reconditionnement'],

            ['pa' => 'PA10', 'maturity' => '002', 'code' => 'TIR29', 'title' => 'Inventorier les logiciels et applications utilisés par l\'organisation et étudier les besoins fonctionnels et techniques avant d\'installer ou souscrire un nouveau service'],
            ['pa' => 'PA10', 'maturity' => '002', 'code' => 'TIR30', 'title' => 'Intégrer les principes de la conception responsable dès l\'expression de besoin et tout au long du cycle de vie'],
            ['pa' => 'PA10', 'maturity' => '002', 'code' => 'TIR31', 'title' => 'Établir des paramétrages permettant de limiter les impacts liés à l\'utilisation des logiciels et applications'],
            ['pa' => 'PA10', 'maturity' => '002', 'code' => 'TIR32', 'title' => 'Responsabiliser les utilisateurs à la mise en œuvre des écogestes et des bonnes pratiques'],
            ['pa' => 'PA10', 'maturity' => '002', 'code' => 'TIR33', 'title' => 'Désinstaller les logiciels et applications non utilisés'],

            ['pa' => 'PA11', 'maturity' => '002', 'code' => 'TIR34', 'title' => 'Concevoir et faire vivre les infrastructures en fonction des besoins et en optimisant les ressources utilisées'],
            ['pa' => 'PA11', 'maturity' => '002', 'code' => 'TIR35', 'title' => 'Recourir à un centre de données engagé sur la mise en œuvre de bonnes pratiques et assurer un suivi régulier de ses performances NR'],
            ['pa' => 'PA11', 'maturity' => '002', 'code' => 'TIR36', 'title' => 'Définir une configuration permettant de limiter les impacts liés au fonctionnement du centre de données (consommations, refroidissement...)'],

            ['pa' => 'PA12', 'maturity' => '002', 'code' => 'TIR37', 'title' => 'Collecter uniquement les données nécessaires lors de la création d\'un nouveau service numérique'],
            ['pa' => 'PA12', 'maturity' => '002', 'code' => 'TIR38', 'title' => 'Informer les utilisateurs de l\'usage qui l\'en sera fait de leurs données et répondre à leurs sollicitations sur le sujet'],
            ['pa' => 'PA12', 'maturity' => '002', 'code' => 'TIR39', 'title' => 'Sécuriser le stockage des données et assurer leur archivage ou leur suppression selon des conditions fixées en interne'],

            ['pa' => 'PA13', 'maturity' => '002', 'code' => 'TIR40', 'title' => 'Soutenir l\'essor de services numériques transparents et pérennes par le recours et la contribution à des solutions open data, open source, open standard'],
            ['pa' => 'PA13', 'maturity' => '002', 'code' => 'TIR41', 'title' => 'Encourager ses collaborateurs à contribuer à un projet d\'intérêt général en lien avec le NR'],

            ['pa' => 'PA14', 'maturity' => '002', 'code' => 'TIR42', 'title' => 'Recourir au numérique pour apporter des solutions à impact positif pour l\'homme ou pour l\'environnement (IT for green, IT for good...)'],

            ['pa' => 'PA17', 'maturity' => '002', 'code' => 'TIR43', 'title' => 'Intégrer la conception responsable dans le diagnostic des besoins et d\'analyse des usages'],
            ['pa' => 'PA17', 'maturity' => '002', 'code' => 'TIR44', 'title' => 'Présenter la démarche NR de l\'organisation dans les propositions commerciales (engagements, actions menées, chiffres-clefs...)'],
            ['pa' => 'PA17', 'maturity' => '002', 'code' => 'TIR45', 'title' => 'Proposer des solutions adaptées et argumentées permettant au client d\'intégrer le NR comme critère de choix'],

            ['pa' => 'PA18', 'maturity' => '002', 'code' => 'TIR46', 'title' => 'Recourir à la conception responsable durant la définition et la mise en œuvre des solutions'],
            ['pa' => 'PA18', 'maturity' => '002', 'code' => 'TIR47', 'title' => 'Mettre en œuvre l\'accessibilité numérique dans ses prestations'],
            ['pa' => 'PA18', 'maturity' => '002', 'code' => 'TIR48', 'title' => 'Faire appel à des méthodes ou des produits facilitant la transparence et la collaboration'],
            ['pa' => 'PA18', 'maturity' => '002', 'code' => 'TIR49', 'title' => 'Prise en compte du cycle de vie des produits installés chez les clients'],

            ['pa' => 'PA19', 'maturity' => '002', 'code' => 'TIR50', 'title' => 'Intégrer le NR aux retours d\'expériences afin d\'évaluer les performances du projet et améliorer les solutions et produits'],
            ['pa' => 'PA19', 'maturity' => '002', 'code' => 'TIR51', 'title' => 'Sensibiliser le support client afin de proposer des réponses orientées NR'],
            ['pa' => 'PA19', 'maturity' => '002', 'code' => 'TIR52', 'title' => 'Favoriser l\'émergence de retours clients sur l\'approche NR mise en œuvre par l\'organisation'],
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
                $this->fileService->init('evidence', $tir->getId());
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
