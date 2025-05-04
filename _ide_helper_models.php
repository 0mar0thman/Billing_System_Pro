<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $invoice_Date
 * @property string|null $Due_date
 * @property string $product_name
 * @property string $section_name
 * @property int $product_id
 * @property int $section_id
 * @property string|null $Amount_collection
 * @property string $Amount_Commission
 * @property string $Discount_Commission
 * @property string $Value_VAT
 * @property string|null $Rate_VAT
 * @property string $Total
 * @property string $Status
 * @property int $Value_Status
 * @property string|null $note
 * @property string|null $Payment_Date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InvoiceAttachments> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Product $products
 * @property-read \App\Models\Section $sections
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice paid()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice partial()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice unpaid()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAmountCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAmountCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDiscountCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereRateVAT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereSectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereValueStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereValueVAT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice withoutTrashed()
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $file_name
 * @property string $Created_by
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice $invoices
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceAttachments whereUpdatedAt($value)
 */
	class InvoiceAttachments extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $product_name
 * @property string|null $section_name
 * @property string|null $address
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $invoice_id
 * @property int|null $product_id
 * @property int|null $section_id
 * @property string $Status
 * @property int $Value_Status
 * @property string|null $Payment_Date
 * @property string|null $note
 * @property string $user
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice|null $invoices
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereSectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetails whereValueStatus($value)
 */
	class InvoiceDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $section_id
 * @property string $Amount_collection
 * @property string $Amount_Commission
 * @property string $Discount_Commission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereAmountCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereAmountCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereDiscountCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSittings whereUpdatedAt($value)
 */
	class InvoiceSittings extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $product_name
 * @property string|null $description
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $email
 * @property int $section_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \App\Models\Section $sections
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $section_name
 * @property string|null $description
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereSectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereUpdatedAt($value)
 */
	class Section extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int $is_admin
 * @property mixed $password
 * @property array|null $roles_name
 * @property string $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRolesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

