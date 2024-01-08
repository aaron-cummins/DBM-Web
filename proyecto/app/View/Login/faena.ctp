<!-- Main content wrapper -->
<div class="loginWrapper">
    <div class="loginLogo"><img src="/images/logo.png" alt="" /></div>
    <div class="widget" style="height: 163px;">
        <div class="title"><img src="/images/icons/dark/key.png" alt="" class="titleIcon" /><h6>Seleccione Faena</h6></div>
        <form action="/Login/faena" id="validate" class="form" method="post">
            <fieldset>
                <div class="formRow">
						<select name="faena_id" id="faena_id" style="width: 100%; padding: 5px;">
							<?php foreach ($faena as $key => $value) { ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php } ?>
						</select>						
                    <div class="clear"></div>
                </div>
                
                <div class="loginControl">
                    <input type="submit" value="Continuar" class="greenB logMeIn" />
                    <div class="clear"></div>
                </div>
            </fieldset>
        </form>
    </div>
</div>