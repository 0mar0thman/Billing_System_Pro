<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء أو تحديث المستخدم المسؤول
        $user = User::updateOrCreate(
            ['email' => 'omar@gmail.com'],
            [
                'name' => 'Omar Mohamed',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
                'status' => 'مفعل',
                'roles_name' => ['owner'],
                'is_admin' => true, // سيتم استخدامها الآن بعد إضافة العمود
            ]
        );

        // إنشاء أو استرجاع دور المالك (owner)
        $role = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web'
        ]);

        // تعيين جميع الصلاحيات للدور
        $permissions = Permission::pluck('id')->all();
        $role->syncPermissions($permissions);

        // تعيين الدور للمستخدم
        $user->assignRole($role);

        // مسح كاش الصلاحيات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('تم إنشاء المستخدم المسؤول بنجاح!');
        $this->command->info('البريد الإلكتروني: omar@gmail.com');
        $this->command->info('كلمة المرور: 11111111');
    }
}
