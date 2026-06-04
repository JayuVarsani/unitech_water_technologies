<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Moderator;
use App\Utility\Enums\StatusEnum;
use Illuminate\Database\Seeder;

class ModeratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Moderator::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 123456,
            'status' => StatusEnum::Active->value,
            'type' => 'admin',
        ]);
    }
}
