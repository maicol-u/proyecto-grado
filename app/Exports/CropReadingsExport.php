<?php

namespace App\Exports;

use App\Models\Crop;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CropReadingsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        private readonly Crop $crop,
    ) {
    }

    public function query(): Builder
    {
        return DB::table('readings')
            ->join('sensors', 'sensors.id', '=', 'readings.sensor_id')
            ->where('sensors.crop_id', $this->crop->id)
            ->orderBy('readings.recorded_at', 'desc')
            ->select([
                'sensors.id as sensor_id',
                'sensors.name as sensor_name',
                'sensors.model as sensor_model',
                'sensors.unit as sensor_unit',
                'readings.value',
                'readings.recorded_at',
            ]);
    }

    public function headings(): array
    {
        return [
            'Invernadero',
            'Sensor ID',
            'Sensor',
            'Modelo',
            'Unidad',
            'Valor',
            'Fecha de lectura',
        ];
    }

    public function map($reading): array
    {
        return [
            $this->crop->name,
            $reading->sensor_id,
            $reading->sensor_name,
            $reading->sensor_model,
            $reading->sensor_unit ?? '%',
            $reading->value,
            $reading->recorded_at,
        ];
    }
}