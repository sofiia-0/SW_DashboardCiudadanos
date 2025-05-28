<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CitiesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new City([
            'name'        => $row['name'],         // Nombre de la ciudad, debe coincidir con el encabezado en el XLS/CSV
            'description' => $row['description'],  // Descripción, idem
        ]);
    }
}
