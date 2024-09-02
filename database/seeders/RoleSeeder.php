<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'boutiquier', 'client'];

        foreach ($roles as $role) {
            $created = Role::firstOrCreate(['name' => $role]);
            Log::info("Role '{$role}' " . ($created->wasRecentlyCreated ? 'created' : 'already exists'));
        }
    }
}
