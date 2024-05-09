<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "Admin",
            "Promoter",
            "Customer",
        ];
        $permissions = [
            "view_user_list",
            "create_user",
            "update_user",
            "view_user",
            "delete_user",
            "restore_user",
            "force_delete_user",

            "view_venue_list",
            "create_venue",
            "update_venue",
            "view_venue",
            "delete_venue",

            "view_role_list",
            "create_role",
            "update_role",
            "view_role",
            "delete_role",

            'view_event_list',
            "create_event",
            "update_event",
            "view_event",
            "delete_event",

            "manage_attendee",
            "accept_attendee_requests",
            "upcomming_event_list",
            "my_events"
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(["name" => $role]);
        }
        foreach ($permissions as $per) {
            Permission::firstOrCreate(["name" => $per]);
        }
        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->syncPermissions($permissions);

        $promoterRole = Role::where('name', 'Promoter')->first();
        $promoterRole->syncPermissions([
            "create_venue",
            "update_venue",
            "view_venue",
            "delete_venue",

            "create_event",
            "update_event",
            "view_event",
            "delete_event",

            "manage_attendee"
        ]);
        $customerRole = Role::where('name', 'Customer')->first();
        $customerRole->syncPermissions([
            "upcomming_event_list",
            "my_events",
        ]);
        $admin = User::first();
        $promoter = User::find(2);
        $admin->assignRole('Admin');
        $promoter->assignRole('Promoter');
    }
}
