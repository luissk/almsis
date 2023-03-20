<?php
//echo "<pre>";print_r($requerimientos);echo "</pre>";
?>

<br>
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12 col-xl-12">
                <div class="card card-default">
                    <div class="card-header">                        
                        <h3 class="card-title">LISTA DE REQUERIMIENTOS</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-condensed dt-responsive" width="100%" id="tblReq">
                                    <thead>
                                    <tr>
                                        <th>CODIGO</th>
                                        <th>FECHA</th>
                                        <th>ESTADO</th>
                                        <th>AREA</th>
                                        <th>F ATENDIENDO</th>
                                        <th>F PROCESADO</th>
                                        <th>OPCION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($requerimientos){
                                            //print_r($requerimientos);
                                            foreach($requerimientos as $req){
                                                $idreq         = $req['idreq'];
                                                $codigo        = $req['codigo'];
                                                $fechareg      = $req['fechareg'];
                                                $fecha         = $req['fecha'];
                                                $estado        = $req['estado'];
                                                $area          = $req['area'];
                                                $fechaatencion = $req['fechadespacho'];
                                                $fechaproceso  = $req['fechaprocesado'];

                                                echo "<tr>";

                                                echo "<td>$codigo</td>";
                                                echo "<td>$fecha</td>";
                                                echo "<td class='alert alert-".h_estadoReq($estado,'C')."'>".h_estadoReq($estado)."</td>";                                                
                                                echo "<td>$area</td>";
                                                echo "<td>$fechaatencion</td>";
                                                echo "<td>$fechaproceso</td>";
                                                echo "<td>";
                                                    echo "
                                                    <a title='Ver' class='btn btn-success btn-sm verReq' role='button' data-idreq=$idreq href=''>
                                                        <i class='fa fa-search'></i>
                                                    </a> ";
                                                    echo "
                                                    <a title='Cambiar Estado' class='btn btn-info btn-sm updStatusReq' role='button' data-idreq=$idreq data-codigo='".$codigo."' href=''>
                                                        <i class='fa fa-arrow-circle-right'></i>
                                                    </a> ";
                                                echo "</td>";

                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="msj"></div>
</div>


<!-- Modal Detalle-->
<div class="modal fade" id="modalReq" tabindex="-1" aria-labelledby="titleModalReq" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalReq">DETALLE REQUERIMIENTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleReq"></div>
        </div>
    </div>
</div>

<!-- Modal Estado-->
<div class="modal fade" id="modalReqEstado" tabindex="-1" aria-labelledby="titleModalReqEstado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalReqEstado">Cambiar estado requerimiento: <span id="codreq"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="estadoReq">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Estados</label>
                        <select name="cboEstado" id="cboEstado" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="1"><?php echo h_estadoReq(1)?></option>
                            <option value="2"><?php echo h_estadoReq(2)?></option>
                            <option value="3"><?php echo h_estadoReq(3)?></option>
                        </select>
                    </div>
                    <div class="col-sm-6 d-flex align-items-end">
                        <input type="hidden" id="txtIdReq">
                        <button class="btn btn-danger" id="btnAplicar">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
$(function(){  
    let dtable = $("#tblReq").dataTable({
        'ordering': true,
        'order': [[1, 'desc']]
    });

    //OPCIONES VER Y ESTADO
    $("#tblReq").on('click', '.verReq', function(e){
        e.preventDefault();
        let idreq = $(this).data('idreq');
        $("#detalleReq").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO...');
        $("#modalReq").modal();
        $.post('requerimiento/modalDetalleReq', {
            idreq: idreq
        }, function(data){
            $("#detalleReq").html(data);
        })
    });

    $("#tblReq").on('click', '.updStatusReq', function(e){
        e.preventDefault();
        let idreq = $(this).data('idreq');
        let codigo = $(this).data('codigo');
        $("#codreq").text(codigo);
        $("#txtIdReq").val(idreq);
        $("#modalReqEstado").modal();

        //console.log(idreq, codigo);
    });

    $("#btnAplicar").on('click', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnAplicar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        let idreq = $("#txtIdReq").val(),
            cboEstado = $("#cboEstado").val();
        $.post('requerimiento/cambiarEstado', {
            idreq: idreq, cboEstado: cboEstado
        }, function(data){
            //console.log(data);
            $("#msj").html(data);

            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        })

    })
});
</script>