$(document).ready(() => {
    BDashboard.loadWidget($('#widget_series').find('.widget-content'), route('series.widget.list-series'));
});