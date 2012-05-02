google.load("visualization", "1", {packages:["corechart"]});

(function($) {
    $.fn.drawChart = function(params, data) {
        var defaults = {
            title: 'Company Performance'
        }
        var options = $.extend(defaults, params);
        //console.log(data);
        //console.log(options);
        return this.each(function() {
            console.log(this);
            var chartData = google.visualization.arrayToDataTable(data);
            var chart = new google.visualization.LineChart(this);
            chart.draw(chartData, options);
        });
   }
})(jQuery);

google.setOnLoadCallback(function(){
    var lineChart = $('#analytics_report');
    
    if (lineChart.length) {
        lineChart.load(lineChart.data('url'), function(){
            $("#chart_div").drawChart(
            {
                title: "Visits over past 30 days",
                legend: {position: "none"}
            }
            , lineChartData);
        });
    }
});
