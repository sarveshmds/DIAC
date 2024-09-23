// initialize plugin with defaults
// Common file tag configuration ==========================================
let commonFileConfig = {
	theme: "fa",
	showUpload: false,
	showCancel: true,
	overwriteInitial: false,
	autoReplace: false,
	maxFileCount: 5,
	removeClass: "btn btn-danger",
	browseClass: "btn btn-primary",
	allowedFileExtensions: ["pdf", "doc", "docx"],
	previewFileType: "any",
	browseLabel: "Pick Documents",
	append: true, // whether to append content to the initial preview (or set false to overwrite)
};

// Miscellaneous Preview
if ($(".multiple_miscellaneous_initial_preview").val()) {
	let files = JSON.parse($(".multiple_miscellaneous_initial_preview").val());
	commonFileConfig.initialPreview = [];
	commonFileConfig.initialPreviewAsData = true;
	commonFileConfig.overwriteInitial = true;
	commonFileConfig.initialPreviewConfig = [
		{ type: "pdf", width: "100%", key: 1 },
	];
	commonFileConfig.initialPreviewShowDelete = true;

	if (files) {
		for (let i = 0; i < files.length; i++) {
			console.log(files[i]);
			commonFileConfig.initialPreview.push(
				"" + base_url + "public/upload/miscellaneous_documents/" + files[i]
			);
		}
	}
}
$(".documents-input").fileinput(commonFileConfig);

// Common file tag configuration ==========================================
let commonPDFFileConfig = {
	theme: "fa",
	showUpload: false,
	showCancel: true,
	overwriteInitial: false,
	autoReplace: false,
	maxFileCount: 5,
	removeClass: "btn btn-danger",
	browseClass: "btn btn-primary",
	allowedFileExtensions: ["pdf"],
	previewFileType: "any",
	browseLabel: "Pick Documents",
	append: true, // whether to append content to the initial preview (or set false to overwrite)
};

$(".pdf-documents-input").fileinput(commonPDFFileConfig);

// Noting page: FIle tag configuration ===================
commonFileConfig.dropZoneEnabled = false;
$(".noting_file_input").fileinput(commonFileConfig);
