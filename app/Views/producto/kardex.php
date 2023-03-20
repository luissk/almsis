<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark">Kardex</h4>
            </div><!-- /.col -->
            <div class="col-sm-6 text-right">
                <a href="detalle-en-excel" target='_blank' class="btn btn-primary">Detalle en excel</a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content bg-white">
    <div class="container-fluid py-4">
        <div class="row">
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
                        <th>INICIAL</th>
                        <th>ENTRADAS</th>
                        <th>SALIDAS</th>
                        <th>STOCK</th>
                        <th>NOMBRE</th>
                        <th>UM</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>



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
                    return "<a title='ver movimientos' class='btn detalle' href='movimientos-"+row.idproducto+"' target='_blank' >"+data+"</a>";
                }
            },
            {"data": "stock"},
            {"data": "nroentradas"},
            {"data": "nrosalidas"},
            {"data": "stock", render: function ( data, type, row, meta ) {
                //console.log(row)
                return Number(data) + Number(row.nroentradas) - Number(row.nrosalidas)
                }
            },
            {"data": "nombre"},
            {"data": "um"},
            /* {"data": "idproducto",
                "mRender": function (data, type, row) {
                    return "<a title='editar' class='btn btn-success btn-sm' role='button' href='edit-producto-"+data+"'><i class='fa fa-edit'></i></a> <a title='imagen' class='btn btn-success btn-sm' role='button' href='imagen-producto-"+data+"'><i class='fa fa-image'></i></a> <a href='' title='eliminar' class='btn btn-danger btn-sm deleteProducto' idpro="+data+" codigo='"+row.codigo+"' role='button'><i class='fa fa-trash-alt'></i></a>";
                }
            } */
        ],
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
});
</script>