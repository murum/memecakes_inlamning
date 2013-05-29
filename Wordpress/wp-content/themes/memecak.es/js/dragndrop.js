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
	console.log(files);
	for (var i = 0; i < files.length; i++) {
	    var file = files[i];
	    var imageType = /image.*/;
	    var preview = document.getElementById("memecake-upload-img-preview");
	     
	    if (!file.type.match(imageType)) {
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
	        success: function(e) {
	        	console.log(e);
	        	AddImageUrlField(e);
	        }
        });
	}
}

function AddImageUrlField(e) {
	$("#memecake-upload-form").append("<input type='hidden' name='memecake-attach-id' value='"+e+"' />");
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

$(function(){

	$('#memecake-submit').hide();
	var dropbox;

	dropbox = document.getElementById("memecake-upload-dropzone");
	dropbox.addEventListener("dragenter", dragenter, false);
	dropbox.addEventListener("dragover", dragover, false);
	dropbox.addEventListener("drop", drop, false);

	$('.memecake-meme').on('click', function(e) {
		e.preventDefault();
		$('.memecake-meme-not-choosed').toggleClass('hidden');
		$('.memecake-meme-choosed').toggleClass('hidden');
		AddChoosedMeme($(this).attr('meme'));
		AddMemeField($(this).attr('meme'));
		AddBackToList();
	});

	$('.memecake-upload-browse-image-button').on('click', function() {
		$("#memecake-image").trigger('click');
	});

	$('.memecake-upload-browse-image').change(function(e) {
		var file = $(this).val();
		var fileName = file.split("\\");
		$('.memecake-upload-browse-image-button').html(fileName[fileName.length-1]);

		var files = e.target.files;
		 
		handleFiles(files);
	});

	$('.memecake-upload-submit-button').on('click', function(e) {
		$('#memecake-submit').trigger('click');
	});

});