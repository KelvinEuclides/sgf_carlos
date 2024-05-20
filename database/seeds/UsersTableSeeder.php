<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrPermissions = [
            "manage role",
            "create role",
            "edit role",
            "delete role",
            "manage user",
            "create user",
            "edit user",
            "delete user",
            "manage customer",
            "create customer",
            "edit customer",
            "delete customer",
            "show customer",
            "manage vendor",
            "edit vendor",
            "delete vendor",
            "show vendor",
            "create vendor",
            "manage category",
            "create category",
            "edit category",
            "delete category",
            "manage tax",
            "create tax",
            "edit tax",
            "delete tax",
            "manage unit",
            "create unit",
            "edit unit",
            "delete unit",
            "manage item",
            "create item",
            "edit item",
            "delete item",
            "manage estimation",
            "create estimation",
            "edit estimation",
            "delete estimation",
            "show estimation",
            "send estimation",
            "manage invoice",
            "create invoice",
            "edit invoice",
            "delete invoice",
            "show invoice",
            "send invoice",
            "manage bill",
            "create bill",
            "edit bill",
            "delete bill",
            "show bill",
            "manage banking",
            "create banking",
            "edit banking",
            "delete banking",
            "manage transfer",
            "create transfer",
            "edit transfer",
            "delete transfer",
            "manage income",
            "create income",
            "edit income",
            "delete income",
            "manage expense",
            "create expense",
            "edit expense",
            "delete expense",
            "manage subscription",
            "buy subscription",
            "create subscription",
            "edit subscription",
            "manage voucher",
            "create voucher",
            "edit voucher",
            "delete voucher",
            "show voucher",
            "create payment invoice",
            "delete payment invoice",
            "send bill",
            "create payment bill",
            "delete payment bill",
            "manage summary",
        ];
        foreach($arrPermissions as $ap)
        {
            Permission::create(['name' => $ap]);
        }


        // Super admin
        $superAdminRole        = Role::create(
            [
                'name' => 'super admin',
                'created_by' => 0,
            ]
        );
        $superAdminPermissions = [
            "manage subscription",
            "create subscription",
            "edit subscription",
            "manage voucher",
            "create voucher",
            "edit voucher",
            "delete voucher",
            "show voucher",
        ];
        foreach($superAdminPermissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $superAdminRole->givePermissionTo($permission);
        }
        $superAdmin = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'type' => 'super admin',
                'lang' => 'en',
                'avatar' => 'avatar.png',
                'created_by' => 0,
            ]
        );
        $superAdmin->assignRole($superAdminRole);


        // company

        $companyRole        = Role::create(
            [
                'name' => 'company',
                'created_by' => $superAdmin->id,
            ]
        );
        $companyPermissions = [
            "manage role",
            "create role",
            "edit role",
            "delete role",
            "manage user",
            "create user",
            "edit user",
            "delete user",
            "manage customer",
            "create customer",
            "edit customer",
            "delete customer",
            "show customer",
            "manage vendor",
            "edit vendor",
            "delete vendor",
            "show vendor",
            "create vendor",
            "manage category",
            "create category",
            "edit category",
            "delete category",
            "manage tax",
            "create tax",
            "edit tax",
            "delete tax",
            "manage unit",
            "create unit",
            "edit unit",
            "delete unit",
            "manage item",
            "create item",
            "edit item",
            "delete item",
            "manage estimation",
            "create estimation",
            "edit estimation",
            "delete estimation",
            "show estimation",
            "send estimation",
            "manage invoice",
            "create invoice",
            "edit invoice",
            "delete invoice",
            "show invoice",
            "send invoice",
            "manage bill",
            "create bill",
            "edit bill",
            "delete bill",
            "show bill",
            "manage banking",
            "create banking",
            "edit banking",
            "delete banking",
            "manage transfer",
            "create transfer",
            "edit transfer",
            "delete transfer",
            "manage income",
            "create income",
            "edit income",
            "delete income",
            "manage expense",
            "create expense",
            "edit expense",
            "delete expense",
            "manage subscription",
            "buy subscription",
            "create payment invoice",
            "delete payment invoice",
            "send bill",
            "create payment bill",
            "delete payment bill",
            "manage summary",
        ];

        foreach($companyPermissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $companyRole->givePermissionTo($permission);
        }
        $company = User::create(
            [
                'name' => 'Company',
                'email' => 'company@example.com',
                'password' => Hash::make('password'),
                'address' => 'Very complex address 1',
                'nuit' => random_int(9, 9),
                'type' => 'company',
                'lang' => 'en',
                'avatar' => 'avatar.png',
                'subscription' => 1,
                'created_by' => $superAdmin->id,
            ]
        );
        $company->assignRole($companyRole);


        // customer
        $customerRole = Role::create(
            [
                'name' => 'customer',
                'created_by' => $company->id,
            ]
        );

        $customer = User::create(
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'address' => 'Very complex address 1',
                'nuit' => random_int(9, 9),
                'type' => 'customer',
                'lang' => 'en',
                'avatar' => 'avatar.png',
                'created_by' => $company->id,
            ]
        );
        \App\Customer::create(
            [
                'user_id' => $customer->id,
                'customer_id' => 1,
                'balance' => 0,
                'created_by' => $company->id,
            ]
        );

        $customer->assignRole($customerRole);


        // vendor
        $vendorRole = Role::create(
            [
                'name' => 'vendor',
                'created_by' => $company->id,
            ]
        );

        $vendor = User::create(
            [
                'name' => 'Vendor',
                'email' => 'vendor@example.com',
                'password' => Hash::make('password'),
                'address' => 'Very complex address 1',
                'nuit' => random_int(9, 9),
                'type' => 'vendor',
                'lang' => 'en',
                'avatar' => 'avatar.png',
                'created_by' => $company->id,
            ]
        );

        \App\Vendor::create(
            [
                'user_id' => $vendor->id,
                'vendor_id' => 1,
                'balance' => 0,
                'created_by' => $company->id,
            ]
        );

        $vendor->assignRole($vendorRole);


        // manager
        $managerRole       = Role::create(
            [
                'name' => 'manager',
                'created_by' => $company->id,
            ]
        );
        $managerPermission = [
            "manage role",
            "create role",
            "edit role",
            "delete role",
            "manage user",
            "create user",
            "edit user",
            "delete user",
            "manage customer",
            "create customer",
            "edit customer",
            "delete customer",
            "show customer",
            "manage vendor",
            "edit vendor",
            "delete vendor",
            "show vendor",
            "create vendor",
            "manage item",
            "create item",
            "edit item",
            "delete item",
            "manage estimation",
            "create estimation",
            "edit estimation",
            "delete estimation",
            "show estimation",
            "send estimation",
            "manage invoice",
            "create invoice",
            "edit invoice",
            "delete invoice",
            "show invoice",
            "send invoice",
            "manage bill",
            "create bill",
            "edit bill",
            "delete bill",
            "show bill",
            "manage banking",
            "create banking",
            "edit banking",
            "delete banking",
            "manage transfer",
            "create transfer",
            "edit transfer",
            "delete transfer",
            "manage income",
            "create income",
            "edit income",
            "delete income",
            "manage expense",
            "create expense",
            "edit expense",
            "delete expense",
            "create payment invoice",
            "delete payment invoice",
            "send bill",
            "create payment bill",
            "delete payment bill",
            "manage summary",
        ];

        foreach($managerPermission as $ap)
        {
            $permission = Permission::findByName($ap);
            $managerRole->givePermissionTo($permission);
        }

        $manager = User::create(
            [
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'address' => 'Very complex address 1',
                'nuit' => random_int(9, 9),
                'type' => 'manager',
                'lang' => 'en',
                'avatar' => 'avatar.png',
                'created_by' => $company->id,
            ]
        );
        $manager->assignRole($managerRole);

        \App\BankAccount::create(
            [
                'holder_name' => 'Cash',
                'bank_name' => '',
                'account_number' => '-',
                'opening_balance' => '0.00',
                'contact_number' => '-',
                'bank_address' => '-',
                'created_by' => $company->id,
            ]
        );
    }
}
