function calculateCommissionAndVAT() {
    // 1. الحصول على القيم الأساسية
    const amountCollection =
        parseFloat(document.getElementById("Amount_collection").value) || 0;
    const commissionRate =
        parseFloat(
            document.querySelector('input[name="Amount_Commission_value"]')
                .value
        ) || 0;
    const discountRate =
        parseFloat(
            document.querySelector('input[name="Discount_Commission_value"]')
                .value
        ) || 0;
    const vatRate = parseFloat(document.getElementById("Rate_VAT").value) || 0;

    // 2. حساب العمولة الأساسية
    const grossCommission = amountCollection * (commissionRate / 100);

    // 3. حساب الخصم على العمولة
    const discountAmount = grossCommission * (discountRate / 100);

    // 4. حساب صافي العمولة بعد الخصم (الوعاء الضريبي)
    const netCommission = grossCommission - discountAmount;

    // 5. حساب ضريبة القيمة المضافة (على صافي العمولة فقط)
    const vatAmount = netCommission * (vatRate / 100);

    // 6. حساب الإجمالي النهائي (الصافي + الضريبة)
    const totalWithVAT = netCommission + vatAmount;

    // 7. تعبئة النتائج في الحقول
    document.getElementById("Amount_Commission").value =
        grossCommission.toFixed(2);
    document.getElementById("Discount").value = discountAmount.toFixed(2);
    document.getElementById("Value_VAT").value = vatAmount.toFixed(2);
    document.getElementById("Total").value = totalWithVAT.toFixed(2);

    // 8. تحديث العلامات التوضيحية
    updateCalculationLabels(netCommission, vatRate);
}

function updateCalculationLabels(netCommission, vatRate) {
    const vatLabel = `ضريبة (${vatRate}%) على ${netCommission.toFixed(2)}`;
    document.querySelector('label[for="Value_VAT"]').innerHTML = vatLabel;

    const totalLabel = `الإجمالي (${netCommission.toFixed(2)} + ${(
        (netCommission * vatRate) /
        100
    ).toFixed(2)})`;
    document.querySelector('label[for="Total"]').innerHTML = totalLabel;
}

// 3. تهيئة الأحداث
document.addEventListener("DOMContentLoaded", function () {
    // ربط الأحداث للحقول المؤثرة
    const calculationInputs = ["Amount_collection", "Rate_VAT"];

    calculationInputs.forEach((id) => {
        document
            .getElementById(id)
            .addEventListener("input", calculateCommissionAndVAT);
        document
            .getElementById(id)
            .addEventListener("change", calculateCommissionAndVAT);
    });

    // حساب أولي عند التحميل
    calculateCommissionAndVAT();
});

function formatNumber(num) {
    return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
}
