<?php
//echo "$emisor - $receptor";
foreach($chat as $ch){
    $idchat  = $ch['idchat'];
    $mensaje = $ch['mensaje'];
    $fecha   = $ch['fecha'];
    $nomemi  = $ch['nomemi'];
    $nomrec  = $ch['nomrec'];
    $emi     = $ch['emisor'];
?>
    <div class="direct-chat-msg <?php echo $emisor == $emi ? '' : 'right'?>">
        <div class="direct-chat-info clearfix">
            <span class="direct-chat-name float-<?php echo $emisor == $emi ? 'left' : 'right'?>"><?php echo $nomemi?></span>
            <span class="direct-chat-timestamp float-<?php echo $emisor == $emi ? 'right' : 'left'?>"><?php echo $fecha?></span>
        </div>
        <img class="direct-chat-img" src="public/adminlte/dist/img/user2-160x160.jpg" alt="message user image">
        <div class="direct-chat-text <?php echo $emisor == $emi ? '' : 'text-info'?>">
            <?php echo $mensaje?>
        </div>
    </div>
<?php
}
?>