function confirmDelete(invoiceId) {
    // عرض SweetAlert2 برومبت للتأكيد
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "لن يمكنك استرجاع الملف بعد الحذف!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذف الملف!",
        cancelButtonText: "إلغاء",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // إذا وافق المستخدم، يتم إرسال النموذج
            document.getElementById("delete-form-" + invoiceId).submit();
        }
    });
}

function printInvoice() {
    window.print();
}

document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.querySelector(".custom-file-input");
    const fileLabel = document.querySelector(".custom-file-label");

    // تحديث تسمية الملف عند تغييره
    fileInput.addEventListener("change", function () {
        if (fileInput.files.length > 0) {
            fileLabel.textContent = fileInput.files[0].name;
        }
    });

    // إذا كان هناك هاش في الرابط (#tab5)
    if (window.location.hash) {
        const hash = window.location.hash;

        // إزالة الفعالية من كل التبويبات
        document.querySelectorAll(".nav-link").forEach((link) => {
            link.classList.remove("active");
        });

        // إخفاء كل محتويات التبويبات
        document.querySelectorAll(".tab-pane").forEach((pane) => {
            pane.classList.remove("active", "show");
        });

        // تفعيل التبويب المطلوب
        const targetTab = document.querySelector(`[href="${hash}"]`);
        const targetPane = document.querySelector(hash);

        if (targetTab && targetPane) {
            targetTab.classList.add("active");
            targetPane.classList.add("active", "show");
        }
    }
});

// استخدام jQuery لتفعيل DataTables
$(document).ready(function() {
    // تفعيل DataTables لجميع الجداول التي تحمل المعرفات المحددة
    $('#payment-status-table, #attachments-table').DataTable({
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        }
    });

    // تحديث تسمية الملف عند تغييره باستخدام jQuery
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
