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
                        <h3 class="card-title"><a href="salidas"><i class="fas fa-arrow-left"></i></a> NUEVA SALIDA</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 col-xl-2">
                                <label for="fechareg">Fecha</label>
                                <input type="date" name="fechareg" id="fechareg" class="form-control">
                            </div>
                            <div class="col-sm-3 col-xl-2">
                                <label for="documento">Doc. (<a id="btnSearchReq"><i class="fa fa-search"></i> Requerimiento</a>)</label>
                                <input type="text" name="documento" id="documento" maxlength="11" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase()">
                            </div>
                            <div class="col-sm-6">
                                <label for="comentario">Comentario</label>
                                <input type="text" name="comentario" id="comentario" maxlength="200" class="form-control">
                            </div>
                            <div class="col-sm-4 mt-2">
                                <label for="area">Area</label>
                                <select name="area" id="area" class="form-control select2">
                                    <option value="">Seleccione</option>
                                    <?php
                                    if($areas){
                                        foreach($areas as $a){
                                            $idar      = $a['idarea'];
                                            $area      = $a['area'];
                                            $nombres   = $a['nombres'];
                                            $apellidos = $a['apellidos'];
                                            $dni       = $a['dni'];
                                            echo "<option value='$idar' encargado='$nombres $apellidos' dni='$dni'>$area</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-8 d-flex align-items-center" id="arealabel"></div>
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
                                    <tbody id="detasalida">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-primary" id="btnSalida">GUARDAR</button>
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

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <input type="text" id="codigospro" class="form-control" placeholder="FILTRA POR VARIOS CODIGOS">                                      
                            </div>
                            <div class="col-sm-3">
                                <button id="btnFiltraCodigos" class="btn btn-default">FILTRAR</button>
                            </div>
                        </div>

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

<div id="msj"></div>

<script>
$(function(){
    $(".select2").select2({'theme':'bootstrap4'});
    
    $("#area").on('change', function(){
        let encargado = $("#area option:selected").attr('encargado'),
            dni = $("#area option:selected").attr('dni');
        
        $("#arealabel").text('');
        if( $(this).val() != '' )
            $("#arealabel").text(`${encargado} - ${dni}`);
    });

    $("#btnFiltraCodigos").on('click', () => {
        let codigos = $("#codigospro").val().trim();

        if(codigos.length > 0){
            cods = codigos.split(" ").join("|");
            console.log(cods, codigos.split(" ").length);
            $("#tblProductos").DataTable().search(
                cods, true, false
            ).draw();
        }
    });
    
    //REQUERIMIENTO
    $('#btnSearchReq').on('click', function(e){
        let codigoReq = $('#documento').val().trim();
        $.post('requerimiento/buscarReqSalida', {
            codigoReq:codigoReq
        }, function(data){
            try {
                let datos = JSON.parse(data);

                $("#area option").removeAttr('selected').filter('[value="'+datos.idarea+'"]').attr('selected', true);
                $("#area").trigger('change');

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
    });
    //FIN REQUERIMIENTO
});

let items = [], 
    fila = document.querySelector('#detasalida');

$("#tblProductos").on('click', ".product", function(e){
    let datos = $(this).data('datos');
    //console.log(datos);
    let idproducto = datos[0],
        codigo = datos[1],
        nombre = datos[2],
        stock = datos[3],
        um = datos[4];

    if( stock === 0 ){
        swal_alert('Alerta', 'Stock agotado', 'warning', 'Aceptar');
        return;
    }

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
    let tr = document.createElement('tr'),
        filahtml = '';
    
    for(let i of items){
        filahtml = `<td id="${i.idproducto}"><a onclick="eliminarItem(${i.idproducto})"><i class='fas fa-trash-alt'></i></a> ${i.codigo}</td>
            <td>${i.nombre}</td>
            <td><input type="text" value="${i.cantidad}" id="c${i.idproducto}" class="numerocondecimal"></td>
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

$("#btnSalida").on('click', function(e){
    e.preventDefault();
    
    let btn = document.querySelector('#btnSalida'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} Guardando`;
    
    let fechareg = $("#fechareg").val(),
        documento = $("#documento").val(),
        comentario = $("#comentario").val(),
        area = $("#area").val();
    
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
    }else if(area == ''){
        swal_alert('Alerta', 'Seleccione una área', 'info', 'Aceptar');
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }else if(items.length == 0){
        swal_alert('Alerta', 'Productos sin agregar', 'info', 'Aceptar');
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }else{
        //agregando cantidades
        for(let i of items){
            let cantidad = $("#c"+i.idproducto).val();
            if(cantidad <= 0){
                swal_alert('Atención', `Cantidad inválida del producto ${i.codigo}`, 'info', 'Aceptar');
                btn.removeAttribute('disabled');
                btn.innerHTML = txtbtn;
                return;
            }            
            if(cantidad > i.stock){
                swal_alert('Atención', `Cantidad sobrepasa al stock del producto ${i.codigo}`, 'info', 'Aceptar');
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
        formData.append('area', area);
        formData.append('items', JSON.stringify(items));

        let objConfirm = {
            title: 'REGISTRAR SALIDA',
            text: "¿Vas a registrar la salida?",
            icon: 'warning',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.ajax({
                    beforeSend: function(){
                        //         
                    },
                    url: 'salida/saveSalida',
                    type:"POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        if(data == 1){
                            btn.setAttribute('disabled', 'disabled');
                            alert('SALIDA EXITOSA..!')
                            location.reload();
                        }else{
                            swal_alert('Atención', data, 'warning', 'Aceptar');
                        }                 
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }    
});
</script>