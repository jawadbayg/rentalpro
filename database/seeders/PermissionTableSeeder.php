<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'fleet-list',
           'fleet-create',
           'fleet-edit',
           'fleet-delete',
           'booking-list',
           'booking-create',
           'booking-edit',
           'booking-delete',
           'payment-list',
            'payment-create',
            'payment-edit',
            'payment-delete',
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',
        ];
        
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
