<?php
if(session('idtipousu') == 3){
  echo "<h1>BIENVENIDO USUARIO, PUEDES REALIZAR TUS REQUERIMIENTOS DE MATERIALES</h1>";
}else{
?>

<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark">Dashboard ALMSIS</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-3">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="count_pro">0</h3>

                <p>Productos en total</p>
              </div>
              <div class="icon">
                <i class="fa fa-newspaper"></i>
              </div>
              <a href="productos" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-sm-12 col-md-12 col-lg-3">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="count_ent">0</h3>

                <p>Entradas</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-bag"></i>
              </div>
              <a href="entradas" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-sm-12 col-md-12 col-lg-3">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="count_sal">0</h3>

                <p>Salidas</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-cart"></i>
              </div>
              <a href="salidas" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-sm-12 col-md-12 col-lg-3">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>Kardex</h3>

                <p>Movimientos de productos</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
              <a href="kardex" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-sm-6">
            <!-- BAR CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Ultimas Salidas</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-sm-6">
            <!-- DONUT CHART -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Top 4 productos con más movimientos</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div><!-- /.container-fluid -->
</section>



<script>
$(function(){ 

    $.post('inicio/grafica_salidas', {

    }, function(data){
      var areaChartData = {
          labels  : [],
          datasets: [
            {
              label               : 'Salidas',
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : []
            }
          ]
        };

        //console.log(JSON.parse(data));
        let data_salida = JSON.parse(data);
        //console.log(data_salida.map(data => data.total))
        areaChartData.labels = data_salida.map(data => data.nom_mes);
        areaChartData.datasets[0].data = data_salida.map(total => total.total);

        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = jQuery.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        barChartData.datasets[0] = temp0


        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        var barChart = new Chart(barChartCanvas, {
          type: 'bar', 
          data: barChartData,
          options: barChartOptions
        })
    })

    


    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    $.post('inicio/topProductosMov', {

    }, function(data){
        let data_salida = JSON.parse(data);

        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData        = {
          labels: data_salida.map(data => data.codigo),
          datasets: [
            {
              data: data_salida.map(data => data.salidas),
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
          ]
        }
        var donutOptions     = {
          maintainAspectRatio : false,
          responsive : true,
          legend:{
            position: "left",
            onClick: (event, legend) => {
              let codigo = legend.text;
              let dato = data_salida.find(data => data.codigo == codigo)
              swal_alert(dato.codigo, `${dato.nombre}`, 'info', 'Aceptar');
              //alert(dato.codigo +' => '+ dato.nombre);
            }
          }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var donutChart = new Chart(donutChartCanvas, {
          type: 'doughnut',
          data: donutData,
          options: donutOptions      
        });
    });


    //COUNTERS DASHBOARD
    counters_dashboard();
});

function counters_dashboard(){
    var rnd = Math.random() * 11;
    $.post('inicio/counters_dashboard',{
        rnd
    }, function(data){
        let dat = JSON.parse(data);
        //console.log(dat,dat[0],dat[1],dat[2]);
        $("#count_pro").text(dat[0]);
        $("#count_ent").text(dat[1]);
        $("#count_sal").text(dat[2]);
    })
}
</script>

<?php
}
?>
