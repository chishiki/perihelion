
function getLocation() {
	
	var loc = location;

	loc.href;     // "https://perihelion.xyz:80/kinjitsuten/?search=test#hash";
	loc.protocol; // => "http:"
	loc.host;     // => "perihelion.xyz:80"
	loc.hostname; // => "perihelion.xyz"
	loc.port;     // => "80"
	loc.pathname; // => "/kinjitsuten/"
	loc.hash;     // => "#hash"
	loc.search;   // => "?search=test" // locSearch
	loc.origin;   // => "http://example.com:3000"

	console.dir(loc);
	
	return loc;


	
}

function getUrlData(locSearch) {
	
    var urlData = {};
    var pairs = (locSearch[0] === '?' ? locSearch.substr(1) : locSearch).split('&');
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('=');
        urlData[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
    }

    console.dir(urlData);
    
    return urlData;
    
}

$(window).on('load', function() {

		var lang = 'en';
		var langPrefix = '';
		var langSelectImages = 'Select Images';
		var langImagesSelected = ' Images Selected';
		var langSelectFiles = 'Select Files';
		var langFilesSelected = ' Files Selected';

		if ($('body').hasClass('lang-ja')) {
			lang = 'ja';
			langPrefix = '/ja';
			langSelectImages = 'イメージ選択';
			langImagesSelected = 'つのイメージ';
			langSelectFiles = 'ファイル選択';
			langFilesSelected = 'つのファイル';
		}

		$('.clickable').click(
			function() { window.document.location = $(this).data('url'); }
		);

		/*
		$('.alphanumeric-and-hyphens-only').keypress(function (e) {
			var allowedChars = new RegExp("^[a-zA-Z0-9\-]+$");
			var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
			if (allowedChars.test(str) || e.which === 8 || e.which === 9 || e.which === 13 || e.which === 16 || e.which === 17 || e.which === 18) { return true; }
			e.preventDefault();
			return false;
		}).keyup(function() {
			var forbiddenChars = new RegExp("[^a-zA-Z0-9\-]", 'g');
			if (forbiddenChars.test($(this).val())) { $(this).val($(this).val().replace(forbiddenChars, '')); }
		});
		*/
		
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})

		$('#profileChangePassword').click(function() {
			if( $(this).is(':checked')) {
				$('#userPassword').removeAttr('disabled');
				$('#confirmPassword').removeAttr('disabled');
			} else {
				$('#userPassword').attr('disabled','disabled');
				$('#confirmPassword').attr('disabled','disabled');
			}
		});

		$('#new_image_select_input').on('change click keyup', newImagesPrep);

		function newImagesPrep(event) {
			var images = event.target.files;
			var imageCount = images.length;
			if (imageCount < 1) {
				$('#new_image_select_prompt').text(langSelectImages);
				$('#new_image_submit_button').prop('disabled',true);
			} else {
				$('#new_image_select_prompt').text(imageCount+langImagesSelected);
				$('#new_image_submit_button').prop('disabled',false);
			}
		}

		$('#new_file_select_input').on('change click keyup', newFilesPrep);

		function newFilesPrep(event) {
			var files = event.target.files;
			var fileCount = files.length;
			if (fileCount < 1) {
				$('#new_file_select_prompt').text(langSelectFiles);
				$('#new_file_submit_button').prop('disabled',true);
			} else {
				$('#new_file_select_prompt').text(fileCount+langFilesSelected);
				$('#new_file_submit_button').prop('disabled',false);
			}
		}

		$('.image-list-default-radio > input[type="radio"][name="mainImage"]').on('change', function() {

			var imageID = $(this).closest('tr').data('image-id');
			var imageObject = $(this).closest('tr').data('image-object');
			var imageObjectID = $(this).closest('tr').data('image-object-id');

			$(this).closest('table').find('button.image-delete').removeClass('disabled').prop("disabled", false);
			$(this).closest('tr').find('button.image-delete').addClass('disabled').prop("disabled", true);

			var settings = {
				url: "/api/v1/image/set-default/",
				method: "post",
				data: { imageID : imageID, imageObject : imageObject, imageObjectID : imageObjectID },
				dataType: "json"
			};

			$.ajax(settings).always(function(data) {
				console.dir(data);
			});

		});

		$('.image-list-action > button.image-delete').on('click', function() {

			var imageID = $(this).closest('tr').data('image-id');

			$(this).closest('tr').remove();

			var settings = {
				url: "/api/v1/image/delete/",
				method: "post",
				data: { imageID : imageID },
				dataType: "json"
			};

			$.ajax(settings).always(function(data) {
				console.dir(data);
			});

		});

		$('.file-list-action > button.file-delete').on('click', function() {

			var fileID = $(this).closest('tr').data('file-id');

			$(this).closest('tr').remove();

			var settings = {
				url: "/api/v1/file/delete/",
				method: "post",
				data: { fileID : fileID },
				dataType: "json"
			};

			$.ajax(settings).always(function(data) {
				console.dir(data);
			});

		});

		$('#content_form_container_select').on('change', function() {

			var containerClasses = $(this).val();
			console.log(containerClasses);
			if (containerClasses === 'container-fluid' || containerClasses === 'container') {
				$('#content_form_row_input').val('row');
				$('#content_form_col_input').val('col-12');
			} else {
				$('#content_form_row_input').val('');
				$('#content_form_col_input').val('');
			}

		});

		console.log("perihelion.js has loaded");

});
