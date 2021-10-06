
$('.js-vote button').click(function (e) {
    e.preventDefault();

    let direction = $(e.currentTarget).data('direction');
    let answer_id = $(e.currentTarget).data('id');
    let pill = $(e.currentTarget).parent().find('.js-vote-total');

    $.post(
        '/answers/' + answer_id + '/vote',
        {
            direction: direction
        },
        function (response) {
            pill.text(response.votes);
        }
    )
});