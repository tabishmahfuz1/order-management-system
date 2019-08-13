// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
  	"searching": false,
  	bPaginate: false,
  	dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });
});
