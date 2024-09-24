<?php

namespace App\Enum;
Enum State: string
{

    case ACTIVE = 'En cours';
    case INACTIVE = 'Annulé';

    case FINISHED = 'Finis';

    case PENDING = 'En attente';


    public static function toString(self $state): string {
        return $state->value;
    }


}