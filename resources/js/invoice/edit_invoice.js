/**
 * Invoice Management Script
 * Handles invoice calculations, section-product AJAX, and datepicker initialization
 */

class InvoiceCalculator {
    static init() {
        this.setupDatePicker();
        this.setupSectionProductAjax();
        this.setupCalculationEvents();
        this.calculateOnLoad();
    }

    static setupDatePicker() {
        $(".fc-datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });
    }

    static setupSectionProductAjax() {
        $(document).on("change", 'select[name="Section"]', function () {
            const sectionId = $(this).val();
            if (sectionId) {
                $.ajax({
                    url: `${BASE_URL}/section/${sectionId}`,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        const productSelect = $('select[name="product"]');
                        productSelect.empty();

                        if (data.length > 0) {
                            $.each(data, function (key, value) {
                                productSelect.append(
                                    `<option value="${value}">${value}</option>`
                                );
                            });
                        } else {
                            productSelect.append(
                                '<option value="">لا يوجد منتجات</option>'
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching products:", error);
                        showToast("error", "خطأ", "فشل في تحميل المنتجات");
                    },
                });
            }
        });
    }

    static setupCalculationEvents() {
        // Calculate when amount or VAT rate changes
        $(document).on("input", "#Amount_collection, #Rate_VAT", function () {
            InvoiceCalculator.calculateCommissionAndVAT();
        });
    }

    static calculateCommissionAndVAT() {
        const Amount_collection =
            parseFloat($("#Amount_collection").val()) || 0;
        const Rate_VAT = parseFloat($("#Rate_VAT").val()) || 0;

        // Commission (20% of collection amount)
        const Amount_Commission = Amount_collection * 0.2;

        // Discount (10% of commission if collection < 500)
        const Discount = Amount_collection < 500 ? Amount_Commission * 0.1 : 0;

        // Commission after discount
        const CommissionAfterDiscount = Amount_Commission - Discount;

        // VAT value
        const Value_VAT = CommissionAfterDiscount * (Rate_VAT / 100);

        // Total amount
        const Total = CommissionAfterDiscount - Value_VAT;

        // Update fields with formatted values
        $("#Amount_Commission").val(Amount_Commission.toFixed(2));
        $("#Discount").val(Discount.toFixed(2));
        $("#Value_VAT").val(Value_VAT.toFixed(2));
        $("#Total").val(Total.toFixed(2));
    }

    static calculateOnLoad() {
        $(window).on("load", function () {
            InvoiceCalculator.calculateCommissionAndVAT();
        });
    }
}

// Helper function to show toast notifications
function showToast(type, title, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    Toast.fire({
        icon: type,
        title: title,
        text: message,
    });
}

// Initialize when document is ready
$(document).ready(function () {
    // Set base URL from Laravel
    window.BASE_URL = "{{ URL::to('/') }}";

    // Initialize invoice calculator
    InvoiceCalculator.init();

    // Add any additional initialization here
});
