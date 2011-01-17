$(function() {
    // load themes for selected segment
    $('#FormSegment').change(function() {
        var self = $(this);
        if(self.val()) {
            $.get('/segments/view/' + self.val(), {}, function(response) {
                var themes = response.themes;
                var theme_select = $('#FormTheme');
                $('option', theme_select).slice(1).remove();

                $.each(themes, function(key, value) {
                    var option = $('<option />').html(value).attr('value', key);
                    option.appendTo(theme_select);
                });
            });
        }
    }).change();
});