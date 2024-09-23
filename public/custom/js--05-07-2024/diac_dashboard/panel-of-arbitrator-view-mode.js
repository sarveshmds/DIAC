// Datatable initialization
var dataTable = $('#dataTablePOAList').DataTable({
	"processing": true,
	"serverSide": true,
	"order": [],
    "responsive": true,
	"ajax": {
		url: base_url+"service/get_all_poa_list/ALL_POA_LIST",
		type: 'POST',
		data: function(d){
			d.csrf_trans_token = $('#csrf_poa_trans_token').val();
			d.category = $('#poa_f_category').val();
			d.name = $('#poa_f_name').val();
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    }
			
		},
		{data: 'name'},
		{data: 'category_name'},
        {data: 'experience'},
        {data: 'enrollment_no'},
        {data: 'contact_details'},
        {data: 'email_details'},
        {data: 'address_details'},
        {data: 'remarks'},
		{data: 'id',
			"sWidth": "15%",
			"sClass":"alignCenter",
			"render": function (data, type, row, meta) {
		        return '<button class="btn btn-success btn-sm" onclick="viewPOAData(event)"><span class="fa fa-eye"></span></button>'
		    }
		}
		
	],
	"columnDefs": [
		{
            "targets": [0, 1, 2, 3, 4, 5, 6,7 , 8, 9],
            "orderable": false,
            "sorting": false
        },
        {
            "targets": [3,4,5,6,7, 8],
            "visible": false
        }
	],
	dom: 'lBfrtip',
    buttons: [
        {
            text: '<span class="fa fa-file-pdf-o"></span> PDF',
            className: 'btn btn-danger',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
				window.open(base_url+'pdf/panel-of-arbitrators', '_BLANK');
            }
        }
    ]
});


// View the panel of arbitrator data
function viewPOAData(event){
    // Get data table instance to get the data through row
    var oTable = $('#dataTablePOAList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
        row = event.target.parentNode.parentNode;
    else if(event.target.tagName == "SPAN")
        row = event.target.parentNode.parentNode.parentNode;


    var name = oTable.fnGetData(row)['name'];
    var category_name = oTable.fnGetData(row)['category_name'];
    var contact_details = oTable.fnGetData(row)['contact_details'];
    var email_details = oTable.fnGetData(row)['email_details'];
    var address_details = oTable.fnGetData(row)['address_details'];
    var enrollment_no = oTable.fnGetData(row)['enrollment_no'];
    var experience = oTable.fnGetData(row)['experience'];

    // Set data into table
    var table = "<table class='table table-responsive table-bordered table-striped'>";
    table += "<tr><th width='30%'>Name: </th> <td>"+name+"</td></tr>";
    table += "<tr><th>Category: </th> <td>"+category_name+"</td></tr>";
    table += "<tr><th>Enrollment No.: </th> <td>"+enrollment_no+"</td></tr>";
    table += "<tr><th>Experience: </th> <td>"+experience+"</td></tr>";
    table += "<tr><th>Contact Details: </th> <td>"+contact_details+"</td></tr>";
    table += "<tr><th>Email Details: </th> <td>"+email_details+"</td></tr>";
    table += "<tr><th>Address Details: </th> <td>"+address_details+"</td></tr>";
    table += "</table>";

    // Set the data in common modal to show the data
    $('.common-modal-title').html('<span class="fa fa-file"></span> Panel of arbitrator');
    $('.common-modal-body').html(table);
    $('#common-modal').modal('show');
}