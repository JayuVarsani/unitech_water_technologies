<?php

declare(strict_types=1);

use App\Models\JobCard;
use App\Src\Company\Modules\Auth\ForgotPassword;
use App\Src\Company\Modules\Auth\Login;
use App\Src\Company\Modules\Auth\ResetPassword;
use App\Src\Company\Modules\Category\CategoryTable;
use App\Src\Company\Modules\Category\CreateCategory;
use App\Src\Company\Modules\Category\EditCategory;
use App\Src\Company\Modules\CustomerManagement\Customer\CreateCustomer;
use App\Src\Company\Modules\CustomerManagement\Customer\CustomerTable;
use App\Src\Company\Modules\CustomerManagement\Customer\EditCustomer;
use App\Src\Company\Modules\CustomerManagement\Customer\ViewCustomer;
use App\Src\Company\Modules\CustomerManagement\CustomerGroup\CreateCustomerGroup;
use App\Src\Company\Modules\CustomerManagement\CustomerGroup\CustomerGroupTable;
use App\Src\Company\Modules\CustomerManagement\CustomerGroup\EditCustomerGroup;
use App\Src\Company\Modules\Inquiry\CreateInquiry;
use App\Src\Company\Modules\JobCard\CreateJobCard;
use App\Src\Company\Modules\JobCard\EditJobCard;
use App\Src\Company\Modules\Inquiry\InquiryTable;
use App\Src\Company\Modules\Inquiry\EditInquiry;
use App\Src\Company\Modules\Inquiry\ViewInquiry;
use App\Src\Company\Modules\Order\EditOrder;
use App\Src\Company\Modules\Order\OrderTable;
use App\Src\Company\Modules\JobCard\JobCardTable;
use App\Src\Company\Modules\JobCardMachine\CreateJobCardMachine;
use App\Src\Company\Modules\JobCardMachine\EditJobCardMachine;
use App\Src\Company\Modules\JobCardMachine\JobCardMachineTable;
use App\Src\Company\Modules\Material\CreateMaterial;
use App\Src\Company\Modules\Material\EditMaterial;
use App\Src\Company\Modules\Material\MaterialTable;
use App\Src\Company\Modules\Order\ViewOrder;
use App\Src\Company\Modules\OrderJob\OrderJobTable;
use App\Src\Company\Modules\Product\CreateProduct;
use App\Src\Company\Modules\Product\EditProduct;
use App\Src\Company\Modules\Product\ProductTable;
use App\Src\Company\Modules\Delivery\CreateDelivery;
use App\Src\Company\Modules\Delivery\DeliveryTable;
use App\Src\Company\Modules\Delivery\EditDelivery;
use App\Src\Company\Modules\Delivery\ViewDelivery;
use App\Src\Company\Modules\Visit\CreateVisit;
use App\Src\Company\Modules\Visit\EditVisit;
use App\Src\Company\Modules\Visit\ViewVisit;
use App\Src\Company\Modules\Visit\VisitTable;
use App\Src\Company\Modules\Profile\ChangePassword;
use App\Src\Company\Modules\Profile\Dashboard;
use App\Src\Company\Modules\Profile\Logout;
use App\Src\Company\Modules\Profile\Profile;
use App\Src\Company\Modules\RolePermission\CreateRolePermission;
use App\Src\Company\Modules\RolePermission\EditRolePermission;
use App\Src\Company\Modules\RolePermission\RolePermissionTable;
use App\Src\Company\Modules\StaffManagement\Staff\CreateStaff;
use App\Src\Company\Modules\StaffManagement\Staff\EditStaff;
use App\Src\Company\Modules\StaffManagement\Staff\StaffTable;
use App\Src\Company\Modules\StaffManagement\StaffRole\CreateStaffRole;
use App\Src\Company\Modules\StaffManagement\StaffRole\EditStaffRole;
use App\Src\Company\Modules\StaffManagement\StaffRole\StaffRoleTable;
use App\Src\Company\Modules\Wastage\WastageTable;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes For Admin Panel
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', Login::class)->name('index')->middleware(['guest:company']);

    Route::group(['as' => 'company.', 'prefix' => 'company'], function () {
        Route::group(['as' => 'auth.', 'middleware' => ['guest:company']], function () {
            Route::get('login', Login::class)->name('login');
            Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
            Route::get('reset-password/{token}', ResetPassword::class)->name('reset-password');
        });

        Route::group(['middleware' => ['auth:company', 'company.active']], function () {
            Route::get('/', Dashboard::class)->name('index');
            Route::get('dashboard', Dashboard::class)->name('dashboard');
            
            Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {

                Route::get('/', Profile::class)->name('index');
                Route::get('change-password', ChangePassword::class)->name('change-password');
            });

            // Category
            Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
                Route::get('/', CategoryTable::class)->name('index');
                Route::get('create', CreateCategory::class)->name('create');
                Route::get('edit/{category}', EditCategory::class)->name('edit');
            });

            // Product
            Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
                Route::get('/', ProductTable::class)->name('index');
                Route::get('create', CreateProduct::class)->name('create');
                Route::get('edit/{product}', EditProduct::class)->name('edit');
            });

            // Visit
            Route::group(['prefix' => 'visit', 'as' => 'visit.'], function () {
                Route::get('/', VisitTable::class)->name('index');
                Route::get('create', CreateVisit::class)->name('create');
                Route::get('view/{visit}', ViewVisit::class)->name('view');
                Route::get('edit/{visit}', EditVisit::class)->name('edit');
            });

            // Delivery
            Route::group(['prefix' => 'delivery', 'as' => 'delivery.'], function () {
                Route::get('/', DeliveryTable::class)->name('index');
                Route::get('create', CreateDelivery::class)->name('create');
                Route::get('view/{delivery}', ViewDelivery::class)->name('view');
                Route::get('edit/{delivery}', EditDelivery::class)->name('edit');
            });

            // Material
            Route::group(['prefix' => 'material', 'as' => 'material.'], function () {
                Route::get('/', MaterialTable::class)->name('index');
                Route::get('create', CreateMaterial::class)->name('create');
                Route::get('edit/{material}', EditMaterial::class)->name('edit');
            });

            // Staff Management | Role
            Route::group(['prefix' => 'staff-management/staff-role', 'as' => 'staff-management.staff-role.'], function () {
                Route::get('/', StaffRoleTable::class)->name('index');
                Route::get('create', CreateStaffRole::class)->name('create');
                Route::get('edit/{staffRole}', EditStaffRole::class)->name('edit');
            });

            // Staff Management | Staff
            Route::group(['prefix' => 'staff-management/staff', 'as' => 'staff-management.staff.'], function () {
                Route::get('/', StaffTable::class)->name('index');
                Route::get('create', CreateStaff::class)->name('create');
                Route::get('edit/{staff}', EditStaff::class)->name('edit');
            });

            // Staff Management | Role and Permission
            Route::middleware(['auth', 'checkUserType:staff'])->group(function () {
                Route::group(['prefix' => 'role-permission', 'as' => 'staff-management.role-permission.'], function () {
                    Route::get('/', RolePermissionTable::class)->name('index');
                    Route::get('create', CreateRolePermission::class)->name('create');
                    Route::get('{id}/edit', EditRolePermission::class)->name('edit');
                });
            });

            // Customer Management| Customer Group
            Route::group(['prefix' => 'customer-management/customer-group', 'as' => 'customer-management.customer-group.'], function () {
                Route::get('/', CustomerGroupTable::class)->name('index');
                Route::get('create', CreateCustomerGroup::class)->name('create');
                Route::get('edit/{customergroup}', EditCustomerGroup::class)->name('edit');
            });

            // Customer Management | Customer
            Route::group(['prefix' => 'customer-management/customer', 'as' => 'customer-management.customer.'], function () {
                Route::get('/', CustomerTable::class)->name('index');
                Route::get('create', CreateCustomer::class)->name('create');
                Route::get('edit/{customer}', EditCustomer::class)->name('edit');
                Route::get('view/{customer}', ViewCustomer::class)->name('view');
            });

            // JobCard
            Route::group(['prefix' => 'jobcard', 'as' => 'jobcard.'], function () {

                Route::any('/', JobCardTable::class)->name('index');
                Route::get('create', CreateJobCard::class)->name('create');
                Route::get('edit/{jobcard}', EditJobCard::class)->name('edit');
            });

            // JobCard
            Route::group(['prefix' => 'orderjob', 'as' => 'orderjob.'], function () {
                Route::any('/', OrderJobTable::class)->name('index');
            });

            // Inquiry
            Route::group(['prefix' => 'inquiry', 'as' => 'inquiry.'], function () {
                Route::any('/', InquiryTable::class)->name('index');
                Route::get('create', CreateInquiry::class)->name('create');
                Route::get('edit/{inquiry}', EditInquiry::class)->name('edit');
                Route::get('view/{inquiry}', ViewInquiry::class)->name('view');
            });

            // Order
            Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
                Route::any('/', OrderTable::class)->name('index');
                Route::get('edit/{order}', EditOrder::class)->name('edit');
                Route::get('view/{order}', ViewOrder::class)->name('view');
            });
            Route::group(['prefix' => 'jobcard-machine', 'as' => 'jobcard-machine.'], function () {
                Route::get('/', JobCardMachineTable::class)->name('index');
                Route::get('create', CreateJobCardMachine::class)->name('create');
                Route::get('edit/{jobcard-machine}', EditJobCardMachine::class)->name('edit');
            });

            //wastage
            Route::group(['prefix' => 'wastage', 'as' => 'wastage.'], function () {
                Route::get('/', WastageTable::class)->name('index');
            });
        });
    });
});
