<?php

namespace App\Services;

use App\Models\Serie;

class SeriesService
{
    public static function increment($id): void
    {
        // Aumentar el número de serie
        $serie = Serie::query()->findOrFail($id);
        $serie->serie = $serie->serie + 1;
        $serie->save();
    }

}
