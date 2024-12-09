function repoFormatResult(repo) {
    var markup = '<div class="row-fluid">' +
        '<div class="span12">' + repo.title + '</div>';

    markup += '</div>';

    return markup;
}

function repoFormatSelection(repo) {
    return repo.title;
}