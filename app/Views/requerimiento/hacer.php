<style>
    .swal_width{
        width: 850px;
    }
</style>

<?php
//print_r($usuario);
$codigoAleatorio = stringAleatorio(6);
?>

<br>
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12 col-xl-6">
                <div class="card card-default">
                    <div class="card-header">                        
                        <h3 class="card-title">NUEVO REQUERIMIENTO (<?php echo $usuario['area']?>)</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-3">
                                <label for="fechareg">Fecha</label>
                                <input type="date" name="fechareg" id="fechareg" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <label for="codigo">Codigo</label>
                                <input type="text" name="codigo" id="codigo" class="form-control" value="<?php echo $codigoAleatorio?>" readonly>
                            </div>
                            <div class="col-sm-7 col-xl-7">
                                <label for="comentario">Comentario</label>
                                <input type="text" name="comentario" id="comentario" class="form-control" maxlength="240">
                            </div>
                        </div>

                        <div class="row  mt-3">
                            <!-- <div class="col-sm-3">
                                <input type="text" name="codigopro" id="codigopro" class="form-control numerosindecimal" placeholder="Codigo Producto">
                            </div> -->
                            <div class="col-sm-4 d-flex align-items-center">                                
                                <!-- <button class="btn btn-secondary btn-sm" id="btnAdd">AGREGAR</button> -->
                                <button class="btn btn-secondary btn-sm" id="btnModalPro" data-toggle="modal" data-target="#modalPro">Agregar Producto</button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <h5 class="text-center bg-gray">*** PRODUCTOS AGREGADOS ***</h5>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>código</th>
                                            <th>producto</th>
                                            <th>cantidad</th>
                                            <th>um</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detareq">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-right">
                                <input type="hidden" id="idreqhidden">
                                <button class="btn btn-primary" id="btnRequerimiento">REALIZAR REQUERIMIENTO</button>
                                <div id="msj"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-6">
                <div class="card card-default">
                    <div class="card-header">                        
                        <h3 class="card-title">MIS REQUERIMIENTOS (<?php echo $usuario['area']?>)</h3>
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
                                        <th>OPCION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($requerimientos){
                                            //print_r($requerimientos);
                                            foreach($requerimientos as $req){
                                                $idreq = $req['idreq'];
                                                $codigo = $req['codigo'];
                                                $fechareg = $req['fechareg'];
                                                $fecha = $req['fecha'];
                                                $estado = $req['estado'];

                                                echo "<tr>";

                                                echo "<td>$codigo</td>";
                                                echo "<td>$fecha</td>";
                                                echo "<td class='alert alert-".h_estadoReq($estado,'C')."'>".h_estadoReq($estado)."</td>";
                                                echo "<td>";
                                                    echo "
                                                    <a title='ver' class='btn btn-success btn-sm verReq' role='button' data-idreq=$idreq href=''>
                                                        <i class='fa fa-search'></i>
                                                    </a> ";
                                                    if($estado == 3){
                                                        echo "
                                                        <a title='editar' class='btn btn-info btn-sm editarReq' role='button' data-idreq=$idreq data-codigo='".$codigo."' href=''>
                                                        <i class='fa fa-edit'></i>
                                                        </a>";

                                                        echo "
                                                        <a title='eliminar' class='btn btn-danger btn-sm eliminarReq' role='button' data-idreq=$idreq data-codigo='".$codigo."' href=''>
                                                        <i class='fa fa-trash-alt'></i>
                                                        </a>";
                                                    }
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

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-default">
                    <div class="card-header">                        
                        <h3 class="card-title">INFORMACION ESTADOS</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                <div class="alert alert-<?php echo h_estadoReq(3,'C')?> alert-dismissible">
                                    <h5><i class="icon fas fa-info"></i> <?php echo h_estadoReq(3)?></h5>
                                    El requerimiento ha sido creado.
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                <div class="alert alert-<?php echo h_estadoReq(2,'C')?> alert-dismissible">
                                    <h5><i class="icon fas fa-info"></i> <?php echo h_estadoReq(2)?></h5>
                                    El requerimiento esta siendo atendido.
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                <div class="alert alert-<?php echo h_estadoReq(1,'C')?> alert-dismissible">
                                    <h5><i class="icon fas fa-info"></i> <?php echo h_estadoReq(1)?></h5>
                                    El requerimiento fue entregado y procesado.
                                </div>
                            </div>                         
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

    </div>
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

<!-- Modal Productos-->
<div class="modal fade" id="modalPro" tabindex="-1" aria-labelledby="titleModalPro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalPro">PRODUCTOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-condensed dt-responsive tablas" width="100%" id="tblProductos">
                            <thead>
                                <tr>
                                    <th>CODIGO</th>
                                    <th>NOMBRE</th>
                                    <th>STOCK</th>
                                    <th>UM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($productos){
                                    foreach($productos as $p){
                                        $idproducto  = $p['idproducto'];
                                        $codigo      = $p['codigo'];
                                        $nombre      = str_replace("'", "", $p['nombre']);
                                        $nroentradas = $p['nroentradas'];
                                        $nrosalidas  = $p['nrosalidas'];
                                        $stock       = $p['stock'];
                                        $um          = $p['um'];
                                        $stock_act   = $stock + $nroentradas - $nrosalidas;

                                        //$datos = [$idproducto,$codigo,$nombre,$stock_act,$um];
                                        $img = $p['img'];
                                        $datos = [$idproducto,$codigo];

                                        echo "<tr>";
                                        echo "<td>
                                            <a href='' title='Agregar' class='img' data-img='".$img."' data-idpro=$idproducto><i class='fa fa-image'></i></a> 
                                            <a href='' title='Agregar' class='product' data-datos='".json_encode($datos)."'>$codigo</a>                                           
                                        </td>";
                                        echo "<td>$nombre</td>";
                                        echo "<td>$stock_act</td>";
                                        echo "<td>$um</td>";
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

<script>
$(".numerosindecimal").on("keypress keyup blur",function (event) {    
    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

let items = [];
let fila = document.querySelector('#detareq');

$(function(){  
    let dtable = $("#tblReq").dataTable({
        'ordering': true,
        'order': [[1, 'desc']]
    });

    /* $('#btnAdd').on('click', function(){
        let codigopro = $('#codigopro').val().trim();
        if( codigopro.length < 1 ){
            swal_alert('Error', 'Código de producto inválido', 'warning', 'Aceptar');
            return;
        }

        $.post('requerimiento/agregarProducto', {
            codigopro:codigopro
        }, function(data){
            //console.log(data);          
            try {
                let item = JSON.parse(data);
                let existe = items.find(x => x.idproducto === item.idproducto);
                if(existe === undefined && item.idproducto != ''){
                    items.push(item);
                    dibujaFilas();
                    $('#codigopro').val("").focus();       
                }
                //console.log(items);
            } catch (error) {
                $("#msj").html(data);
                return false;
            }               
        });
    }); */

    $('#tblProductos').on('click', '.product', function(e){
        e.preventDefault();
        let datos = $(this).data('datos'),
        codigopro = datos[1].trim();
        
        $.post('requerimiento/agregarProducto', {
            codigopro:codigopro
        }, function(data){         
            try {
                let item = JSON.parse(data);
                let existe = items.find(x => x.idproducto === item.idproducto);
                if(existe === undefined && item.idproducto != ''){
                    items.push(item);
                    dibujaFilas(); 
                    swal_alert('', 'Producto agregado!', 'success', 'Aceptar');   
                }
            } catch (error) {
                $("#msj").html(data);
                return false;
            }               
        });
    });

    $('#tblProductos').on('click', '.img', function(e){
        e.preventDefault();
        let img = $(this).data('img'),
            idproducto = $(this).data('idpro');

        if(img != ''){
            let ext = img.split(".");
            ext = ext[ext.length - 1];
            if( ext == 'pdf' || ext == 'PDF' ){
                img = `<iframe src="public/images/products/${idproducto}/${img}" frameborder="0" style="width:100%; height: 500px"></iframe>`;
            }else{
                img = `<img src="public/images/products/${idproducto}/${img}" class="img-fluid"/>`;
            }           
        }else{
            img = 'SIN DATOS';
        }          
        swal_alert('', '', '', 'Aceptar', img, 'swal_width');
    });

    $('#btnRequerimiento').on('click', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnRequerimiento'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        let fecha = $("#fechareg").val(),
            codigo = $("#codigo").val(),
            comentario = $("#comentario").val(),
            idreqhidden = $("#idreqhidden").val();

        //agregando cantidades
        for(let i of items){
            let cantidad = $("#c"+i.idproducto).val();
            i.cantidad = cantidad;
        }

        let formData = new FormData;
        formData.append('fecha', fecha);
        formData.append('codigo', codigo);
        formData.append('comentario', comentario);
        formData.append('items', JSON.stringify(items));
        formData.append('idreqhidden', idreqhidden);

        let objConfirm = {
            title: 'Mensaje',
            text: "¿VAS A PROCESAR EL REQUERIMIENTO?",
            icon: 'warning',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.ajax({
                    beforeSend: function(){
                        //         
                    },
                    url: 'requerimiento/procesaRequerimiento',
                    type:"POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        //console.log(data);
                        $("#msj").html(data);           
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    });

    //OPCIONES VER, EDITAR Y ELIMINAR
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

    $("#tblReq").on('click', '.eliminarReq', function(e){
        e.preventDefault();
        let idreq = $(this).data('idreq'),
            codigo = $(this).data('codigo');

        let objConfirm = {
            title: 'Mensaje',
            text: "¿VAS A ELIMINAR EL REQUERIMIENTO "+codigo+"?",
            icon: 'info',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('requerimiento/eliminareReq', {
                    idreq: idreq
                }, function(data){
                    $("#msj").html(data);
                })
            }
        };
        swal_confirm(objConfirm);
    });

    $("#tblReq").on('click', '.editarReq', function(e){
        e.preventDefault();
        let idreq = $(this).data('idreq'),
            codigo = $(this).data('codigo');

        let objConfirm = {
            title: 'Mensaje',
            text: "¿VAS A MODIFICAR EL REQUERIMIENTO "+codigo+"?",
            icon: 'info',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('requerimiento/editarReq', {
                    idreq: idreq
                }, function(data){
                    try {
                        let datos = JSON.parse(data);

                        $("#btnRequerimiento").text("MODIFICAR REQUERIMIENTO");
                        $("#idreqhidden").val(datos.idreq);
                        $("#fechareg").val(datos.fecha);
                        $("#codigo").val(datos.codigo);
                        $("#comentario").val(datos.comentario);

                        items = [];
                        fila.innerHTML='';
                        for(let i of datos.items){
                            let entrada = {};
                            entrada.idproducto = i.idproducto;
                            entrada.codigo     = i.codigo;
                            entrada.nombre     = i.nombre;
                            entrada.stock      = Number(i.stock) + Number(i.nroentradas) - Number(i.nrosalidas);
                            entrada.um         = i.um;
                            entrada.cantidad = Number(i.cantidad);
                            items.push(entrada);
                            dibujaFilas();
                        }        
                        //console.log(datos, items);
                    } catch (error) {
                        $("#msj").html(data);
                        return false;
                    }
                    //console.log(data);
                })
            }
        };
        swal_confirm(objConfirm);
    })
});

function dibujaFilas(){
    let tr = document.createElement('tr'),
        filahtml = '';
    
    for(let i of items){
        filahtml = `<td id="${i.idproducto}"><a onclick="eliminarItem(${i.idproducto})"><i class='fas fa-trash-alt'></i></a> ${i.codigo}</td>
            <td>${i.nombre}</td>
            <td><input type="text" value="${i.cantidad}" id="c${i.idproducto}" class="numerosindecimal"></td>
            <td>${i.um}</td>`;
    }
    tr.innerHTML = filahtml;
    //console.log(tr);
    fila.appendChild(tr);

    $(".numerosindecimal").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
}

function eliminarItem(idproducto){
    let indice = items.findIndex(x => x.idproducto == idproducto);
    items.splice(indice, 1);
    $('table td[id='+idproducto+']').parent().remove();
}
</script>