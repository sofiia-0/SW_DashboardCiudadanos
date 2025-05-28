<?php

namespace App\Imports;

use App\Models\Citizen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class CitizensImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    return new Citizen([
        'first_name' => $row['first_name'],
        'last_name'  => $row['last_name'],
        'birth_date' => $this->transformDate($row['birth_date']),
        'city_id'    => $row['city_id'],
        'address'    => $row['address'],
        'phone'      => $row['phone'],
    ]);
}

private function transformDate($value)
{
    try {
        // Si es una fecha en formato Excel numérico
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
        }
        // Si ya viene en formato string (YYYY-MM-DD)
        return Carbon::parse($value);
    } catch (\Exception $e) {
        return null; // o puedes lanzar un error personalizado
    }
}
}
