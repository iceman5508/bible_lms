// var options = {
//   series: allPercentage,
//   chart: {
//     type: 'donut',
//     width: 325,
//   },
//   responsive: [{
//     breakpoint: 480,
//     options: {
//       chart: {
//         width: 250
//       },
//       legend: {
//         position: 'bottom'
//       }
//     }
//   }],

//   labels: allName

// };

// var chart = new ApexCharts(document.querySelector("#donut"), options);
// chart.render();

var options = {
  series: allPercentage,
  chart: {
      type: 'donut',
  },
  dataLabels: {
      enabled: true,
      formatter: function(val, opts) {
        console.log(opts)
          return opts.w.config.series[opts.seriesIndex]+currencySymbol;
      },
      offsetY: -4
  },
  tooltip: {
      enabled: true,
      fillSeriesColor:false
  },
  responsive: [{
      breakpoint: 480,
      options: {
          chart: {
              width: 200
          },
          legend: {
              position: 'bottom'
          }
      }
  },
  ],
  labels: allName
};

var chart = new ApexCharts(document.querySelector("#donut"), options);
chart.render();