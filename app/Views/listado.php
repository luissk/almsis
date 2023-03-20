<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h3>Artículos</h3>
        </div>
        <div class="col-sm-6 text-right">
            <a href="<?php echo base_url('crear')?>" class="btn btn-light">Crear artículo</a>
        </div>
        <div class="col-sm-12 table-responsive">
            <table class="table table-light table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>DESCRIPCION</th>
                        <th>PRECIO</th>
                        <th>STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($libros as $libro){
                        echo "
                        <tr>
                            <td>$libro[id]</td>
                            <td>$libro[descripcion]</td>
                            <td>$libro[precio]</td>
                            <td>$libro[stock]</td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="msj"></div>

<script>
/*let formData = new FormData;
formData.append('nombre', 'Luis Calderón');

var xhr = new XMLHttpRequest();

xhr.open('POST', 'http://localhost/ci4/public/articulos/crear', true);
xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
xhr.onload = function(){
    console.log(xhr.responseText);
    document.querySelector('#msj').innerHTML = xhr.responseText;
}
xhr.send(formData);*/
</script>