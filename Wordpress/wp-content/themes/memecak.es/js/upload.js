function dragenter(e) {
  e.stopPropagation();
  e.preventDefault();
}
 
function dragover(e) {
  e.stopPropagation();
  e.preventDefault();
}

function drop(e) {
  e.stopPropagation();
  e.preventDefault();
 
  var dt = e.dataTransfer;
  var files = dt.files;
 
  handleFiles(files);
}

function handleFiles(files) {
	for (var i = 0; i < files.length; i++) {
	    var file = files[i];
	    var imageType = /image.*/;
	    var preview = document.getElementById("memecake-upload-img-preview");
	    $('#memecake-upload-img-preview').empty();

	    // Check filetypes
		if (!file.type.match(imageType)) {
	    	ShowUploadError();
	    	continue;
	    }
	     
	    var img = document.createElement("img");
	    img.classList.add("obj");
	    img.file = file;
	    preview.appendChild(img);
	     
	    var reader = new FileReader();
	    reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
	    reader.readAsDataURL(file);

		var formData = new FormData(document.forms.namedItem("memecake-upload-form"));
		formData.append('memecake-img',file);
		formData.append('upload', true);

		return $.ajax({
	        url: "/upload/",
	        type: "POST",
	        data: formData,
	        processData: false,
	        contentType: false,
	        beforeSend: function(e) {
				$('.memecake-upload-submit-button').hide();
				$('.ajaxLoading').show();
	        },
	        success: function(e) {
	        	$('.memecake-upload-submit-button').show();
	        	$('#memecake-upload-dropzone').height($(img).height());
	        	$('.memecake-upload-dropzone-message').hide();
	        	$('#memecake-upload-img').empty();
	        	$('.ajaxLoading').hide();
	        	$('.uploadError').hide();
	        	AddImageUrlField(e);
	        }
        });
	}
}

function AddImageUrlField(e) {
	$(".memecake-upload-errors-container").empty();
	$("#memecake-upload-form").append("<input type='hidden' name='memecake-attach-id' value='"+e+"' />");
};

function ShowUploadError(e) {
	$(".memecake-upload-errors-container").empty();
	$(".memecake-upload-errors-container").append("<div class='memecake-upload-errors'><p>You tried to upload a file which wasn't an image</p></div>");
};

function AddMemeField(meme) {
	$("#memecake-upload-form").append("<input type='hidden' id='memecake-meme' name='memecake-meme' value='"+meme+"' />");
};

function AddChoosedMeme(choosedMemeId) {
	$('.memecake-choosen-meme').empty();

	var memeText = $("a[meme="+choosedMemeId+"]").text();
	$('.memecake-choosen-meme').append($("div[meme="+choosedMemeId+"]").children().clone());
	$('.memecake-choosen-meme').append($('<span class="memecake-choosen-meme-text">'+memeText+'</span>'));
	$('.memecake-choosen-meme').append($('<a href="#" class="icon-ok-sign icon-2x memecake-meme-remove green"></a>'));
	$('.memecake-choosen-meme').append($('<div class="memecake-choosen-meme-back icon-reply"><span class="text">I didn\'t mean '+memeText+'</span></div>'));

	RemoveChoosedMeme();
};

function RemoveChoosedMeme() {
	$('.memecake-meme-remove').on('click', function(e) {
		e.preventDefault();
		$('.memecake-meme-not-choosed').toggleClass('hidden');
		$('.memecake-meme-choosed').toggleClass('hidden');
		$('#memecake-meme').remove();
	});
}

function AddBackToList() {
	$('.memecake-choosen-meme-back').on('click', function() {
		$('.memecake-meme-not-choosed').toggleClass('hidden');
		$('.memecake-meme-choosed').toggleClass('hidden');
		$('#memecake-meme').remove();
	});
}

