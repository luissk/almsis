<?php
if(isset($producto) && $producto){
    //echo "<pre>"; print_r($producto); echo "</pre>";
    $idproducto  = $producto['idproducto'];
    $codigo      = $producto['codigo'];
    $nombre      = $producto['nombre'];
    $descripcion = $producto['descripcion'];
    $idcategoria = $producto['idcategoria'];
    $stock       = $producto['stock'];
    $min         = $producto['min'];
    $max         = $producto['max'];
    $ubicacion   = $producto['ubicacion'];
    $idum        = $producto['idum'];

    $title_head = "EDITAR PRODUCTO";
    $btnProducto = "MODIFICAR PRODUCTO";
}else{
    $idproducto  = "";
    $codigo      = "";
    $nombre      = "";
    $descripcion = "";
    $idcategoria = "";
    $stock       = "";
    $min         = "";
    $max         = "";
    $ubicacion   = "";
    $idum        = "";

    $title_head = "NUEVO PRODUCTO";
    $btnProducto = "GUARDAR PRODUCTO";
}

?>

<section class="content bg-white">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-12">
                
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $title_head?> &nbsp;<a href="productos" class="btn btn-danger btn-sm" role="button">Regresar</a></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form" enctype="multipart/form-data" id="frmProducto">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required value="<?php echo $codigo?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo $nombre?>">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="categoria">Categoría</label>
                                <select name="categoria" id="categoria" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    if( $categorias ){
                                        foreach($categorias as $c){
                                            $idcate    = $c['idcategoria'];
                                            $categoria = $c['categoria'];
                                            $sel_cate  = $idcate == $idcategoria ? 'selected' : '';
                                            echo "<option value=$idcate $sel_cate>$categoria</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="Medida">Medida</label>
                                <select name="medida" id="medida" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    if( $medidas ){
                                        foreach($medidas as $m){
                                            $idume = $m['idum'];
                                            $um   = $m['um'];
                                            $sel_um = $idume == $idum ? 'selected' : '';
                                            echo "<option value=$idume $sel_um>$um</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="stock">Stock Ini</label>
                                <input type="text" class="form-control" id="stock" name="stock" required value="<?php echo $stock?>">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="min">Mínimo</label>
                                <input type="text" class="form-control" id="min" name="min" required value="<?php echo $min?>">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="max">Máximo</label>
                                <input type="text" class="form-control" id="max" name="max" required value="<?php echo $max?>">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="ubicacion">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required value="<?php echo $ubicacion?>">
                            </div>
                            <div class="form-group col-sm-9">
                                <label for="descripcion">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="4" class="form-control" required><?php echo $descripcion?></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer text-right">
                        <input type="hidden" name="idproducto" id="idproducto" value="<?php echo $idproducto?>">
                        <button type="submit" class="btn btn-primary" id="btnProducto"><?php echo $btnProducto?></button>
                    </div>
                    </form>
                </div>
                <!-- /.card -->

            </div>
        </div>
    </div>
</section>


<script>
$(function(){
    $("#frmProducto").on("submit", function(e){
        e.preventDefault();
        let btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>',
            textBtn = $('#btnProducto').text();
        $("#btnProducto").attr('disabled', 'disabled');
        $("#btnProducto").html(`${btnHTML} Guardando`);
        $.ajax({
            method: 'POST',
            url: 'producto/saveUpdateProducto',
            data: $(this).serialize(),
            success: function(datos){
                console.log(datos);
                let data = JSON.parse(datos);
                if(data.err != ""){
                    swal_alert('Atención', data.err, 'info', 'Aceptar');
                    $("#btnProducto").removeAttr('disabled');
                    $("#btnProducto").text(textBtn);
                }else{                    
                    location.href='productos';
                }
            }
        });
    })
});
</script>