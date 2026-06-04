<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Piece', 'Sq.Ft'];

        $existingUnits = Unit::all();

        $existingUnitNames = $existingUnits->pluck('name')->toArray();

        foreach ($names as $name) {
            if (in_array($name, $existingUnitNames)) {
                Unit::where('name', $name)->update(['updated_at' => now()]);
            } else {
                Unit::create(['name' => $name]);
            }
        }

        $unitsToDelete = $existingUnits->reject(function ($unit) use ($names) {
            return in_array($unit->name, $names);
        });

        foreach ($unitsToDelete as $unit) {
            $unit->delete();
        }

    }
}
