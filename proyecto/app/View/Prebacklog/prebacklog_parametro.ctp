<?php
?>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Crear Prebacklog Parámetros</span>
                </div>
            </div>
            <form action="" class="horizontal-form" method="post" enctype="multipart/form-data">
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->

                    <input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo $data["id"]; ?>"  />
                    <div class="form-body">
                        <!-- CATEGORIA -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box blue-soft">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-angle-double-right"></i>Categoría
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: display;">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Categoría</label>
                                                        <select class="form-control" name="data[categoria_id]" id="prebacklog_categoria_id" required="required"> 
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($categoria as $key => $value) { ?>
                                                                <option value="<?php echo $value["Prebacklog_categoria"]["id"]; ?>" <?php echo @$data["categoria_id"] == $value["Prebacklog_categoria"]["id"] ? "selected=\"selected\"" : ""; ?> ><?php echo $value["Prebacklog_categoria"]["nombre"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>¿Enviar correo?</label>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" id="envia_correo" name="data[envia_correo]" value="1" class="md-check" checked>
                                                                <label for="envia_correo">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> email </label>
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

                        <!-- FECHA EVENTO -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box blue-soft">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-angle-double-right"></i>Fecha Evento
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: display;">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6"> 
                                                    <label>Fecha evento</label>
                                                    <input type="date" name="fecha_creacion_f"  id="fecha_creacion_f" class="form-control" value="<?php echo (@$data["fecha_creacion"] != "" ? date("Y-m-d",strtotime(@$data["fecha_creacion"])) : ""); ?>">
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <label>Hora evento</label>
                                                    <input type="time" name="fecha_creacion_h"  id="fecha_creacion_h" class="form-control" value="<?php echo (@$data["fecha_creacion"] != "" ? date("H:i",strtotime(@$data["fecha_creacion"])): ""); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAENA - FLOTA - EQUIPO -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box blue-soft">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-angle-double-right"></i>Identificación de Equipo
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: display;">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Faena</label>
                                                        <select class="form-control" name="data[faena_id]" required="required" id="faena_id"> 
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($faenas as $key => $value) { ?>
                                                                <option value="<?php echo $key; ?>" <?php echo @$data["faena_id"] == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Flota</label>
                                                        <select class="form-control" name="data[flota_id]" required="required" id="flota_id"> 
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($flotas as $key => $value) { ?>
                                                                <option value="<?php echo $value["FaenaFlota"]["faena_id"]; ?>_<?php echo $value["FaenaFlota"]["flota_id"]; ?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"]; ?>"><?php echo $value["Flota"]["nombre"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Equipo</label>
                                                        <select class="form-control" name="data[unidad_id]" id="unidad_id" required="required"> 
                                                            <option value="">Seleccione una opción </option>
                                                            <?php foreach ($unidades as $key => $value) { ?>
                                                                <option value="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>_<?php echo $value["Unidad"]["id"]; ?>" faena_flota="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>" motor_id="<?php echo $value["Unidad"]["motor_id"]; ?>"><?php echo $value["Unidad"]["unidad"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="esn" class="col-md-4 control-label">ESN</label>
                                                        <input name="data[esn]" class="form-control" required="required" type="text" id="esn" readonly="readonly"  />
                                                    </div>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SINTOMAS -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box blue-soft">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-angle-double-right"></i>Sintomas
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
                                                        <label>Criticidad</label>
                                                        <select class="form-control" name="data[criticidad_id]" required="required" id="criticidad_id"> 
                                                            <option value="">Seleccione una opción</option>
                                                            <option value="1" <?php echo @$data["criticidad_id"] == 1 ? "selected=\"selected\"" : ""; ?>>Alto</option>
                                                            <option value="2" <?php echo @$data["criticidad_id"] == 2 ? "selected=\"selected\"" : ""; ?>>Medio</option>
                                                            <option value="3" <?php echo @$data["criticidad_id"] == 3 ? "selected=\"selected\"" : ""; ?>>Bajo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Categoría síntoma</label>
                                                        <select class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id"> 
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($categoria_sintoma as $key => $value) { ?>
                                                                <option value="<?php echo $key; ?>" <?php echo @$data["categoria_sintoma_id"] == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Síntoma</label>
                                                        <select class="form-control" name="data[sintoma_id]" required="required" id="sintoma_id"> 
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($sintomas as $key => $value) { ?>
                                                                <?php if ($value['Sintoma']["codigo"] != "0" && $value['Sintoma']["codigo"] != "") { ?>
                                                                    <option value="<?php echo $value['Sintoma']["id"]; ?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;"><?php echo $value['Sintoma']["codigo"]; ?> - <?php echo $value['Sintoma']["nombre"]; ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?php echo $value['Sintoma']["id"]; ?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;"><?php echo $value['Sintoma']["nombre"]; ?></option>

                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COMENTARIO -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <div class="scroller" style="height: auto; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                                    <ul class="chats" id="">
                                                        <?php $i = 0;
                                                        foreach($comentarios as $comen){ 
                                                            if($i%2 == 0){?>

                                                            <li class="in">
                                                                <img class="avatar" alt="" src="/images/user.png">
                                                                <div class="message">
                                                                    <span class="arrow"> </span>
                                                                    <a href="javascript:;" class="name"><?php echo $comen['Usuario']['nombres'] . ' ' . $comen['Usuario']['apellidos'] ?> </a>
                                                                    <span class="datetime"> <?php echo $comen['Prebacklog_comentario']['fecha'] ?> </span>
                                                                    <span class="body"> <?php echo $comen['Prebacklog_comentario']['comentario'] ?> </span>
                                                                </div>
                                                            </li>
                                                        <?php }else{ ?>
                                                            <li class="out">
                                                                <img class="avatar" alt="" src="/images/user.png">
                                                                <div class="message">
                                                                    <span class="arrow"> </span>
                                                                    <a href="javascript:;" class="name"><?php echo $comen['Usuario']['nombres'] . ' ' . $comen['Usuario']['apellidos'] ?> </a>
                                                                    <span class="datetime"> <?php echo $comen['Prebacklog_comentario']['fecha'] ?> </span>
                                                                    <span class="body"> <?php echo $comen['Prebacklog_comentario']['comentario'] ?> </span>
                                                                </div>
                                                            </li>
                                                        <?php } 
                                                        $i++;
                                                        }?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Comentario</label>
                                                        <textarea name="comentario" class="form-control" required="required" id="comentario"><?php echo @$data["comentario"]; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ARCHIVOS -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                            
                                            <div class="row" id="archivosdiv">
                                                <div class="col-md-12">
                                                    <div class="form-group">                                                       
                                                        <div class="input-group input-group-lg">
                                                            <input type="file" class="form-control" name="archivo[]" placeholder="NO hay archivos seleccionados">
                                                            <span class="input-group-btn">
                                                                <button class="btn green" type="button" onclick="addArchivos(1);"><i class="fa fa-plus-circle"></i></button>
                                                            </span>
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
                <div class="form-actions right">
                    <button type="button" class="btn default" onclick="window.location = '/Prebacklog/';">Cancelar</button>
                    <button type="submit" class="btn blue"> <i class="fa fa-filter"></i> Guardar</button>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>

<script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    contador = 1;
    
    function addArchivos(id){
        
        ident = id + contador;
        
        arch = '<div class="col-md-12" id="arch' + ident + '" >'
        +'    <div class="form-group">'
        +'        <div class="input-group input-group-lg">'
        +'            <input type="file" class="form-control" name="archivo[]" placeholder="NO hay archivos seleccionados">'
        +'            <span class="input-group-btn">'
        +'                <button class="btn red" type="button" onclick="delArchivos(' + ident + ');"><i class="fa fa-minus-circle"></i></button>'
        +'            </span>'
        +'        </div>'
        +'    </div>'
        +'</div>'


        $("#archivosdiv").append(arch);
        
        contador += 1;
    }
    
    function delArchivos(id){
        document.getElementById("arch"+id).remove();
    }
    
    
    
</script>
