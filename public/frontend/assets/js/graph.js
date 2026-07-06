
      function initBusinessPerformanceChart() {
        (function () {
          // Dynamic data injected from the admin (Business Performance). Falls back to defaults.
          var YEARS  = (window.BP_YEARS && window.BP_YEARS.length)   ? window.BP_YEARS   : ['2016-17','2017-18','2018-19','2019-20','2020-21','2021-22','2022-23','2023-24','2024-25','2025-26'];
          var CATS   = (window.BP_CATS && window.BP_CATS.length)     ? window.BP_CATS    : ['Debenture Trustee','Security Trustee','Securitization','Others'];
          var COLORS = (window.BP_COLORS && window.BP_COLORS.length) ? window.BP_COLORS  : ['#c9624c','#4a7fb5','#e8a838','#3d8c6f'];
          var RAW = (window.BP_RAW && window.BP_RAW.length) ? window.BP_RAW : [
            [513,  109,  245,  21],
            [443,  199,  284,  74],
            [405,  224,  434,  33],
            [365,  280,  607,  30],
            [645,  210,  281,  43],
            [622,  443,  356,  75],
            [898,  647,  579,  76],
            [1110, 719,  646,  93],
            [3008, 2627, 591,  880],
            [5223, 4696, 2677, 2938]
          ];

          var TOTALS = RAW.map(function(r){ return r.reduce(function(a,b){ return a+b; }, 0); });

          var hiddenCats = [];
          var activeYear = 0;
          var charts = [];

          function init() {
            var mount = document.getElementById('chart_div');
            mount.innerHTML = '';

            var dash = document.createElement('div');
            dash.className = 'donut-dashboard';
            dash.innerHTML =
              '<div class="dash-legend" id="dd-legend"></div>' +
              '<div class="donuts-row"  id="dd-donuts"></div>' +
              '<div class="detail-panel" id="dd-detail">' +
                '<div class="dp-title" id="dd-dtitle"></div>' +
                '<div id="dd-dbars"></div>' +
              '</div>';
            mount.appendChild(dash);

            buildLegend();
            buildDonuts();
            selectYear(0);
          }

          function buildLegend() {
            document.getElementById('dd-legend').innerHTML = CATS.map(function(c, i) {
              return '<div class="dash-legend-item" id="leg-' + i + '" onclick="ddToggleCat(' + i + ')">' +
                '<span class="dash-legend-dot" style="background:' + COLORS[i] + '"></span>' +
                '<span>' + c + '</span>' +
              '</div>';
            }).join('');
          }

          window.ddToggleCat = function(ci) {
            var idx = hiddenCats.indexOf(ci);
            if (idx === -1) hiddenCats.push(ci);
            else hiddenCats.splice(idx, 1);
            document.querySelectorAll('.dash-legend-item').forEach(function(el, i) {
              el.classList.toggle('dimmed', hiddenCats.indexOf(i) !== -1);
            });
            rebuildDonuts();
            if (activeYear !== null) showDetail(activeYear);
          };

          function buildDonuts() {
            var grid = document.getElementById('dd-donuts');
            grid.innerHTML = YEARS.map(function(y, yi) {
              return '<div class="donut-cell" id="dcell-' + yi + '" onclick="ddSelectYear(' + yi + ')">' +
                '<canvas id="dcanvas-' + yi + '" width="90" height="90" role="img" aria-label="Donut for ' + y + '"></canvas>' +
                '<div class="d-year">' + y + '</div>' +
              '</div>';
            }).join('');
            charts = [];
            YEARS.forEach(function(_, yi){ renderDonut(yi); });
          }

          function rebuildDonuts() {
            charts.forEach(function(c){ c.destroy(); });
            charts = [];
            YEARS.forEach(function(_, yi){ renderDonut(yi); });
          }

          function renderDonut(yi) {
            var ctx = document.getElementById('dcanvas-' + yi).getContext('2d');
            var bgColors = COLORS.map(function(c, ci){
              return hiddenCats.indexOf(ci) !== -1 ? 'rgba(200,200,200,0.15)' : c;
            });
            var data = RAW[yi].map(function(v, ci){
              return hiddenCats.indexOf(ci) !== -1 ? 0 : v;
            });

            var c = new Chart(ctx, {
              type: 'doughnut',
              data: {
                datasets: [{
                  data: data,
                  backgroundColor: bgColors,
                  borderWidth: 0,
                  hoverOffset: 0
                }]
              },
              options: {
                responsive: false,
                cutout: '60%',
                animation: { animateRotate: true, duration: 600 },
                events: [],
                plugins: {
                  legend: { display: false },
                  tooltip: { enabled: false }
                }
              }
            });
            charts.push(c);
          }

          window.ddSelectYear = selectYear;

          function selectYear(yi) {
            activeYear = yi;
            document.querySelectorAll('.donut-cell').forEach(function(el, i){
              el.classList.toggle('active', i === yi);
            });
            showDetail(yi);
          }

          function showDetail(yi) {
            document.getElementById('dd-dtitle').textContent =
              YEARS[yi] + ' breakdown — ' + TOTALS[yi].toLocaleString() + ' total transactions';

            var visible = RAW[yi].filter(function(_, ci){ return hiddenCats.indexOf(ci) === -1; });
            var maxVal  = Math.max.apply(null, visible.length ? visible : [1]);

            document.getElementById('dd-dbars').innerHTML = CATS.map(function(c, ci) {
              if (hiddenCats.indexOf(ci) !== -1) return '';
              var v    = RAW[yi][ci];
              var pct  = Math.round(v / TOTALS[yi] * 100);
              var barW = Math.round(v / maxVal * 100);
              return '<div class="dp-bar-row">' +
                '<div class="dp-bar-name">' + c + '</div>' +
                '<div class="dp-bar-track"><div class="dp-bar-fill" style="width:' + barW + '%;background:' + COLORS[ci] + '"></div></div>' +
                '<div class="dp-bar-val">'  + v.toLocaleString() + '</div>' +
                '<div class="dp-bar-pct">'  + pct + '%</div>' +
              '</div>';
            }).join('');
          }

          if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
          } else {
            init();
          }
        })();
      }
      document.addEventListener("DOMContentLoaded", function () {

          const section = document.querySelector(".performance-wrap");
          let chartLoaded = false;

          const observer = new IntersectionObserver(function(entries) {

              entries.forEach(function(entry) {

                  if (entry.isIntersecting && !chartLoaded) {

                      chartLoaded = true;

                      initBusinessPerformanceChart();

                      observer.unobserve(section); // run only once
                  }
              });

          }, {
              threshold: 0.3 // trigger when 30% visible
          });

          observer.observe(section);

      });