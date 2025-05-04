<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // إعادة تعيين IDs لتفادي تضارب المفاتيح
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // إنشاء الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // مجموعات الصلاحيات
        $permissionGroups = [

            'الفواتير' => [
                'الفواتير',
                'قائمة الفواتير', //
                'الفواتير المدفوعة', //
                'الفواتير المدفوعة جزئيا', //
                'الفواتير الغير مدفوعة', //
                'ارشيف الفواتير', //
                'اعدادات الفواتير', //
                'اضافة فاتورة', //
                'حذف الفاتورة', //
                'تعديل الفاتورة', //
                'ارشفة الفاتورة', //
                'طباعةالفاتورة', //
                'اضافة مرفق', // استعلام
                'حذف المرفق', //
                'تصدير EXCEL', //
                'تغير حالة الدفع' //
            ],
            'التقارير' => [
                'التقارير',
                'تقرير الفواتير',
                'تقرير العملاء'
            ],
            'المستخدمين' => [
                'المستخدمين', //
                'قائمة المستخدمين', //
                'اضافة مستخدم', //
                'اضافة مستخدم', //
                'تعديل مستخدم', //
                'حذف مستخدم', //
                // 'اضافة مستخدم'
            ],
            'الصلاحيات' => [
                'الصلاحيات', //
                'صلاحيات المستخدمين', //
                'عرض صلاحية', //
                'اضافة صلاحية', //
                'تعديل صلاحية', //
                'حذف صلاحية', //
                // 'اضافة صلاحية'
            ],
            'الاعدادات' => [
                'الاعدادات', //
                'المنتجات',  //
                'اضافة منتج', //
                'تعديل منتج', //
                'حذف منتج', //
                'الاقسام', //
                'اضافة قسم', //
                'تعديل قسم', //
                'حذف قسم' //
            ],
            'النظام' => [
                'الاشعارات'
            ]

        ];

        // إنشاء وتعيين الصلاحيات
        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permission) {
                $permission = Permission::firstOrCreate([
                    'name' => $permission,
                    'group_name' => $group,
                    'guard_name' => 'web'
                ]);
                $adminRole->givePermissionTo($permission);
            }
        }

        // صلاحيات أساسية للمستخدم العادي
        $basicPermissions = [
            'قائمة الفواتير',
            'الفواتير المدفوعة',
            'الفواتير المدفوعة جزئيا',
            'الفواتير الغير مدفوعة',
            'طباعةالفاتورة'
        ];

        foreach ($basicPermissions as $permission) {
            $userRole->givePermissionTo($permission);
        }
    }
}
