<?php
//echo "<pre>";
//print_r($head);
//print_r($detalle);
//print_r($mov);
//print_r($idproducto);
//echo "</pre>";
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-gray">
            <div class="card-header">                        
                <h3 class="card-title">Cabecera <?php echo $mov?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-condensed">
                            <tr>
                                <th>Fecha</th>
                                <td><?php echo $head['fecha']?></td>
                            </tr>
                            <tr>
                                <th>Documento</th>
                                <td><?php echo $head['documento']?></td>
                            </tr>
                            <tr>
                                <th>Comentario</th>
                                <td><?php echo $head['comentario']?></td>
                            </tr>
                            <?php
                            if( isset($head['area']) ){
                            ?>
                            <tr>
                                <th>Area</th>
                                <td><?php echo $head['area']?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>       
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-gray">
            <div class="card-header">                        
                <h3 class="card-title">Detalle <?php echo $mov?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>código</th>
                                <th>producto</th>
                                <th>cantidad</th>
                                <th>um</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($detalle as $d){
                                $pintado = $idproducto == $d['idproducto'] ? 'bg-green' : '';
                                echo "<tr class='".$pintado."'>";
                                echo "<td>$d[codigo]</td>";
                                echo "<td>$d[nombre]</td>";
                                echo "<td>$d[cantidad]</td>";
                                echo "<td>$d[um]</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>