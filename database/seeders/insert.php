<?php

namespace Database\Seeders;

use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Seeder;

class insert extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role::create([
        //     'code' => 'SUPER-ADMIN',
        //     'name' => 'Super admin'
        // ]);

        // Role::create([
        //     'code' => 'USER',
        //     'name' => 'User'
        // ]);

        // Role::create([
        //     'code' => 'GUEST',
        //     'name' => 'Guest',
        //     'level' => 1
        // ]);

        // Role::create([
        //     'code' => 'ADMIN',
        //     'name' => 'Admin',
        //     'level' => 3
        // ]);

        // GroupPermission::create([
        //     'code' => 'ALL',
        //     'name' => 'Toàn quyền hệ thống',
        //     'table_name' => null,
        // ]);

        // Permission::create([
        //         'code' => 'ALL',
        //         'name' => 'Toàn quyền hệ thống',
        //         'group_id' => 2
        //     ]);
        // Permission::create([
        //     'code' => 'VIEW-USER',
        //     'name' => 'Xem người dùng',
        //     'group_id' => 1
        // ]);

        // Permission::create([
        //     'code' => 'CREATE-USER',
        //     'name' => 'Tạo người dùng',
        //     'group_id' => 1
        // ]);

        // Permission::create([
        //     'code' => 'UPDATE-USER',
        //     'name' => 'Cập nhật người dùng',
        //     'group_id' => 1
        // ]);

        // Permission::create([
        //     'code' => 'DELETE-USER',
        //     'name' => 'Xóa người dùng',
        //     'group_id' => 1
        // ]);

        // RolePermission::create([
        //             'role_id' => 2,
        //             'permission_id' => 1,
        //         ]);

        // for($i = 1; $i <= 4; $i++){
        //     RolePermission::create([
        //         'role_id' => 4,
        //         'permission_id' => $i,
        //     ]);
        // }

        User::create([
            ''
        ]);
    }
}
