// Initialize date pickers
flatpickr("#invoice_Date", {
    locale: "ar",
    dateFormat: "Y-m-d",
    defaultDate: "{{ date('Y-m-d') }}",
});

flatpickr("#Due_date", {
    locale: "ar",
    dateFormat: "Y-m-d",
    defaultDate: "{{ date('Y-m-d', strtotime('+3 months')) }}",
});

// File upload display
document.getElementById("pic").addEventListener("change", function (e) {
    const fileName = e.target.files[0]
        ? e.target.files[0].name
        : "لم يتم اختيار ملف";
    document.getElementById(
        "file-name"
    ).textContent = `الملف المختار: ${fileName}`;
});



$(document).ready(function() {
    // تحميل البيانات من المتغير العام
    const sectionProducts = window.sectionProducts;

    // تهيئة select2
    $('.select2').select2({
        width: '100%',
        dir: 'rtl'
    });

    // حدث تغيير البنك
    $('select[name="section_id"]').on('change', function() {
        var sectionId = $(this).val();
        var productSelect = $('select[name="product_id"]');

        if (sectionId) {
            // إعداد حالة التحميل
            productSelect.empty();
            productSelect.append('<option selected disabled>جارٍ تحميل العملاء...</option>');
            productSelect.prop('disabled', true);
            productSelect.select2({
                width: '100%',
                dir: 'rtl'
            });

            // محاكاة تأخير التحميل
            setTimeout(function() {
                productSelect.empty();
                productSelect.prop('disabled', false);

                // البحث عن البنك المحدد
                const selectedSection = sectionProducts.find(section => section.id == sectionId);

                if (!selectedSection || !selectedSection.products || selectedSection.products.length === 0) {
                    productSelect.append(
                        '<option selected disabled>لا يوجد عملاء لهذا البنك</option>'
                    );
                } else {
                    productSelect.append(
                        '<option value="" selected disabled>اختر العميل</option>'
                    );

                    // إضافة العملاء مع بياناتهم
                    selectedSection.products.forEach(function(product) {
                        productSelect.append(
                            '<option value="' + product.id + '"' +
                            ' data-product_name="' + (product.product_name || '') + '"' +
                            ' data-email="' + (product.email || '') + '"' +
                            ' data-phone="' + (product.phone || '') + '"' +
                            ' data-address="' + (product.address || '') + '">' +
                            product.product_name +
                            '</option>'
                        );
                    });
                }

                // تحديث select2
                productSelect.select2({
                    width: '100%',
                    dir: 'rtl'
                });

            }, 500);

        } else {
            productSelect.empty();
            productSelect.append('<option selected disabled>اختر البنك أولاً</option>');
            productSelect.select2({
                width: '100%',
                dir: 'rtl'
            });

            // مسح الحقول عند عدم اختيار بنك
            $('#product_name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#address').val('');
        }
    });

    // حدث تغيير العميل
    $('select[name="product_id"]').on('change', function() {
        const selectedOption = $(this).find('option:selected');

        // تحديث الحقول عند اختيار عميل
        $('#product_name').val(selectedOption.data('product_name'));
        $('#email').val(selectedOption.data('email'));
        $('#phone').val(selectedOption.data('phone'));
        $('#address').val(selectedOption.data('address'));
    });
});




// Initialize select2
$(document).ready(function () {
    $(".select2").select2({
        placeholder: "اختر من القائمة",
        allowClear: true,
    });
});

$(document).ready(function () {
    $("form").validate({
        rules: {
            invoice_Date: {
                required: true,
            },
            Due_date: {
                required: true,
                greaterThanOrEqual: "#invoice_Date",
            },
            section_id: {
                required: true,
            },
            product_id: {
                required: true,
            },
            Amount_collection: {
                required: true,
                number: true,
                min: 0,
            },
            Amount_Commission: {
                required: false,
                number: true,
                min: 0
            },
            Discount_Commission: {
                required: false,
                number: true,
                min: 0
            },
            Value_VAT: {
                required: false,
                number: true,
                min: 0
            },
            Total: {
                required: false,
                number: true,
                min: 0
            },
            Rate_VAT: {
                required: true,
                number: true,
                min: 0,
                max: 100,
            },
            Value_Status: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            address: {
                required: true,
                maxlength: 255,
            },
            phone: {
                required: true,
                maxlength: 20,
            },
        },
        messages: {
            invoice_Date: {
                required: "تاريخ الفاتورة مطلوب",
            },
            Due_date: {
                required: "تاريخ الاستحقاق مطلوب",
                greaterThanOrEqual:
                    "تاريخ الاستحقاق يجب أن يكون بعد أو يساوي تاريخ الفاتورة",
            },
            section_id: {
                required: "اختيار القسم مطلوب",
            },
            product_id: {
                required: "اختيار المنتج مطلوب",
            },
            Amount_collection: {
                required: "مبلغ التحصيل مطلوب",
                number: "يجب أن يكون المبلغ رقماً",
                min: "يجب أن يكون المبلغ أكبر من صفر",
            },
            Amount_Commission: {
                number: "يجب أن يكون المبلغ رقماً",
                min: "يجب أن يكون المبلغ أكبر من صفر"
            },
            Discount_Commission: {
                number: "يجب أن تكون القيمة رقماً",
                min: "يجب أن تكون القيمة أكبر من صفر"
            },
            Value_VAT: {
                number: "يجب أن تكون القيمة رقماً",
                min: "يجب أن تكون القيمة أكبر من صفر"
            },
            Total: {
                number: "يجب أن يكون المبلغ رقماً",
                min: "يجب أن يكون المبلغ أكبر من صفر"
            },
            Rate_VAT: {
                required: "نسبة الضريبة مطلوبة",
                number: "يجب أن تكون النسبة رقماً",
                min: "يجب أن تكون النسبة أكبر من صفر",
                max: "يجب أن لا تتجاوز النسبة 100%",
            },

            Value_Status: {
                required: "حالة الدفع مطلوبة",
            },
            email: {
                required: "البريد الإلكتروني مطلوب",
                email: "يجب أن يكون البريد الإلكتروني صالحاً",
            },
            address: {
                required: "العنوان مطلوب",
                maxlength: "يجب أن لا يتجاوز العنوان 255 حرفاً",
            },
            phone: {
                required: "رقم الهاتف مطلوب",
                maxlength: "يجب أن لا يتجاوز رقم الهاتف 20 رقماً",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    // دالة للتحقق من أن التاريخ أكبر من أو يساوي تاريخ آخر
    $.validator.addMethod(
        "greaterThanOrEqual",
        function (value, element, param) {
            var startDate = $(param).val();
            if (!value || !startDate) return true;
            return new Date(value) >= new Date(startDate);
        }
    );
});
