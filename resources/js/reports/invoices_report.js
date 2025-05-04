$(document).ready(function() {
    // Initialize datepicker
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '2000:2030'
    });

    // Toggle search sections based on radio button
    function toggleSearchSections() {
        if ($('input[name="rdio"]:checked').val() == '1') {
            $('#type_section, #start_at_section, #end_at_section').show();
            $('#invoice_number_section').hide();
            $('#invoice_number_section input').val('');
        } else {
            $('#type_section, #start_at_section, #end_at_section').hide();
            $('#invoice_number_section').show();
            $('#type_section select, #start_at_section input, #end_at_section input').val('');
        }
    }

    // Initial toggle
    toggleSearchSections();

    // Toggle on radio change
    $('input[name="rdio"]').change(function() {
        toggleSearchSections();
    });

    // Initialize DataTable
    $('#invoices-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        }
    });
});

