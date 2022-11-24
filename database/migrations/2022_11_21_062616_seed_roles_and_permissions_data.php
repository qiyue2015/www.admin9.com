<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 需清除缓存，否则会报错
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 先创建权限
        $permissions = [
            'user.index', 'user.store', 'user.show', 'user.update', 'user.destroy', 'user.change-password',
            'role.index', 'role.store', 'role.show', 'role.update', 'role.destroy',
        ];
        collect($permissions)->each(function ($value) {
            Permission::create(['name' => $value]);
        });

        // 创建站长角色
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo($permissions); // 赋予全部权限

        // 创建管理员角色
        $maintainer = Role::create(['name' => 'Maintainer']);
        $permissions = collect($permissions)->filter(function ($value) {
            // 并赋予除删除外的权限
            return !Str::contains($value, '.destroy');
        });
        $maintainer->givePermissionTo($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 需清除缓存，否则会报错
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
};
