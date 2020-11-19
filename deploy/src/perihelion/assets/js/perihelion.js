
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

$(document).ready( // dom is ready

	function() {
		
		$('.clickable').click(
			function() {
				window.document.location = $(this).data('url');
			}
		);
		
		$('.alphaNumericHyphenOnly').keypress(function (e) {
			var allowedChars = new RegExp("^[a-zA-Z0-9\-]+$");
			var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
			if (allowedChars.test(str) || e.which === 8 || e.which === 9 || e.which === 13 || e.which === 16 || e.which === 17 || e.which === 18) { return true; }
			e.preventDefault();
			return false;
		}).keyup(function() {
			var forbiddenChars = new RegExp("[^a-zA-Z0-9\-]", 'g');
			if (forbiddenChars.test($(this).val())) { $(this).val($(this).val().replace(forbiddenChars, '')); }
		});
		
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})

		$('#contentImages').on('change click keyup', contentImagesPrep);

		function contentImagesPrep(event) {
			images = event.target.files;
			var fileCount = images.length;
			if (fileCount < 1) {
				$('#perihelionDesignerContentImagesSubmitButtonText').text('Select Images');
				$('#perihelionDesignerContentImagesSubmit').prop("disabled",true);
			} else {
				$('#perihelionDesignerContentImagesSubmitButtonText').text(images.length + ' Images Selected');
				$('#perihelionDesignerContentImagesSubmit').prop("disabled",false);
			}
		}

		$('#perihelionImages').on('change click keyup', perihelionImagesPrep);
		function perihelionImagesPrep(event) {
			images = event.target.files;
			var fileCount = images.length;
			if (fileCount < 1) {
				$('#perihelionImageManagerSubmitButtonText').text('Select Images');
				$('#perihelionImageManagerSubmit').prop("disabled",true);
			} else {
				$('#perihelionImageManagerSubmitButtonText').text(images.length + ' Images Selected');
				$('#perihelionImageManagerSubmit').prop("disabled",false);
			}
		}
		
		$('#perihelionFiles').on('change click keyup', perihelionFilesPrep);
		function perihelionFilesPrep(event) {
			
			var segments = location.pathname.split('/');
			var unselectedButtonText = 'Select Files';
			var selectedButtonText = '　Files Selected';
			if (segments[1] == 'ja') {
				unselectedButtonText = 'ファイル選択';
				selectedButtonText = 'つのファイル';
			}

			files = event.target.files;
			var fileCount = files.length;
			if (fileCount < 1) {
				$('#perihelionFileManagerSubmitButtonText').text(unselectedButtonText);
				$('#perihelionFileManagerSubmit').prop("disabled",true);
			} else {
				getLocation();
				$('#perihelionFileManagerSubmitButtonText').text(files.length + ' ' + selectedButtonText);
				$('#perihelionFileManagerSubmit').prop("disabled",false);
			}
		}
		
		$('#profileChangePassword').click(function() {
			if( $(this).is(':checked')) {
				$('#userPassword').removeAttr('disabled');
				$('#confirmPassword').removeAttr('disabled');
			} else {
				$('#userPassword').attr('disabled','disabled');
				$('#confirmPassword').attr('disabled','disabled');
			}
		});

		$('#freshperihelionFiles').on('change click keyup', freshPerihelionFilesPrep);

		function freshPerihelionFilesPrep(event) {
			
			files = event.target.files;
			var fileCount = files.length;
			if (fileCount < 1) {
				$('#freshPerihelionFilesSubmitButtonText').text('Select Files');
				$('#freshPerihelionFilesSubmit').prop("disabled",true);
			} else {
				$('#freshPerihelionFilesSubmitButtonText').text(files.length + ' Files Selected');
				$('#freshPerihelionFilesSubmit').prop("disabled",false);
			}
		}
		
		$('#freshPerihelionImages').on('change click keyup', freshPerihelionImagesPrep);

		function freshPerihelionImagesPrep(event) {
			console.log('wuttup');
			images = event.target.files;
			var fileCount = images.length;
			if (fileCount < 1) {
				$('#freshPerihelionImagesSelectPromptText').text('Select Images');
				$('#freshPerihelionImagesSubmit').prop("disabled",true);
			} else {
				$('#freshPerihelionImagesSelectPromptText').text(images.length + ' Images Selected');
				$('#freshPerihelionImagesSubmit').prop("disabled",false);
			}
		}

	}
	
);

$(window).on('load', function () {
	console.log("assets have been loaded");
});
