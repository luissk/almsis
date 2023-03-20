<style>
.imagep {
  flex-basis: 40%;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px solid #ccc;
  height: 492px;
  /* background-color: gray; */
}
.imagep .image-wrap {
  background-size: 100%;
  background-position: center;
  background-repeat: no-repeat;
}
.imagep .image-wrap img {
  max-width: 100%;
  max-height: 490px;
}
</style>

<section class="content bg-white">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="m-0 text-dark">Producto: <?php echo $producto['nombre']?></h3>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Cargar Imagen o PDF</h3>
                    </div>
                    <div class="card-body">
                        <form id="upload_image" method="post" enctype="multipart/form-data">
                            <input type="file" id="image" name="image" required>
                            <input type="hidden" name="idproducto" value="<?php echo $producto['idproducto']?>">
                            <div class="xprogress"></div>
                            <button type="submit" class="btn btn-primary mt-3" id="btnUpload">Subir imagen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
if($producto['img'] != null){
    $ruta = "public/images/products/".$producto['idproducto']."/".$producto['img']."";
    //https://docs.google.com/gview?url=

    $ext = explode(".", $ruta);
    $ext = $ext[count($ext) - 1];
?>
<section class="content bg-white">
    <div class="container-fluid mt-3">
        <div class="row py-3">
            <div class="col-sm-6">
                <?php
                if($ext == 'pdf' || $ext == 'PDF'){
                    echo '<iframe src="'.$ruta.'" frameborder="0" style="width:100%; height: 500px"></iframe>';
                }else{
                    echo '
                    <div class="imagep">
                        <div class="image-wrap" data-src="'.$ruta.'" id="image-wrap">
                            <img src="'.$ruta.'" alt="" id="img"/>
                        </div>
                    </div>
                    ';
                }
                ?>                
            </div> 
            <div class="col-sm-6">
                <a href="delete-image-<?php echo $producto['idproducto']?>" class="btn btn-danger" role="button"><i class="fas fa-trash"></i> ELIMINAR IMAGEN</a>
            </div>           
        </div>
    </div>
</section>
<?php
}
?>

<script>
$(function(){
    $("#image").on('change', function(e){
        let tipos = ['image/jpeg','image/jpg','image/png','application/pdf'];
        let file = this.files[0];
        let tipofile = file.type;
        let sizefile = file.size;

        if(!tipos.includes(tipofile)){
            swal_alert('Atención', 'IMAGEN SOLO EN (JPEG|JPG|PNG|PDF)', 'info', 'Aceptar');
            $(this).val('');
            return false;
        }
        if(sizefile >= 4192256){
            swal_alert('Atención', 'LA IMAGEN NO DEBE SER MAYOR A 4MB', 'info', 'Aceptar');
            $(this).val('');
            return false;
        }
    });

    $('#upload_image').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btnUpload").attr('disabled', 'disabled');

        $.ajax({
            type:'POST',
            url: 'producto/processImage',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log("success");
                console.log(data);
                if(data == 'OK'){
                    location.reload();
                }else{
                    swal_alert('Atención', data, 'info', 'Aceptar');
                }
                $("#image").val("");
                $("#btnUpload").removeAttr('disabled');
                $(".xprogress").html("");
            },
            error: function(data){
                console.log("error");
                console.log(data);
            },
            xhr: function() {
              var xhr = new window.XMLHttpRequest();

              xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                  var percentComplete = evt.loaded / evt.total;
                  percentComplete = parseInt(percentComplete * 100);
                  console.log(percentComplete);
                  $(".xprogress").html("Progreso: "+ percentComplete +"%");

                  if (percentComplete === 100) {
                    
                  }

                }
              }, false);

              return xhr;
            },

        });

    }));

    <?php if($producto['img'] != null && $ext != 'pdf' && $ext != 'PDF' ){?>
    //preview img
    const img = document.querySelector('#img'),
        wraper = document.querySelector('#image-wrap'),
        src_wraper = wraper.getAttribute('data-src');

    img.addEventListener('mousemove', function (e) {
        let src = img.getAttribute('src');
        wraper.style.backgroundImage = "url('"+src_wraper+"')";

        let width = wraper.offsetWidth;
        let height = wraper.offsetHeight;
        let mouseX = e.offsetX;
        let mouseY = e.offsetY;
        
        let bgPosX = (mouseX / width * 100);
        let bgPosY = (mouseY / height * 100);
        
        wraper.style.backgroundPosition = `${bgPosX}% ${bgPosY}%`;
        wraper.style.backgroundSize = "220%";
        img.style.opacity = "0";
    });

    img.addEventListener('mouseleave', function () {
        wraper.style.backgroundPosition = "center";
        wraper.style.backgroundSize = "100%";
        img.style.opacity = "1";
    });

    img.addEventListener('touchmove', function (e) {
        e.preventDefault();
        let src = img.getAttribute('src');
        wraper.style.backgroundImage = "url('"+src_wraper+"')";

        let width = wraper.offsetWidth;
        let height = wraper.offsetHeight;
        let mouseX = e.touches[0].clientX;
        let mouseY = e.touches[0].clientY;
        
        let bgPosX = (mouseX / width * 100);
        let bgPosY = (mouseY / height * 100);
        
        wraper.style.backgroundPosition = `${bgPosX}% ${bgPosY}%`;
        wraper.style.backgroundSize = "220%";
        img.style.opacity = "0";
    });

    img.addEventListener('touchend', function (e) {
        wraper.style.backgroundPosition = "center";
        wraper.style.backgroundSize = "100%";
        img.style.opacity = "1";
    });

    <?php }?>
});
</script>