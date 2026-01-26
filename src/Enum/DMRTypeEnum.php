<?php

namespace App\Enum;

enum DMRTypeEnum: string
{
    case DOCUMENTATION = 'documentation';
    case MOYEN = 'moyen';
    case RESULTAT = 'resultat';

    // Optionnel : pour l'affichage dans les formulaires Twig
    public function getLabel(): string
    {
        return match ($this) {
            self::DOCUMENTATION => 'Documentation',
            self::MOYEN => 'Moyen',
            self::RESULTAT => 'Résultat',
        };
    }
}
