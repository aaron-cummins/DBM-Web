<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <form name="controlaform" id="controlaform" action="/Configuracion/controla" class="horizontal-form" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>T</label>
                                    <select class="form-control" name="t" id="t">
                                        <option value="s">S</option>
                                        <option value="i">I</option>
                                        <option value="u">U</option>
                                        <option style="display: none" value="d">D</option>
                                    </select> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label>C</label>
                                    <input name="c" id="c" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label>F</label>
                                    <input name="f" id="f" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label>W</label>
                                    <input name="w" id="w" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label>O</label>
                                    <input name="o" id="o" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label>L</label>
                                    <input name="l" id="l" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label>V</label>
                                    <input name="v" id="v" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/Configuracion/controla';">Limpiar</button>
                        <button type="button" onclick="valida();" class="btn blue" name="calc">Aplicar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <p>Archivos de log</p>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">   
                                <a href="tmp?i=1" class="btn default">error</a>
                                <a href="tmp?i=0" class="btn default">debug</a>
                                <a href="?space_disk=1" class="btn default">Espacio en Disco</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(isset($total)) {?>
                    <p>Espacio Total: <b><?php echo $total ?></b></p>
                    <p>Espacio Utilizado: <b><?php echo $utilizado ?></b></p>
                    <p>Espacio Disponible: <b><?php echo $disponible ?></b></p>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    function valida(){
        if(confirm("esta seguro de lo que va a hacer?")){
            $("#controlaform").submit();
        }
    }
</script>

