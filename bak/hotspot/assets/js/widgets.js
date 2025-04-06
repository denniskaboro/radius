$(function () {
    "use strict";
    var e = {
        series: [{name: "Total Income", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "area",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#783dfd"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#783dfd"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2.4, curve: "smooth"},
        colors: ["#783dfd"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart1"), e).render();
    e = {
        series: [{name: "Bounce Rate", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "bar",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#b7d100"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#b7d100"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "35%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 0, curve: "smooth"},
        colors: ["#b7d100"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart2"), e).render();
    e = {
        series: [{name: "Comments", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "area",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#44b614"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#44b614"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2.4, curve: "smooth"},
        colors: ["#44b614"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart3"), e).render()
    e = {
        series: [{name: "Page Views", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "bar",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#f41127"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#f41127"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "35%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 0, curve: "smooth"},
        colors: ["#f41127"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart4"), e).render();

    var e = {
        series: [{name: "Total Income", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "area",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#00c0f5"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#1bf5f5"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2.4, curve: "smooth"},
        colors: ["#00c0f5"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };

    new ApexCharts(document.querySelector("#w-chart5"), e).render();
    e = {
        series: [{name: "Bounce Rate", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "bar",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#42de00"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#42de00"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "35%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 0, curve: "smooth"},
        colors: ["#42de00"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart6"), e).render();
    e = {
        series: [{name: "Comments", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "area",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#00ce41"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#00ce41"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2.4, curve: "smooth"},
        colors: ["#00ce41"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart7"), e).render()
    e = {
        series: [{name: "Page Views", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "bar",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#f45570"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#f45570"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "35%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 0, curve: "smooth"},
        colors: ["#f45570"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart8"), e).render();

    var e = {
        series: [{name: "Total Income", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "area",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#00ae09"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#912f00"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2.4, curve: "smooth"},
        colors: ["#00ae09"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };

    new ApexCharts(document.querySelector("#w-chart9"), e).render();
    e = {
        series: [{name: "Bounce Rate", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "bar",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#912f00"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#912f00"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "35%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 0, curve: "smooth"},
        colors: ["#912f00"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart10"), e).render();
    e = {
        series: [{name: "Comments", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "area",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#a334b7"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#00ce41"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2.4, curve: "smooth"},
        colors: ["#a334b7"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart11"), e).render()
    e = {
        series: [{name: "Page Views", data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]}],
        chart: {
            type: "bar",
            height: 65,
            toolbar: {show: !1},
            zoom: {enabled: !1},
            dropShadow: {enabled: !0, top: 3, left: 14, blur: 4, opacity: .12, color: "#546774"},
            sparkline: {enabled: !0}
        },
        markers: {size: 0, colors: ["#546774"], strokeColors: "#fff", strokeWidth: 2, hover: {size: 7}},
        plotOptions: {bar: {horizontal: !1, columnWidth: "35%", endingShape: "rounded"}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 0, curve: "smooth"},
        colors: ["#546774"],
        xaxis: {categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]},
        fill: {opacity: 1},
        tooltip: {
            theme: "dark", fixed: {enabled: !1}, x: {show: !1}, y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            }, marker: {show: !1}
        }
    };
    new ApexCharts(document.querySelector("#w-chart12"), e).render();

});