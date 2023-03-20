<style>
    .msjnuevos{
        display: none;
    }
</style>
<?php
//print_r($usuarios);
?>
<br>
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12 col-xl-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Chat <span id="contacto"></span></h3>
                        <div class="card-tools">
                            <a type="button" class="btn btn-tool" title="Eliminar mensajes" id="btnDeleteChat" >
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>                    
                    </div>

                    <div class="card-body">
                        <div class="direct-chat-messages" id="chatmessages">
                            <!-- <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                                    <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                </div>
                                <img class="direct-chat-img" src="public/adminlte/dist/img/user2-160x160.jpg" alt="message user image">
                                <div class="direct-chat-text">
                                    Is this template really for free? That's unbelievable!
                                </div>
                            </div>

                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                                    <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                </div>
                                <img class="direct-chat-img" src="public/adminlte/dist/img/user2-160x160.jpg" alt="message user image">
                                <div class="direct-chat-text text-info">
                                    You better believe it!
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <div class="card-footer">
                        <form id="frmChat">
                            <div class="input-group">
                                <input type="text" name="message" id="message" placeholder="Escriba su mensaje..." class="form-control" maxlength="200">
                                <span class="input-group-append">
                                    <button class="btn btn-primary" id="btnChat">Enviar</button>
                                    <input type="hidden" name="idusuario" id="idusuario">
                                </span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <div class="col-sm-12 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-widget="remove">
                            <i class="fa fa-times"></i>
                        </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php
                            if($usuarios){
                                foreach($usuarios as $usu){
                                    $idusuario = $usu['idusuario'];
                                    $usuario   = $usu['usuario'];
                                    $nombres   = $usu['nombres'];
                                    $tipo      = $usu['tipo'];
                                    $area      = $usu['area'];
                            ?>
                            <li class="item">
                                <div class="product-img">
                                    <img src="public/adminlte/dist/img/user2-160x160.jpg" alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title chat" data-idusuario="<?php echo $idusuario?>" data-nombres="<?php echo $nombres?>">
                                        <?php echo $nombres?>
                                        <span class="badge badge-warning float-right msjnuevos" id="msjnuevos-<?php echo $idusuario?>">Nuevo</span>
                                    </a>
                                    <span class="product-description">
                                        <?php echo $tipo == 'admin' ? 'admin' : $area ?>
                                    </span>
                                </div>
                            </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<div id="msj"></div>

<script>
let interval = '',
    intervalMsj = '';

let containerChat = document.querySelector('#chatmessages'), contScroll = 0;

$(function(){
    $('.chat').on('click', function(e){
        e.preventDefault();
        let idusuario = $(this).data('idusuario'),
            nombres = $(this).data('nombres');
        
        $("#contacto").text("("+nombres+")");

        contScroll = 0;
        loadChat(idusuario);
    });

    $("#frmChat").on('submit', function(e){
        e.preventDefault();
        $("#btnChat").attr('disabled', 'disabled');
        $.post('chat/insertMessage', $(this).serialize(), function(data){
            $("#message").val('');

            $("#btnChat").removeAttr('disabled');

            $("#msj").html(data);
            //console.log(data);
            scrollDown();
        })
    });

    $("#btnDeleteChat").on('click', function(e){
        e.preventDefault();
        let idusuario = $("#idusuario").val();

        if( idusuario == '' ){
            swal_alert('Atención', 'Selecciona un usuario, para borrar los mensajes', 'warning', 'Aceptar')
            return;
        }

        let objConfirm = {
            title: 'Mensaje',
            text: "¿QUIERES ELIMINAR LOS MENSAJES?",
            icon: 'warning',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('chat/deleteChat', {idusuario}, function(data){
                    $("#msj").html(data);
                    //console.log(data);
                })
            }
        }            
        swal_confirm(objConfirm);
    });

    nuevosMensajes();
    intervalMsj = setInterval(() => {
        nuevosMensajes();
    }, 2000);
});


function loadChat(idusuario){
    $.post('chat/loadChat', {
        idusuario: idusuario
    }, function(data){
        $("#chatmessages").html(data);
        //console.log(data);
        $("#idusuario").val(idusuario);

        clearTimeout(interval);

        interval = setTimeout(() => {
            loadChat(idusuario)
        }, 2000);

        scrollDown();
    })
}

function nuevosMensajes(){
    let divs = $('.msjnuevos'),
    ids = [];
    for(div of divs){
        ids.push(div.id.split("-")[1]);
    }
    $.post('chat/newMessages', {ids}, function(data){
        $('#msj').html(data);
        //console.log(data);
    })
}

function scrollDown(){
    let altoChat = containerChat.offsetHeight,
        totalScroll = containerChat.scrollHeight,
        ultimaPos = totalScroll - altoChat;

    let currentScroll = containerChat.scrollTop;

    /* if(contScroll > 0){    
        if(currentScroll < ultimaPos){
            console.log('subiste?');
        }else{
            containerChat.scrollTo(0, ultimaPos);
        } 
    }else{
        containerChat.scrollTo(0, ultimaPos);
        contScroll++;
    } */
    containerChat.scrollTo(0, ultimaPos);

    //console.log(`Alto chat: ${altoChat}, totalScroll: ${totalScroll}, ultima Pos: ${ultimaPos}`);
}

/* $("#chatmessages").on('scroll', function(e){
    let altoChat = containerChat.offsetHeight,
        totalScroll = containerChat.scrollHeight,
        ultimaPos = totalScroll - altoChat;

    console.log(`OnScroll Alto chat: ${altoChat}, totalScroll: ${totalScroll}, ultima Pos: ${ultimaPos}`, this);
    
}) */
</script>