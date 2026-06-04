<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::create([
            'name' => 'test company',
            'status' => 1,
            'address' => 'ahmedabad',
        ]);

        $company->company_staff()->create([
            'name' => 'test cmp',
            'email' => 'company@yopmail.com',
            'contact_number' => 1231231230,
            'password' => 'Test@123',
            'status' => 1,
        ]);
    }
}
