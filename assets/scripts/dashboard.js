google.load("visualization", "1", {packages:["corechart"]});

(function($) {
    $.fn.drawChart = function(params, data) {
        var defaults = {
            title: 'Company Performance'
        }
        var options = $.extend(defaults, params);

        return this.each(function() {
            var chartData = google.visualization.arrayToDataTable(data);
            var chart = new google.visualization.LineChart(this);
            chart.draw(chartData, options);
        });
   }
})(jQuery);

