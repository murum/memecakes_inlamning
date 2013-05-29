function SubmitSearchForm() {
	$('#meme-search').submit();
}

// <select> element displays its options on mousedown, not click.
function ShowDropdown(element) {
    var event;
    event = document.createEvent('MouseEvents');
    event.initMouseEvent('mousedown', true, true, window);
    element.dispatchEvent(event);
};

$(function() {
	$('.meme-search-icon').on('click', function() {
		SubmitSearchForm();
	});

	$('.meme-sort-icon').on('click', function() {
		var dropdown = document.getElementById('sort-meme');
    	ShowDropdown(dropdown);
	});

	$('#sort-meme').on('change', function() {
		SubmitSearchForm();
	});
});