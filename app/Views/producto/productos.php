<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h3 class="m-0 text-dark">Productos</h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content bg-white">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-6 mb-4">
                <?php
                if(session('idtipousu') == 1 || session('idtipousu') == 2){
                ?>
                <a class="btn btn-primary" href="nuevo-producto" role="button">
                    Nuevo Producto
                </a>
                <?php
                }
                ?>
            </div>
            <div class="col-sm-6 mb-2">
                <input type="text" name="" id="desc" class="form-control" placeholder="BUSCA POR DESCRIPCION">
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed dt-responsive" width="100%" id="tblProductos">
                    <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>
                        <th>CATEGORIA</th>
                        <th>STOCK</th>
                        <th>UM</th>
                        <th>OPCION</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>


<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="titleModalCategoria" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalCategoria">Detalle Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-condensed">
                            <tr>
                                <th>Nombre</th>
                                <td class="tdNombre"></td>
                            </tr>
                            <tr>
                                <th>Descripción</th>
                                <td class="tdDescripcion"></td>
                            </tr>
                            <tr>
                                <th>Código</th>
                                <td class="tdCodigo"></td>
                            </tr>
                            <tr>
                                <th>Categoría</th>
                                <td class="tdCategoria"></td>
                            </tr>
                            <tr>
                                <th>U Medida</th>
                                <td class="tdMedida"></td>
                            </tr>
                            <tr>
                                <th>Stock</th>
                                <td class="tdStock"></td>
                            </tr>
                            <tr>
                                <th>Ubicación</th>
                                <td class="tdUbicacion"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tdImagen" align="center">

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>              
            </div>
        </div>
    </div>
</div>


<script>
$(function(){
    var $table = $('#tblProductos').dataTable({        
        "ajax":{
            "url": 'producto/listProductosDT',
            "dataSrc":"",
            "type": "POST",
            "data": {"desc": function() { return $('#desc').val() } },
            "complete": function(xhr, responseText){
                //console.log(xhr);
                //console.log(xhr.responseText); //*** responseJSON: Array[0]
            }
        },
        "columns":[
            {"data": "codigo",
                "mRender": function (data, type, row) {
                    return "<a title='detalle' class='btn detalle' data-fila='"+JSON.stringify(row).replaceAll("'","")+"'>"+data+"</a>";
                }
            },
            {"data": "nombre"},
            {"data": "categoria"},
            {"data": "stock", render: function ( data, type, row, meta ) {
                //console.log(row)
                return parseInt(data) + parseInt(row.nroentradas) - parseInt(row.nrosalidas) 
                }
            },
            {"data": "um"},
            {"data": "idproducto",
                "mRender": function (data, type, row) {
                    <?php
                    if(session('idtipousu') == 1 || session('idtipousu') == 2){
                    ?>
                    return "<a title='editar' class='btn btn-success btn-sm' role='button' href='edit-producto-"+data+"'><i class='fa fa-edit'></i></a> <a title='imagen' class='btn btn-success btn-sm' role='button' href='imagen-producto-"+data+"'><i class='fa fa-image'></i></a> <a href='' title='eliminar' class='btn btn-danger btn-sm deleteProducto' idpro="+data+" codigo='"+row.codigo+"' role='button'><i class='fa fa-trash-alt'></i></a>";
                    <?php
                    }else{
                    ?>
                    return '-';
                    <?php
                    }
                    ?>
                }
            }
        ],
        "aaSorting": [[ 1, "asc" ]],
        "pageLength": 25
    });

    $("#desc").on('keyup', function(e){
        //console.log($(this).val());
        $('#tblProductos').DataTable().ajax.reload()
    });

    $("#tblProductos").on('click', '.deleteProducto', function(e){
        e.preventDefault();
        let idproducto = $(this).attr('idpro'),
            codigo = $(this).attr('codigo');

        let objConfirm = {
            title: '¿Estás seguro?',
            text: "Vas a eliminar el producto: "+codigo,
            icon: 'warning',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('producto/deleteProducto', {
                    idproducto:idproducto
                }, function(data){
                    //console.log(data);
                    if(data == "eliminado"){
                        location.reload();
                    }else{
                        swal_alert('No puedes eliminar el producto', data, 'info', 'Aceptar');
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
    });

    $("#tblProductos").on('click', '.detalle', function(e){
        let fila = $(this).data('fila');
        //console.log(fila);

        $(".tdNombre").text(fila.nombre);
        $(".tdDescripcion").text(fila.descripcion);
        $(".tdCodigo").text(fila.codigo);
        $(".tdCategoria").text(fila.categoria);
        $(".tdMedida").text(fila.um);
        $(".tdStock").text(parseInt(fila.stock) + parseInt(fila.nroentradas) - parseInt(fila.nrosalidas));
        $(".tdUbicacion").text(fila.ubicacion);
        let img = fila.img;
        if(img != null){
            let ext = img.split(".");
            ext = ext[ext.length - 1];
            //console.log(ext);
            if( ext == 'pdf' || ext == 'PDF' ){
                img = `<iframe src="public/images/products/${fila.idproducto}/${img}" frameborder="0" style="width:100%; height: 500px"></iframe>`;
            }else{
                img = `<img src="public/images/products/${fila.idproducto}/${img}" class="img-fluid"/>`;
            }           

            $(".tdImagen").html(img);
        }else{
            $(".tdImagen").html('');
        }          
        
        $("#modalDetalle").modal();
    });
})
</script>