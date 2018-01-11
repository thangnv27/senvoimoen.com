<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/libs/js/highcharts.js"></script>
<div id="visits-log"></div>
<?php
$last_day = 20;
if (isset($_GET['startdate'])) {
    $startDate = $_GET['startdate'];
    $endDate = CurrentDate('Y-m-d');
    if (isset($_GET['startdate'])) {
        $endDate = $_GET['enddate'];
        if(strtotime($startDate) > strtotime($endDate)){
            $startDate = $_GET['enddate'];
            $endDate = $_GET['startdate'];
        }
    }
    $last_day = dateDifference($endDate, $startDate);
} else if(isset($_GET['hitdays']) and intval($_GET['hitdays']) > 0){
    $last_day = intval($_GET['hitdays']);
}
?>
<script type="text/javascript">
    var visit_chart;
    var last_day = <?php echo $last_day; ?>;
    <?php if(!isset($_GET['enddate']) or trim($_GET['enddate']) == ""): ?>
    var categories_datetime = [<?php
        for ($i = $last_day; $i >= 0; $i--) {
            echo '"' . CurrentDate('Y-m-d', '-' . $i) . '"';
            echo ", ";
        }
        ?>];
    var data_visit = [<?php
        for ($i = $last_day; $i >= 0; $i--) {
            echo ppo_statistics_taxonomy('-' . $i, $cat_id);
            echo ", ";
        }
        ?>];
    <?php else: ?>
    var categories_datetime = [<?php
        for ($i = $last_day; $i >= 0; $i--) {
            echo '"' . date("Y-m-d", strtotime($endDate) - 3600*24*$i) . '"';
            echo ", ";
        }
        ?>];
    var data_visit = [<?php
        for ($i = $last_day; $i >= 0; $i--) {
            echo ppo_statistics_taxonomy(date("Y-m-d", strtotime($endDate) - 3600*24*$i), $cat_id);
            echo ", ";
        }
        ?>];
    <?php endif; ?>
    visit_chart = new Highcharts.Chart({
        chart: {
            renderTo: 'visits-log',
            type: 'line', // line, spline, area, areaspline, column, bar, scatter
            backgroundColor: '#FFFFFF',
            height: '300'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Lượt truy cập ' + last_day + ' ngày gần đây',
            style: {
                fontSize: '12px',
                fontFamily: 'Tahoma',
                fontWeight: 'bold'
            }
        },
        xAxis: {
            type: 'datetime',
            labels: {
                rotation: -45
            },
            categories: categories_datetime
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Số lượt truy cập',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Tahoma'
                }
            }
        },
        legend: {
            rtl: true,
            itemStyle: {
                fontSize: '11px',
                fontFamily: 'Tahoma'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true,
            style: {
                fontSize: '12px',
                fontFamily: 'Tahoma'
            },
            useHTML: true
        },
        series: [{
                name: '<?php echo $category->name; ?>',
                data: data_visit
            }]
    });
</script>