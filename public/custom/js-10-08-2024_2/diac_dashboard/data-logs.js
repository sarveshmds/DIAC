
// Datatable initialization
var dataTable = $('#dataTableDataLogsList').DataTable({
	"processing": true,
	"serverSide": true,
	"autoWidth": false,
	"responsive": true,
	"order": [],
	"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	"ajax": {
		url: base_url+"service/get_all_data_logs_list/DATA_LOGS_LIST",
		type: 'POST',
		data: {
			csrf_trans_token: $('#csrf_data_logs_trans_token').val()
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    } 
		},
		{data: 'alter_by_user'},
		{data: 'message'},
		{data: 'date'}
		
	],
	"columnDefs": [
		{
			"targets": [0, 1, 2, 3],
			"orderable": false,
			"sorting": false
		}
	]
});
