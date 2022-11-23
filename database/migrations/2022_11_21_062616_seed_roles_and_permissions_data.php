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
        Permission::create(['name' => 'accounts.index']);
        Permission::create(['name' => 'accounts.store']);
        Permission::create(['name' => 'accounts.show']);
        Permission::create(['name' => 'accounts.update']);
        Permission::create(['name' => 'accounts.destroy']);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo(['accounts.index', 'accounts.store', 'accounts.show', 'accounts.update', 'accounts.destroy']);

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo(['accounts.index', 'accounts.show']);
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
