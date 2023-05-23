$(document).ready(function() {
  $('#dataTable').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json',
    },
    dom: 'Bfrtip',
    bPaginate: true,
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    info: false,
    pageLength : 15,
    "columnDefs": [{
      "targets": '_all',
      "createdCell": function (td, cellData, rowData, row, col) {
        $(td).css('padding', '5px');
        //$(td).css('text-align', 'left');
      }
    }],
    fnDrawCallback: function(oSettings) {
      if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
      } else {
        $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
      }
    }
  });

});
