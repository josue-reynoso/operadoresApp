$(document).on('keyup', '.multiLineInput', function(e) {
    $(this).val($(this).val().replace(/\n|\r/g, " "));
});
$(document).on('keydown', '.multiLineInput', function(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        return false;
    }
});