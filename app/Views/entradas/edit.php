<?php
//echo "<pre>";
//print_r($entrada);
//print_r($detalle);
//echo "</pre>";
$identrada = $entrada['identrada'];
$fecha = $entrada['fecha'];
$fechareg = $entrada['fechareg'];
$documento = $entrada['documento'];
$comentario = $entrada['comentario'];
?>

<style>
    a{cursor: pointer;}
</style>
<br>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-default">
                    <div class="card-header">                        
                        <h3 class="card-title"><a href="entradas"><i class="fas fa-arrow-left"></i></a> MODIFICAR ENTRADA</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 col-xl-2">
                                <label for="fechareg">Fecha</label>
                                <input type="date" name="fechareg" id="fechareg" class="form-control" value="<?php echo $fecha?>">
                            </div>
                            <div class="col-sm-3 col-xl-2">
                                <label for="documento">Documento</label>
                                <input type="text" name="documento" id="documento" maxlength="10" class="form-control" value="<?php echo $documento?>">
                            </div>
                            <div class="col-sm-4">
                                <label for="comentario">Comentario</label>
                                <input type="text" name="comentario" id="comentario" maxlength="200" class="form-control" value="<?php echo $comentario?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <h5 class="text-center bg-gray">*****AGREGADOS*****</h5>
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
                                    <tbody id="detaentrada">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-right">
                                <input type="hidden" name="identrada" id="identrada" value="<?php echo $identrada?>">
                                <button class="btn btn-primary" id="btnEntrada">GUARDAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Productos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
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
                                        $nombre      = $p['nombre'];
                                        $nroentradas = $p['nroentradas'];
                                        $nrosalidas  = $p['nrosalidas'];
                                        $stock       = $p['stock'];
                                        $um          = $p['um'];
                                        $stock_act   = $stock + $nroentradas - $nrosalidas;

                                        $datos = [$idproducto,$codigo,$nombre,$stock_act,$um];

                                        echo "<tr>";
                                        echo "<td><a class='product' data-datos='".json_encode($datos)."'>$codigo</a></td>";
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
</section>

<script>
var items = <?php echo json_encode($detalle)?>, 
    fila = document.querySelector('#detaentrada');

$("#tblProductos").on('click', ".product", function(e){
    let datos = $(this).data('datos');
    //console.log(datos);
    let idproducto = datos[0],
        codigo = datos[1],
        nombre = datos[2],
        stock = datos[3],
        um = datos[4];

    let entrada = {};
    entrada.idproducto = idproducto;
    entrada.codigo     = codigo;
    entrada.nombre     = nombre;
    entrada.stock      = stock;
    entrada.um         = um;
    entrada.cantidad = 1;

    let existe = items.find(x => x.idproducto === idproducto);
    if(existe === undefined && idproducto != ''){
        items.push(entrada);
        dibujaFilas();         
    }        
    //console.log(items);
});

function dibujaFilas(){
    for(let i of items){
        let existe_id_td = $("#detaentrada").find("#"+i.idproducto).length;//para verificar si la fila ya esta dibujada

        if(existe_id_td == 0){
            let tr = document.createElement('tr'),
            filahtml = '';
            filahtml = `<td id="${i.idproducto}"><a onclick="eliminarItem(${i.idproducto})"><i class='fas fa-trash-alt'></i></a> ${i.codigo}</td>
                <td>${i.nombre}</td>
                <td><input type="text" value="${i.cantidad}" id="c${i.idproducto}" class="numerocondecimal"></td>
                <td>${i.um}</td>`;
            tr.innerHTML = filahtml;
            //console.log(tr);
            fila.appendChild(tr);
        }
    }

    $(".numerosindecimal").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(".numerocondecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
}

function eliminarItem(idproducto){
    let indice = items.findIndex(x => x.idproducto == idproducto);
    items.splice(indice, 1);
    $('table td[id='+idproducto+']').parent().remove();
}

$("#btnEntrada").on('click', function(e){
    e.preventDefault();
    
    let btn = document.querySelector('#btnEntrada'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} Guardando`;
    
    let fechareg = $("#fechareg").val(),
        documento = $("#documento").val(),
        comentario = $("#comentario").val(),
        identrada = $("#identrada").val();
    
    if(fechareg == ''){
        swal_alert('Alerta', 'Seleccione una fecha', 'info', 'Aceptar');
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }else if(documento.trim() == ''){
        swal_alert('Alerta', 'Ingrese un documento', 'info', 'Aceptar');
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }else if(comentario.trim() == ''){
        swal_alert('Alerta', 'Ingrese un comentario', 'info', 'Aceptar');
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }else if(items.length == 0){
        swal_alert('Alerta', 'Productos sin agregar', 'info', 'Aceptar');
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }else{
        for(let i of items){
            let cantidad = $("#c"+i.idproducto).val();
            if(cantidad <= 0){
                swal_alert('Atención', `Cantidad inválida del producto ${i.codigo}`, 'info', 'Aceptar');
                btn.removeAttribute('disabled');
                btn.innerHTML = txtbtn;
                return;
            }
            i.cantidad = cantidad;
        }
        //console.log(items);

        let formData = new FormData;
        formData.append('fechareg', fechareg);
        formData.append('documento', documento);
        formData.append('comentario', comentario);
        formData.append('identrada', identrada);
        formData.append('items', JSON.stringify(items));

        let objConfirm = {
            title: 'MODIFICAR ENTRADA',
            text: "¿Vas a modificar la entrada?",
            icon: 'warning',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.ajax({
                    beforeSend: function(){
                        //         
                    },
                    url: 'entrada/updateEntrada',
                    type:"POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        console.log(data);
                        alert('ENTRADA EXITOSA..!')
                        location.reload();                       
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }    
});

dibujaFilas();
</script>