function ClickOnMeme() {
	$('.memecake-meme').on('click', function(e) {
		e.preventDefault();
		$('.memecake-meme-not-choosed').toggleClass('hidden');
		$('.memecake-meme-choosed').toggleClass('hidden');
		AddChoosedMeme($(this).attr('meme'));
		AddMemeField($(this).attr('meme'));
		AddBackToList();
	});
}

$(function(){
	$('.ajaxLoading').hide();
	$('input#meme').focus();
	$('#memecake-submit').hide();
	var dropbox;

	dropbox = document.getElementById("memecake-upload-dropzone");
	dropbox.addEventListener("dragenter", dragenter, false);
	dropbox.addEventListener("dragover", dragover, false);
	dropbox.addEventListener("drop", drop, false);


	ClickOnMeme();

	$('.memecake-upload-browse-image-button').on('click', function() {
		$("#memecake-image").trigger('click');
	});

	$('.memecake-upload-browse-image').change(function(e) {
		var file = $(this).val();
		var fileName = file.split("\\");
		$('.memecake-upload-browse-image-button').html("I want another picture");

		var files = e.target.files;
		 
		handleFiles(files);
	});

	if($('#memecake-meme').val()) {
		$('.memecake-meme-not-choosed').hide();
		$('.memecake-meme-choosed').removeClass('hidden');
		var memeText = $("a[meme="+$('#memecake-meme').val()+"]").text();
		$('.memecake-choosen-meme').append($("div[meme="+$('#memecake-meme').val()+"]").children().clone());
		$('.memecake-choosen-meme').append("<p class='memecake-choosen-meme-is'>The choosen meme is "+memeText+"</p>");
		$('.memecake-upload-submit-button').show();
	}

	$('.memecake-upload-submit-button').on('click', function(e) {
		$('#memecake-submit').trigger('click');
	});

	$('input#meme').keypress(function(event){
		if (event.which == '13') {
			event.preventDefault();
		}
	});
	$('input#meme').on('keyup', function() {
		return $.ajax({
	        url: "/upload/",
	        type: "POST",
	        data: {meme: $('input#meme').val() },
	        beforeSend: function(e) {
	        	$('.memecake-upload-search-meme-loader').show();
	        },
	        success: function(e) {
	        	$('.memecake-meme-not-choosed').empty();
	        	$('.memecake-meme-not-choosed').append(e);
	        	$('.memecake-upload-search-meme-loader').hide();
	        	ClickOnMeme();
	        }
        });
	});

	$('#memecake-description').on('keyup', function() {
		$('.memecake-description-counter').show();
		var descriptionCharCounter = $('#memecake-description').val().length;
		$('.memecake-description-counter-chars').html(descriptionCharCounter);
		if(descriptionCharCounter > 3500) {
			$('.memecake-upload-submit-button').hide();
			$('.memecake-description-counter').css('color', 'red');
		} else {
			$('.memecake-upload-submit-button').show();
			$('.memecake-description-counter').css('color', '#0c0');
		}
	});

	$('#memecake-description').on('keydown', function() {
		if($(this).val() == "Why and how did you bake this cake?") {
			$(this).val("");
		}
	});
	$('#memecake-description').on('focusout', function() {
		if($(this).val() == "") {
			$(this).val("Why and how did you bake this cake?");
		}
	})

	$('#memecake-title').on('keyup', function() {
		$('.memecake-title-counter').show();
		var titleCharCounter = $('#memecake-title').val().length;
		$('.memecake-title-counter-chars').html(titleCharCounter);
		if(titleCharCounter > 45) {
			$('.memecake-upload-submit-button').hide();
			$('.memecake-title-counter').css('color', 'red');
		} else {
			$('.memecake-upload-submit-button').show();
			$('.memecake-title-counter').css('color', '#0c0');
		}
	});

	$('#memecake-title').on('keydown', function() {
		if($(this).val() == "What would you like to call your memecake?") {
			$(this).val("");
		}
	});

	$('#memecake-title').on('focusout', function() {
		if($(this).val() == "") {
			$(this).val("What would you like to call your memecake?");
		}
	})
});