                </div>
            </div> <!-- End content -->

            <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    I'm
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> fucking </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Peaky Blinders </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2069, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="http://www.themekita.com">Rvssian</a>
            </div>
            <div>
              Distributed by
              <a target="_blank" href="https://themewagon.com/">Arch</a>.
            </div>
          </div>
        </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../public/assets/js/plugin/chart.js/chart.min.js"></script>
    <script src="../../public/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="../../public/assets/js/kaiadmin.min.js"></script>
    
    <script>
        // Sparklines para el dashboard
        $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#177dff",
            fillColor: "rgba(23, 125, 255, 0.14)",
        });
    </script>
</body>
</html>