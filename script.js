$(function() {
    var NS = '.test';

    $(document).on('click', NS + ' .button', function(e) {
        e.preventDefault();
        $(this).closest('.button').toggleClass('selected');
    });
});
