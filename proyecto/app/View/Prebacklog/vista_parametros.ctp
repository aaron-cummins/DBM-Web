<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Prebacklog Parámetros</span>
                </div>
                <div class="tools">
                    <button type="button" class="btn blue"> <a href="/Prebacklog/index" style="color: white;"> <i class="fa fa-caret-left icon-white"></i>&nbsp;&nbsp;volver</a></button>
                </div>
            </div>
            <form action="" id="formvistaparametro" class="horizontal-form" method="post" enctype="multipart/form-data">
                <div class="portlet-body form">
                    <div class="row">
                    <!-- BEGIN FORM-->
                    <input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo $data['Prebacklog']["id"]; ?>"  />
                    <div class="form-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="portlet box blue-soft">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-angle-double-right"></i>Información base
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: display;">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td style="width: 30%;">
                                                    <div class="row static-info">
                                                        <div class="col-md-3 name"> Faena: </div>
                                                        <div class="col-md-9 value"> <b><?php echo $data['Faena']['nombre'] ?></b> </div>
                                                    </div>
                                                </td>
                                                <td style="width: 30%;">
                                                    <div class="row static-info">
                                                        <div class="col-md-6 name text-right"> Categoría: </div>
                                                        <div class="col-md-6 value text-left"> <b><?php echo $data['Prebacklog_categoria']['nombre'] ?></b> </div>
                                                    </div>
                                                </td>
                                                <td style="width: 40%;">
                                                    <div class="row static-info">
                                                        <div class="col-md-6 name text-right"> Criticidad: </div>
                                                        <div class="col-md-6 value text-left">
                                                            <?php if($data['Criticidad']['nombre'] == 'Alto'){
                                                                echo    '<span class="label label-sm label-danger">Alto</span>';
                                                            }else if($data['Criticidad']['nombre'] == 'Medio'){
                                                                echo    '<span class="label label-sm label-warning">Medio</span>';
                                                            }else{
                                                                echo    '<span class="label label-sm label-success">Bajo</span>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="row static-info">
                                                        <div class="col-md-3 name"> Flota: </div>
                                                        <div class="col-md-9 value"> <b><?php echo $data['Flota']['nombre'] ?></b> </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row static-info">
                                                        <div class="col-md-6 name text-right"> Fec. evento: </div>
                                                        <div class="col-md-6 value text-left"> <b><?php echo date("d-m-Y", strtotime($data['Prebacklog']["fecha_creacion"])); ?></b> </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row static-info">
                                                        <div class="col-md-6 name text-right"> Estado: </div>
                                                        <div class="col-md-6 value text-left">
                                                            <?php if($data['Estado']['id'] == 7){
                                                                echo    '<span class="label label-sm label-default">'. $data['Estado']['nombre_prebacklog'].'</span>';
                                                            }else if($data['Estado']['id'] == 3){
                                                                echo    '<span class="label label-sm label-warning">'. $data['Estado']['nombre_prebacklog'].'</span>';
                                                            }else if($data['Estado']['id'] == 11){
                                                                echo    '<span class="label label-sm label-success">'. $data['Estado']['nombre_prebacklog'].'</span>';
                                                            } else if($data['Estado']['id'] == 9){
                                                                echo    '<span class="label label-sm label-info">'. $data['Estado']['nombre_prebacklog'].'</span>';
                                                            } else if($data['Estado']['id'] == 2){
                                                                echo    '<span class="label label-sm label-primary">'. $data['Estado']['nombre_prebacklog'].'</span>';
                                                            } else if($data['Estado']['id'] == 13){
                                                                echo    '<span class="label label-sm bg-yellow-gold">'. $data['Estado']['nombre_prebacklog'].'</span>';
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                                <?php if($data['Estado']['id'] == 9) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="row static-info">
                                                            <div class="col-md-3 name"> Equipo: </div>
                                                            <div class="col-md-9 value"> <b><?php echo $data['Unidad']['unidad'] ?></b> </div>
                                                        </div>
                                                    </td>


                                                    <td>
                                                        <div class="row static-info">
                                                            <div class="col-md-6 name text-right"> Motivo cierre: </div>
                                                            <div class="col-md-6 value text-left">
                                                                <b><?php echo $data['Prebacklog_motivoCierre']['motivo'] ?></b>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row static-info">
                                                            <div class="col-md-3 name"> ESN: </div>
                                                            <div class="col-md-9 value"> <b><?php echo $data['Prebacklog']['esn'] ?></b> </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="row static-info">
                                                            <div class="col-md-6 name text-right"> Fec. cierre: </div>
                                                            <div class="col-md-6 value text-left">
                                                                <b><?php echo date_format(date_create($data['Prebacklog']['fecha_cierre']), 'd-m-Y H:i:s') ?></b>
                                                            </div>
                                                         </div>
                                                     </td>

                                                    <td>&nbsp;</td>
                                                </tr>

                                                <?php } else if($data['Estado']['id'] == 13) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="row static-info">
                                                                <div class="col-md-3 name"> Equipo: </div>
                                                                <div class="col-md-9 value"> <b><?php echo $data['Unidad']['unidad'] ?></b> </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="row static-info">
                                                                <div class="col-md-3 name"> ESN: </div>
                                                                <div class="col-md-9 value"> <b><?php echo $data['Prebacklog']['esn'] ?></b> </div>
                                                            </div>
                                                        </td>

                                                        <td></td>
                                                        <td>&nbsp;

                                                            <?php if($data['Prebacklog']['e_falla_acumulativa'] == 'true') { ?>
                                                                <div class="row static-info">
                                                                    <div class="col-md-6 name text-right"> evita falla acumulativa: </div>
                                                                    <div class="col-md-6 value text-left">
                                                                        <b><span class="label label-sm label-success">Si</span></b>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                            <?php if($data['Prebacklog']['e_falla_mayor'] == 'true') { ?>
                                                            <div class="row static-info">
                                                                <div class="col-md-6 name text-right"> evita falla mayor: </div>
                                                                <div class="col-md-6 value text-left" >
                                                                    <b><span class="label label-sm label-success">Si</span></b>
                                                                </div>
                                                            </div>
                                                            <?php } ?>

                                                            <?php if($data['Prebacklog']['e_falla_electrica'] == 'true') { ?>
                                                            <div class="row static-info">
                                                                <div class="col-md-6 name text-right"> evita falla eléctrica: </div>
                                                                <div class="col-md-6 value text-left">
                                                                    <b><span class="label label-sm label-success">Si</span></b>
                                                                </div>
                                                            </div>
                                                            <?php } ?>

                                                            <?php if($data['Prebacklog']['no_existe_falla'] == 'true') { ?>
                                                            <div class="row static-info">
                                                                <div class="col-md-6 name text-right"> No existe falla: </div>
                                                                <div class="col-md-6 value text-left">
                                                                    <b><span class="label label-sm label-success">Si</span></b>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php }else { ?>
                                                <tr>
                                                    <td>
                                                        <div class="row static-info">
                                                            <div class="col-md-3 name"> Equipo: </div>
                                                            <div class="col-md-9 value"> <b><?php echo $data['Unidad']['unidad'] ?></b> </div>
                                                        </div>
                                                    </td>

                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td> 
                                                        <div class="row static-info">
                                                            <div class="col-md-3 name"> ESN: </div>
                                                            <div class="col-md-9 value"> <b><?php echo $data['Prebacklog']['esn'] ?></b> </div>
                                                        </div>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 claerfix">
                            <!-- Alerta -->
                            <div class="portlet box blue-soft">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-angle-double-right"></i>Identificación de alerta
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: display;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <div class="row static-info">
                                                            <div class="col-md-3 name"> Cat. Sintoma:</div>
                                                            <div class="col-md-9 value"><b><?php echo $data['SintomaCategoria']['nombre'] ?></b></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="row static-info">
                                                            <div class="col-md-3 name">Sintoma:</div>
                                                            <div class="col-md-9 value"> <b><?php echo $data['Sintoma']['nombre'] ?></b></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 claerfix">
                            <!-- Alerta -->
                            <div class="portlet box blue-soft">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-angle-double-right"></i>Backlog Asociados
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: display;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                               <?php 
                                                if(isset($backlogs) && count($backlogs) > 0){
                                                    foreach($backlogs as $b) { 
                                                        $criticidad = "";
                                                        $responsable = "";
                                                        $style = "";
                                                        if ($b['Backlog']['criticidad_id'] == "1") {
                                                                $criticidad = "Alto";
                                                                $style = "danger";
                                                        } else if ($b['Backlog']['criticidad_id'] == "2") {
                                                                $criticidad = "Medio";
                                                                $style = "warning";
                                                        } else if ($b['Backlog']['criticidad_id'] == "3") {
                                                                $criticidad = "Bajo";
                                                                $style = "success";
                                                        }
                                                        if($b['Backlog']['responsable_id'] == "1"){
                                                                $responsable = "DCC";
                                                        }else if ($b['Backlog']['responsable_id'] == "2"){
                                                                $responsable = "OEM";
                                                        }else if ($b['Backlog']['responsable_id'] == "3"){
                                                                $responsable = "MINA";
                                                        }
                                                        //$fecha = new Date(item.Backlog.fecha_creacion);
                                                        if($b['Backlog']['estado_id'] == 10){

                                                            echo '<div class="note note-danger">';
                                                            echo "(Eliminado) F-". $b['Backlog']['id'] ." </a> | <span class='label label-$style'>$criticidad</span> | ". date('d-m-Y',strtotime($b['Backlog']['fecha_creacion'])) ." | ". $responsable . " | ". $b['Sistema']['nombre'].
                                                                  " | descripcion: " .$b['Backlog']['comentario']."<br>";
                                                            echo  '</div>';

                                                        }else{

                                                            echo '<div class="note note-info">';
                                                            echo    "<a href='/Backlog/Web/". $b['Backlog']['id'] ."?idPrebacklog=". $data['Prebacklog']["id"] ."' target='_blank'> F-". $b['Backlog']['id'] ." </a> | <span class='label label-$style'>$criticidad</span> | ". date('d-m-Y',strtotime($b['Backlog']['fecha_creacion'])) ." | ". $responsable . " | ". $b['Sistema']['nombre'].
                                                                  " | descripcion: " .$b['Backlog']['comentario'];
                                                            echo  '</div>';

                                                            //echo "<a href='/Backlog/Web/". $b['Backlog']['id'] ."?idPrebacklog=". $data['Prebacklog']["id"] ."' target='_blank'> F-". $b['Backlog']['id'] ." </a> | <span class='label label-$style'>$criticidad</span> | ". date('d-m-Y',strtotime($b['Backlog']['fecha_creacion'])) ." | ". $responsable . " | ". $b['Sistema']['nombre'].
                                                            //      " | descripcion: " .$b['Backlog']['comentario']."<br>";
                                                        }
                                                    } 
                                               }else{
                                                    echo "No hay backlog asignados aún ";
                                                    if ($data['Prebacklog']['estado_id'] == 3 || $data['Prebacklog']['estado_id'] == 7) {
                                                        //echo $this->Html->link("Detalle", array('controller' => 'Trabajo', 'action' => 'Previsualizar', $registro['Planificacion']['id']), array('class' => 'btn btn-sm blue'));
                                                        echo '<br><a class="btn btn-sm yellow tooltips" href="#" onclick="crea_backlog(' . $data['Prebacklog']['id'] . ', ' . $data['Prebacklog']['tipo'] . ')" data-container="body" data-placement="top" data-original-title="Crear backlog"><i class="fa fa-th"></i>  Crear Backlog</a>';
                                                        //echo $this->Html->link("Planificar", array('controller' => 'Trabajo', 'action' => 'Planificar' ,"Backlog", $registro['Backlog']['id']), array('class' => 'btn btn-sm blue'));
                                                    }
                                               }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <!-- COMENTARIO -->
                            <div class="portlet box blue-soft">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-angle-double-right"></i>Comentario
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: display;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="scroller" style="" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                                    <ul class="chats" id="">
                                                        <?php
                                                        $i = 0;
                                                        foreach ($comentarios as $comen) {
                                                            if ($i % 2 == 0) {
                                                                ?>

                                                                <li class="in">
                                                                    <img class="avatar" alt="" src="/images/user.png">
                                                                    <div class="message">
                                                                        <span class="arrow"> </span>
                                                                        <a href="javascript:;" class="name"><?php echo $comen['Usuario']['nombres'] . ' ' . $comen['Usuario']['apellidos'] ?> </a>
                                                                        <span class="datetime"> <?php echo $comen['Prebacklog_comentario']['fecha'] ?> </span>
                                                                        <span class="body"> <?php echo $comen['Prebacklog_comentario']['comentario'] ?> </span>
                                                                    </div>
                                                                </li>
                                                            <?php } else { ?>
                                                                <li class="out">
                                                                    <img class="avatar" alt="" src="/images/user.png">
                                                                    <div class="message">
                                                                        <span class="arrow"> </span>
                                                                        <a href="javascript:;" class="name"><?php echo $comen['Usuario']['nombres'] . ' ' . $comen['Usuario']['apellidos'] ?> </a>
                                                                        <span class="datetime"> <?php echo $comen['Prebacklog_comentario']['fecha'] ?> </span>
                                                                        <span class="body"> <?php echo $comen['Prebacklog_comentario']['comentario'] ?> </span>
                                                                    </div>
                                                                </li>
                                                                <?php
                                                            }
                                                            $i++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <?php if($data['Prebacklog']['estado_id'] == 7 || $data['Prebacklog']['estado_id'] == 3 || $data['Prebacklog']['estado_id'] == 2){ ?>
                                                
                                                <div class="chat-form">
                                                    
                                                    <div class="form-group">                                                       
                                                        <div class="input-group input-group-sm">
                                                            <input type="file" class="form-control" name="archivo[]" placeholder="NO hay archivos seleccionados">
                                                            <!--<span class="input-group-btn">
                                                                <button class="btn green" type="button" onclick="addArchivos(1);"><i class="fa fa-plus-circle"></i></button>
                                                            </span>-->
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="input-cont">
                                                        <input class="form-control" name="comentario" id="comentario" type="text" placeholder="Escribe tu comentario aqui"> 
                                                    </div>
                                                    <div class="btn-cont"> 
                                                        <span class="arrow"> </span>
                                                        <button type="button" onclick="enviarForm();" id="coments" class="btn blue icn-only"> <i class="fa fa-check icon-white"></i></button>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <!-- ARCHIVOS -->
                            <div class="portlet box blue-soft">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-angle-double-right"></i>Archivos
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: display;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group"> 
                                                    <?php foreach ($arvhivos as $kry => $value) { ?>
                                                            <div class="media">
                                                                <?php 
                                                                $resultado = substr($value['Prebacklog_archivo']['nombre'], -3);
                                                                $resultado2 = substr($value['Prebacklog_archivo']['nombre'], -4);
                                                                
                                                                if($resultado == 'pdf') {?>
                                                                    <div class="media-left">
                                                                        <a href="#"><img width="60" height="60" class="media-object" alt="" src="/images/img_pdf.png"> </a>
                                                                    </div>
                                                               <?php } elseif($resultado == 'csv') {?>
                                                                    <div class="media-left">
                                                                        <a href="#"><img width="60" height="60" class="media-object" alt="" src="/images/img_xlsx.png"> </a>
                                                                    </div>
                                                                
                                                                <?php } elseif($resultado2 == 'xlsx') {?>
                                                                    <div class="media-left">
                                                                        <a href="#"><img width="60" height="60" class="media-object" alt="" src="/images/img_xlsx.png"> </a>
                                                                    </div>
                                                                
                                                                <?php } else { ?>
                                                                    <div class="media-left">
                                                                        <a href="#"><img width="60" height="60" class="media-object" alt="" src="/images/img_img.png"> </a>
                                                                    </div>
                                                                <?php } ?>

                                                                <div class="media-body">
                                                                    <h5 class="media-heading">
                                                                        <a href="/Prebacklog/descarga/<?php echo $value['Prebacklog_archivo']['nombre'];?>"><?php echo $value['Prebacklog_archivo']['nombre'] ; ?></a><i> - el
                                                                        <span class="c-date"><?php echo date("d-m-Y", strtotime($value['Prebacklog_archivo']['fecha'])); ?></span></i>
                                                                    </h5> 
                                                                    <?php echo $value['Usuario']['nombres']. ' ' . $value['Usuario']['apellidos'] ; ?> - <a onclick="eliminararchivo(<?php echo "'",$value['Prebacklog_archivo']['nombre']. "',". $value['Prebacklog_archivo']['id']. ",1,". $data['Prebacklog']["id"] ;?>)">eliminar</a>
                                                                </div>
                                                            </div>
                                                        
                                                         <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                <!--<div class="clearfix"></div>

                <div class="form-actions right">
                    <button type="button" class="btn default" onclick="window.location = '/Prebacklog/aceite';">Cancelar</button>
                    <button type="submit" class="btn blue"> <i class="fa fa-filter"></i> Guardar</button>
                </div>-->
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>

<script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript">

    window.addEventListener("keypress", function(event){
        if (event.keyCode == 13){
            event.preventDefault();
        }
    }, false);

    function mostrar(id) {
        if (document.getElementById("alerta_" + id).checked) {
            $("#A_P_" + id).show();
        } else {
            $("#A_P_" + id).hide();
        }
    }

    function enviarForm() {
        if ($("#comentario").val() != "") {
            $("#coments").attr("disabled", "disabled");
            $("#formvistaparametro").submit();
        } else {
            alert("Debe ingresar un comentario.");
        }
    }
    function crea_backlog(id, tipo){
        if(confirm("Va a crear un Backlog, ¿esta seguro?")){
            window.location.href  = "/Backlog/web?idPrebacklog="+id+"&tipo="+tipo;
        }else{
            return false;
        }
    }
    
    function eliminararchivo(nombre, id, tipo, idPrebacklog){
        if(confirm("Va a eliminar este archivo, ¿esta seguro?")){
            window.location.href  = "/Prebacklog/eliminarArchivo/"+nombre+"/"+id+"/"+tipo+"/"+idPrebacklog;
        }else{
            return false;
        }
    }
</script>