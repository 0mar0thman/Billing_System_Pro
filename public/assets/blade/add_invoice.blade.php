<script>
    // Initialize date pickers
    flatpickr("#invoice_Date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        defaultDate: "{{ date('Y-m-d') }}"
    });

    flatpickr("#Due_date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        defaultDate: "{{ date('Y-m-d', strtotime('+3 months')) }}"
    });

    // File upload display
    document.getElementById('pic').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'لم يتم اختيار ملف';
        document.getElementById('file-name').textContent = `الملف المختار: ${fileName}`;
    });

    // تحميل البيانات من الـ backend كـ JSON
    const sectionProducts = @json($sections);

    $('#section_id').on('change', function() {
        const sectionId = $(this).val();
        const $productSelect = $('#product_id');

        // تفريغ العملاء السابقين
        $productSelect.empty();
        $productSelect.append('<option selected disabled>جاري تحميل العملاء...</option>');

        // إيجاد القسم المختار
        const selectedSection = sectionProducts.find(section => section.id == sectionId);

        // إضافة العملاء للقائمة
        if (selectedSection && selectedSection.product.length > 0) {
            $productSelect.empty();
            $productSelect.append('<option selected disabled>اختر العميل</option>');

            selectedSection.product.forEach(product => {
                $productSelect.append(
                    `<option value="${product.id}"
                 data-product-name="${product.product_name}"
                 data-email="${product.email || ''}"
                 data-address="${product.address || ''}"
                 data-phone="${product.phone || ''}">
                 ${product.product_name}
                 </option>`
                );
            });

            // Update hidden fields when product is selected
            $productSelect.on('change', function() {
                const selectedOption = $(this).find('option:selected');
                $('#product_name').val(selectedOption.data('product-name'));
                $('#email').val(selectedOption.data('email'));
                $('#address').val(selectedOption.data('address'));
                $('#phone').val(selectedOption.data('phone'));
            });
        } else {
            $productSelect.empty();
            $productSelect.append('<option selected disabled>لا يوجد عملاء لهذا البنك</option>');
        }
    });

    function calculateCommissionAndVAT() {
        var amountCollection = parseFloat($('#Amount_collection').val()) || 0;
        var amountCollectionValue = parseFloat($('#Amount_collection_value').val()) || 0;
        var amountCommissionRate = parseFloat('{{ $SittingsInvoices->Amount_Commission ?? 0 }}');
        var discountRate = parseFloat('{{ $SittingsInvoices->Discount_Commission ?? 0 }}');
        var rateVAT = parseFloat($('#Rate_VAT').val()) || 0;

        // حساب العمولة الأساسية
        var commissionAmount = (amountCollection * amountCommissionRate) / 100;

        var discountAmount = 0; // الافتراضي مفيش خصم

        // لو التحصيل أقل من الهدف نطبق الخصم
        if (amountCollection < amountCollectionValue) {
            discountAmount = (commissionAmount * discountRate) / 100;
        }

        // حساب العمولة بعد الخصم
        var discountedCommission = commissionAmount - discountAmount;

        // حساب قيمة الضريبة على العمولة بعد الخصم
        var vatValue = (discountedCommission * rateVAT) / 100;

        // حساب الإجمالي النهائي (العمولة بعد الخصم + الضريبة)
        var total = discountedCommission - vatValue;

        // عرض النتائج في الحقول
        $('#Amount_Commission').val(discountedCommission.toFixed(2));
        $('#Discount').val(discountAmount.toFixed(2));
        $('#Value_VAT').val(vatValue.toFixed(2));
        $('#Total').val(total.toFixed(2));
    }

    // Initialize select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'اختر من القائمة',
            allowClear: true
        });
    });
</script>
