<?php

use App\Models\Role\Permission;
use App\Models\Role\Role;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = json_decode(file_get_contents(public_path('/docs/permissions.json')), true);

        $super = Role::create(['name' => 'Super']);
        $admin = Role::create(['name' => 'Admin']);
        $agent = Role::create(['name' => 'Agent']);

        foreach ($permissions as $p) {
            foreach ($p['childs'] as $c) {
                $created_permission = Permission::create($c);

                $created_permission->assignRole($super);
                $created_permission->assignRole($admin);

                if (in_array($created_permission->slug, [
                    'view_tickets',
                    'view_ticket',
                    'reply_to_ticket',
                    'edit_ticket'
                ])) {
                    $created_permission->assignRole($agent);
                }
            }
        }
    }
}
