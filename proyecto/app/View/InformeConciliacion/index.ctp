<?php

App::import('Controller', 'Utilidades');
App::import('Controller', 'UtilidadesReporte');
$util = new UtilidadesController();
$utilReporte = new UtilidadesReporteController();

?>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Filtros</span>
                    <span class="caption-helper">Aplique uno o más filtros</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/InformeConciliacion/descargar" class="horizontal-form" method="get">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Faena</label>
                                    <select class="form-control" name="faena_id" id="faena_id" required="required"> 
                                        <option value="">Seleccione una opción</option>
					<?php foreach($faenas as $key => $value) { ?>
                                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
					<?php }?>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio;?>" required="required" />     
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>H. inicio</label>
                                    <select class="form-control" name="hora_inicio" id="hora_inicio" required="required"> 
                                        <option value="">Seleccione una opción</option>
					<?php foreach($h_inicio as $key => $value) { ?>
                                            <option value="<?php echo $key;?>" <?php echo $key == $hora_inicio ? "selected=selected": ''; ?> ><?php echo $value;?></option>
					<?php }?>
                                    </select>
                                    
                                    <script type="text/javascript">
                                        $('#hora_inicio').change(() => {
                                            var hi = $('#hora_inicio').val();
                                            if(hi == '07:00'){
                                                document.getElementById("hora_termino").value = '19:00';
                                                $('#hora_termino').change();
                                            }
                                            
                                            if(hi == '08:00'){
                                                document.getElementById("hora_termino").value = '20:00';
                                                $('#hora_termino').change();
                                            }
                                            
                                            if(hi == '19:00'){
                                                document.getElementById("hora_termino").value = '07:00';
                                                $('#hora_termino').change();
                                            }
                                            
                                            if(hi == '20:00'){
                                                document.getElementById("hora_termino").value = '08:00';
                                                $('#hora_termino').change();
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                            
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label>Fecha Término</label>
                                    <input type="date" name="fecha_termino" class="form-control" value="<?php echo $fecha_termino;?>" required="required" /> </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>H. Término</label>
                                    <select class="form-control" name="hora_termino" id="hora_termino" required="required"> 
                                        <option value="">Seleccione una opción</option>
					<?php foreach($h_termino as $key => $value) { ?>
                                            <option value="<?php echo $key;?>" <?php echo $key == $hora_termino ? "selected=selected": ''; ?> ><?php echo $value;?></option>
					<?php }?>
                                    </select>
                                    
                                    <script type="text/javascript">
                                        $('#hora_termino').change(() => {
                                            var ht = $('#hora_termino').val();
                                            if(ht == '07:00'){
                                                document.getElementById("hora_inicio").value = '19:00';
                                                $('#hora_inicio').change();
                                            }
                                            
                                            if(ht == '08:00'){
                                                document.getElementById("hora_inicio").value = '20:00';
                                                $('#hora_inicio').change();
                                            }
                                            
                                            if(ht == '19:00'){
                                                document.getElementById("hora_inicio").value = '07:00';
                                                $('#hora_inicio').change();
                                            }
                                            
                                            if(ht == '20:00'){
                                                document.getElementById("hora_inicio").value = '08:00';
                                                $('#hora_inicio').change();
                                            }
                                        });
                                    </script>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/Reporte/Base';">Limpiar</button>
                        <button type="submit" class="btn blue" name="btn-descargar" onclick="$('.alert').hide();"><i class="fa fa-file-excel-o"></i> Descargar</button>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>