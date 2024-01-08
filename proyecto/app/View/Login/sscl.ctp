<!-- Main content wrapper -->
<div class="loginWrapper">
    <div class="loginLogo"><img src="/images/logo.png" alt="" /></div>
	<?php echo $this->Session->flash(); ?>
	<?php echo $mensaje;?>
    <div class="widget">
        <div class="title"><img src="/images/icons/dark/key.png" alt="" class="titleIcon" /><h6>Ingreso de Usuario</h6></div>
        <form action="/Login/sscl" id="validate" class="form" method="post">
            <fieldset>
                <div class="formRow">
                    <label for="login">Rut:</label>
                    <div class="loginInput"><input type="text" name="login" id="login" maxlength="10" autocomplete="off" /></div>
                    <div class="clear"></div>
                </div>
                
                <div class="formRow">
                    <label for="pass">PIN:</label>
                    <div class="loginInput"><input type="password" name="pin" id="pin" maxlength="5" autocomplete="off" /></div>
                    <div class="clear"></div>
                </div>
                
                <div class="loginControl">
                  <!--<div class="rememberMe"><input type="checkbox" id="remMe" name="remMe" /><label for="remMe">Supervisor Temporal</label></div>-->
                    <input type="submit" value="Entrar" class="greenB logMeIn" />
                    <div class="clear"></div>
                </div>
            </fieldset>
        </form>
    </div>
</div>