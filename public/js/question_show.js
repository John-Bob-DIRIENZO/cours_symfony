
$('.js-vote button').click(function (e) {
    e.preventDefault();

    let direction = $(e.currentTarget).data('direction');
    let pill = $(e.currentTarget).parent().find('.js-vote-total');

    $.post(
        '/comments/1/vote/' + direction,
        {},
        function (response) {
            pill.text(response.votes);
        }
    )
});