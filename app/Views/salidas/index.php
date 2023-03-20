<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h4 class="m-0 text-dark">Salidas</h4>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid bg-white py-2">
        <div class="row">
            <div class="col-sm-6">
                <a class="btn btn-primary" href="nueva-salida" role="button">
                    Nueva Salida
                </a>
            </div>
            <div class="col-sm-6">
                <input type="text" name="" id="desc" class="form-control" placeholder="BUSCA POR PRODUCTO">
            </div>
        </div>
    </div>
    <div class="container-fluid bg-white mt-2 py-2">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed dt-responsive" width="100%" id="tblSalidas">
                    <thead>
                    <tr>
                        <th>DOCUMENTO</th>
                        <th>FECHA</th>
                        <th>AREA</th>
                        <th>COMENTARIO</th>
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

<script>
$(function(){
    var $table = $('#tblSalidas').dataTable({ 
        "ajax":{
            "url": 'salida/listSalidasDT',
            "dataSrc":"",
            "type": "POST",
            "data": {"desc": function() { return $('#desc').val() } },
            "complete": function(xhr, responseText){
                //console.log(xhr);
                //console.log(xhr.responseText); //*** responseJSON: Array[0]
            }
        },
        "columns":[
            {"data": "documento"},
            {"data": "fecha"},
            {"data": "area"},
            {"data": "comentario"},
            {"data": "idsalida",
                "mRender": function (data, type, row) {
                    return "<a title='editar' class='btn btn-success btn-sm' role='button' href='edit-salida-"+data+"'><i class='fa fa-edit'></i></a> <a href='' title='eliminar' class='btn btn-danger btn-sm deleteSalida' idsal="+data+" doc='"+row.documento+"' role='button'><i class='fa fa-trash-alt'></i></a>";
                }
            }
        ],
        "aaSorting": [[ 4, "desc" ]],
        "pageLength": 25
    });

    $("#desc").on('keyup', function(e){
        //console.log($(this).val());
        $('#tblSalidas').DataTable().ajax.reload()
    });

    $("#tblSalidas").on('click', '.deleteSalida', function(e){
        e.preventDefault();
        let idsalida = $(this).attr('idsal'),
            doc = $(this).attr('doc');

        let objConfirm = {
            title: '¿Estás seguro?',
            text: "Vas a eliminar la salida: "+doc,
            icon: 'warning',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('salida/deleteSalida', {
                    idsalida:idsalida
                }, function(data){
                    //console.log(data);
                    if(data == "eliminado"){
                        location.reload();
                    }else{
                        //swal_alert('No puedes eliminar la entrada', data, 'info', 'Aceptar');
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
    });

})
</script>