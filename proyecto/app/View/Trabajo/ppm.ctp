	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina1">
	 <tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 1</td>
		</tr>
  <tr>
    <td colspan="2"></td>
    <td colspan="6" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>DATOS</strong></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" bgcolor="#EEEEEE">Fecha de Inspección</td>
    <td colspan="6" bgcolor="#EEEEEE" ><input type="date" name="pm_fecha" id="pm_fecha"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" bgcolor="#EEEEEE">Asesor Técnico</td>
    <td colspan="6" bgcolor="#EEEEEE">
		<select name="pm_asesor" id="pm_asesor">
			  <option value="null">N/A</option>
		</select>
	</td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>INFORMACIÓN DEL EQUIPO</strong></td>
    <td colspan="6" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>DATOS</strong></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle">Fecha puesta en servicio</td>
    <td colspan="6" ><input type="date" name="pm_fecha_servicio" id="pm_fecha_servicio"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle">Modelo del motor</td>
    <td colspan="6" ><input type="text" name="pm_modelo_motor" id="pm_modelo_motor"></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle">ESN (Nº de Serie Motor)</td>
    <td colspan="6" ><input type="text" name="pm_esn" id="pm_esn" readonly></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle">CPL</td>
    <td colspan="6" ><input type="text" name="pm_1_cpl" id="pm_1_cpl"></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Fabricante    del Equipo</td>
    <td colspan="6" ><input type="text" name="pm_fabricante" id="pm_fabricante"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" >Modelo del Equipo</td>
    <td colspan="6" ><input type="text" name="pm_modelo_equipo" id="pm_modelo_equipo"></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Serie del Equipo</td>
    <td colspan="6" ><input type="text" name="pm_nserie" id="pm_nserie"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" >Número Interno del equipo</td>
    <td colspan="6" ><input type="text" name="pm_ninterno" id="pm_ninterno"></td>
  </tr>
    <tr>
    <td colspan="2" valign="middle" >Tipo de equipo (Aplicación)</td>
    <td colspan="6" ><input type="text" name="pm_aplicacion" id="pm_aplicacion" readonly></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Horometro Quantum al momento de la instalación</td>
    <td colspan="5" ><input type="text" name="pm_h_quantum" id="pm_h_quantum"></td>
    <td width="38">&nbsp;&nbsp;Hr</td>
  </tr>
   <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Horometro motor al momento de la instalación</td>
    <td colspan="5" ><input type="text" name="pm_h_instalacion" id="pm_h_instalacion"></td>
    <td width="38">&nbsp;&nbsp;Hr</td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" >Estatus    del Equipo (especificar nuevo o usado)</td>
    <td colspan="6" ><select name="pm_estatus" id="pm_estatus">
      <option selected="selected" value="" disabled="disabled">Estatus</option>
      <option value="NUEVO">Nuevo</option>
      <option value="USADO">Usado</option>
    </select></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" bgcolor="#333333"  style="color: #fff; padding: 10px;"><strong>INFORMACIÓN DEL CLIENTE</strong></td>
    <td colspan="6" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>DATOS</strong></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Nombre    del Cliente</td>
    <td colspan="6" ><input type="text" name="pm_1_cli" id="pm_1_cli"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" >Nombre    y Título Contacto del Cliente</td>
    <td colspan="6" ><input type="text" name="pm_1_cli_cont" id="pm_1_cli_cont"></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Dirección    (incluir ciudad, región y país)</td>
    <td colspan="6" ><input type="text" name="pm_1_cli_dir" id="pm_1_cli_dir"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>INFORMACIÓN DEL DISTRIBUIDOR</strong></td>
    <td colspan="6" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>DATOS</strong></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Nombre    del Distribuidor</td>
    <td colspan="6" ><input type="text" name="pm_1_dist" id="pm_1_dist"></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" >Locación    del Distribuidor</td>
    <td colspan="6" ><input type="text" name="pm_1_dist_loc" id="pm_1_dist_loc"></td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td colspan="2" valign="middle" >Código    del Distribuidor</td>
    <td colspan="6" ><input type="text" name="pm_1_dist_cod" id="pm_1_dist_cod"></td>
  </tr>
</table>

	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina2">
	 <tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 2</td>
		</tr>
		<tr>
		<td colspan="8" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>INSPECCIÓN DEL MOTOR</strong></td>
	  </tr>
	  <tr>
		<td colspan="2" bgcolor="#CCCCCC" ><strong>Chequear lo siguiente antes de echar a correr el motor, ajuste si es necesario</strong></td>
		<td colspan="2" align="center" bgcolor="#CCCCCC" ><strong>SI</strong></td>
		<td colspan="2" align="center" bgcolor="#CCCCCC"><strong>NO</strong></td>
		<td colspan="2" align="center" bgcolor="#CCCCCC"><strong>N/A</strong></td>
	  </tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >Marca    de Comienzo de garantía en placa de datos motor</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_01" id="pm_02_01s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_01" id="pm_02_01n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_01" id="pm_02_01a" value="N/A"></td>
	  </tr>
	  <tr>
		<td colspan="2" >Montaje    de motor y accesorios</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_02" id="pm_02_02s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_02" id="pm_02_02n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_02" id="pm_02_02a" value="N/A"></td>
		</tr>
	  <tr bgcolor="#eee">
		<td colspan="2" >Fugas    de combustible, lubricante, aceite o refrigerante</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_03" id="pm_02_03s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_03" id="pm_02_03n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_03" id="pm_02_03a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Instalación    de sistema de combustible y nivel de combustible</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_04" id="pm_02_04s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_04" id="pm_02_04n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_04" id="pm_02_04a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >Instalación    de sistema de lubricación y nivel de aceite</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_05" id="pm_02_05s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_05" id="pm_02_05n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_05" id="pm_02_05a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Instalación    de sistema de pre-lubricación</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_06" id="pm_02_06s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_06" id="pm_02_06n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_06" id="pm_02_06a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >Instalación    de sistema Centinel y nivel de estanque Centinel</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_07" id="pm_02_07s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_07" id="pm_02_07n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_07" id="pm_02_07a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Instalación    de sistema de refrigeración y nivel de refrigerante</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_08" id="pm_02_08s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_08" id="pm_02_08n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_08" id="pm_02_08a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >Nivel    de concentración de DCA y anticongelante en refrigerante</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_09" id="pm_02_09s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_09" id="pm_02_09n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_09" id="pm_02_09s" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Instalación    de sistema eléctrico y nivel de electrolito de batería</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_10" id="pm_02_10s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_10" id="pm_02_10n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_10" id="pm_02_10a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >Filtros    de aire</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_11" id="pm_02_11s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_11" id="pm_02_11n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_11" id="pm_02_11a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Respiraderos    de motor</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_12" id="pm_02_12s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_12" id="pm_02_12n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_12" id="pm_02_12a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >Tensión    de correas (todas)</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_13" id="pm_02_13s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_13" id="pm_02_13n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_13" id="pm_02_13a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Luces    de cabina del ECM funcionan apropiadamente</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_14" id="pm_02_14s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_14" id="pm_02_14n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_14" id="pm_02_14a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" bgcolor="#eee" >No    hay códigos de falla activos (Quantum o Cense)</td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_15" id="pm_02_15s" value="SI"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_15" id="pm_02_15n" value="NO"></td>
		<td colspan="2" align="center" bgcolor="#eee" ><input type="radio" data-role="none" name="pm_02_15" id="pm_02_15a" value="N/A"></td>
		</tr>
	  <tr>
		<td colspan="2" >Limpiar    códigos de falla inactivos (Quantum o Cense)</td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_16" id="pm_02_16s" value="SI"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_16" id="pm_02_16n" value="NO"></td>
		<td colspan="2" align="center" ><input type="radio" data-role="none" name="pm_02_16" id="pm_02_16a" value="N/A"></td>
		</tr>
	</table>
 
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina3">
  <tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 3</td>
		</tr>
		<tr>
    <td ></td>
    <td ></td>
    <td colspan="3" align="center" style="background-color:#333;color:#fff; padding:10px;" ><strong>MEDIDA</strong></td>
    <td colspan="3" align="center" style="background-color:#333;color:#fff; padding:10px;"><strong>N/A</strong></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Verificar    comunicación con ECM Cense, registrando el/los código(s) de calibración</td>
    <td colspan="3" ><input type="text" name="pm_03_t1" id="pm_03_t1"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c1" id="pm_03_c1"></td>
  </tr>
  <tr>
    <td colspan="2" >Verificar comunicación con ECM Quantum,    registrando el/los código(s) de calibración</td>
    <td colspan="3" ><input type="text" name="pm_03_t2" id="pm_03_t2"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c2" id="pm_03_c2"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego    axial del cigüeñal</td>
    <td colspan="2" ><input type="text" name="pm_03_t3" id="pm_03_t3"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c3" id="pm_03_c3"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo HP-LBF</td>
    <td colspan="2" ><input type="text" name="pm_03_t4" id="pm_03_t4"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c4" id="pm_03_c4"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego axial del turbo HP-LBM</td>
    <td colspan="2" ><input type="text" name="pm_03_t5" id="pm_03_t5"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c5" id="pm_03_c5"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo HP-LBR</td>
    <td colspan="2" ><input type="text" name="pm_03_t6" id="pm_03_t6"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c6" id="pm_03_c6"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego axial del turbo HP-RBF</td>
    <td colspan="2" ><input type="text" name="pm_03_t7" id="pm_03_t7"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c7" id="pm_03_c7"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo HP-RBM</td>
    <td colspan="2" ><input type="text" name="pm_03_t8" id="pm_03_t8"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c8" id="pm_03_c8"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego axial del turbo HP-RBR</td>
    <td colspan="2" ><input type="text" name="pm_03_t9" id="pm_03_t9"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c9" id="pm_03_c9"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo LP-LBF</td>
    <td colspan="2" ><input type="text" name="pm_03_t10" id="pm_03_t10"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c10" id="pm_03_c10"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego axial del turbo LP-LBM</td>
    <td colspan="2" ><input type="text" name="pm_03_t11" id="pm_03_t11"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c11" id="pm_03_c11"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo LP-LBR</td>
    <td colspan="2" ><input type="text" name="pm_03_t12" id="pm_03_t12"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c12" id="pm_03_c12"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego axial del turbo LP-RBF</td>
    <td colspan="2" ><input type="text" name="pm_03_t13" id="pm_03_t13"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c13" id="pm_03_c13"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo LP-RBM</td>
    <td colspan="2" ><input type="text" name="pm_03_t14" id="pm_03_t14"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c14" id="pm_03_c14"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Juego axial del turbo LP-RBR</td>
    <td colspan="2" ><input type="text" name="pm_03_t15" id="pm_03_t15"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c15" id="pm_03_c15"></td>
  </tr>
  <tr>
    <td colspan="2" >Tensión correa bomba de refrigerante</td>
    <td colspan="2" ><input type="text" name="pm_03_t16" id="pm_03_t16"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c16" id="pm_03_c16"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Tensión    correa de alternador</td>
    <td colspan="2" ><input type="text" name="pm_03_t17" id="pm_03_t17"></td>
    <td width="95">Lb    Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c17" id="pm_03_c17"></td>
  </tr>
  <tr>
    <td colspan="2" >Tensión    correa de ventilador</td>
    <td colspan="2" ><input type="text" name="pm_03_t18" id="pm_03_t18"></td>
    <td width="95">Lb    ft</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c18" id="pm_03_c18"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Presión    tapa radiador</td>
    <td colspan="2" ><input type="text" name="pm_03_t19" id="pm_03_t19"></td>
    <td width="95">???</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_03_c19" id="pm_03_c19"></td>
  </tr>

</table>
	
	
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina4">
<tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 4</td>
		</tr>
  <tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>COMPONENTES</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    correa bomba de agua</td>
    <td colspan="3" ><input type="text" name="pm_04_t1" id="pm_04_t1"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c1" id="pm_04_c1"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >N/P    correa ventilador</td>
    <td colspan="3" ><input type="text" name="pm_04_t2" id="pm_04_t2"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c2" id="pm_04_c2"></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    correa alternador</td>
    <td colspan="3" ><input type="text" name="pm_04_t3" id="pm_04_t3"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c3" id="pm_04_c3"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >N/P    filtro de Aceite</td>
    <td colspan="3" ><input type="text" name="pm_04_t4" id="pm_04_t4"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c4" id="pm_04_c4"></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    filtro de combustible</td>
    <td colspan="3" ><input type="text" name="pm_04_t5" id="pm_04_t5"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c5" id="pm_04_c5"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >N/P  filtro de agua</td>
    <td colspan="3" ><input type="text" name="pm_04_t6" id="pm_04_t6"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c6" id="pm_04_c6"></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    filtro de aire primario</td>
    <td colspan="3" ><input type="text" name="pm_04_t7" id="pm_04_t7"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c7" id="pm_04_c7"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >N/P    filtro de aire secundario</td>
    <td colspan="3" ><input type="text" name="pm_04_t8" id="pm_04_t8"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c8" id="pm_04_c8"></td>
  </tr>
  <tr>
    <td colspan="2" >Fabricante    del Prelub</td>
    <td colspan="3" ><input type="text" name="pm_04_t9" id="pm_04_t9"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c9" id="pm_04_c9"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Prelub</td>
    <td colspan="3" ><input type="text" name="pm_04_t10" id="pm_04_t10"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c10" id="pm_04_c10"></td>
  </tr>
  <tr>
    <td colspan="2" >Fabricante    del Alternador</td>
    <td colspan="3" ><input type="text" name="pm_04_t11" id="pm_04_t11"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c11" id="pm_04_c11"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Modelo    Alternador</td>
    <td colspan="3" ><input type="text" name="pm_04_t12" id="pm_04_t12"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c12" id="pm_04_c12"></td>
  </tr>
  <tr>
    <td colspan="2" >Serie    Alternador</td>
    <td colspan="3" ><input type="text" name="pm_04_t13" id="pm_04_t13"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c13" id="pm_04_c13"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >N°    especificación alternador</td>
    <td colspan="3" ><input type="text" name="pm_04_t14" id="pm_04_t14"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c14" id="pm_04_c14"></td>
  </tr>
  <tr>
    <td colspan="2" >Fabricante    motor partida</td>
    <td colspan="3" ><input type="text" name="pm_04_t15" id="pm_04_t15"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c15" id="pm_04_c15"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >Modelo    motor partida</td>
    <td colspan="3" ><input type="text" name="pm_04_t16" id="pm_04_t16"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c16" id="pm_04_c16"></td>
  </tr>
  <tr>
    <td colspan="2" >Serie    Motor de Partida</td>
    <td colspan="3" ><input type="text" name="pm_04_t17" id="pm_04_t17"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c17" id="pm_04_c17"></td>
  </tr>
  <tr bgcolor="#eee">
    <td colspan="2" >N/P    Motor de Partida</td>
    <td colspan="3" ><input type="text" name="pm_04_t18" id="pm_04_t18"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_04_c18" id="pm_04_c18"></td>
  </tr>
</table>
	
	
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina5">
	<tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 5</td>
		</tr>
		<tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>COMPONENTES</strong></td>
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td width="254" rowspan="4" bgcolor="#eee" >BOMBA DE COMBUSTIBLE</td>
    <td width="1100" bgcolor="#eee" >CPL</td>
    <td width="259" bgcolor="#eee" ><input type="text" name="pm_05_t1" id="pm_05_t1"></td>
    <td width="228" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c1" id="pm_05_c1"></td>
  </tr>
  <tr>
    <td >Código</td>
    <td ><input type="text" name="pm_05_t2" id="pm_05_t2"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c2" id="pm_05_c2"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t3" id="pm_05_t3"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c3" id="pm_05_c3"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_05_t4" id="pm_05_t4"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c4" id="pm_05_c4"></td>
  </tr>
  <tr>
    <td rowspan="4" >HP-LBF</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t5" id="pm_05_t5"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c5" id="pm_05_c5"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_05_t6" id="pm_05_t6"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c6" id="pm_05_c6"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t7" id="pm_05_t7"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c7" id="pm_05_c7"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_05_t8" id="pm_05_t8"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c8" id="pm_05_c8"></td>
  </tr>
  <tr>
    <td rowspan="4" bgcolor="#eee" >HP-LBM</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t9" id="pm_05_t9"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c9" id="pm_05_c9"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_05_t10" id="pm_05_t10"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c10" id="pm_05_c10"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t11" id="pm_05_t11"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c11" id="pm_05_c11"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_05_t12" id="pm_05_t12"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c12" id="pm_05_c12"></td>
  </tr>
  <tr>
    <td rowspan="4" >HP-LBR</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t13" id="pm_05_t13"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c13" id="pm_05_c13"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_05_t14" id="pm_05_t14"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c14" id="pm_05_c14"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t15" id="pm_05_t15"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c15" id="pm_05_c15"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_05_t16" id="pm_05_t16"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c16" id="pm_05_c16"></td>
  </tr>
  <tr>
    <td rowspan="4" bgcolor="#eee" >HP-RBF</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t17" id="pm_05_t17"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c17" id="pm_05_c17"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_05_t18" id="pm_05_t18"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c18" id="pm_05_c18"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_05_t19" id="pm_05_t19"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_05_c19" id="pm_05_c19"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_05_t20" id="pm_05_t20"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_05_c20" id="pm_05_c20"></td>
  </tr>
</table>
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina6">
 <tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 6</td>
		</tr>
<tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>COMPONENTES</strong></td>
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>N/A</strong></td>
  </tr>  <tr>
    <td rowspan="4" bgcolor="#eee" >HP-RBM</td>
    <td width="1100" bgcolor="#eee" >Fabricante</td>
    <td width="259" bgcolor="#eee" ><input type="text" name="pm_06_t1" id="pm_06_t1"></td>
    <td width="228" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c1" id="pm_06_c1"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_06_t2" id="pm_06_t2"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c2" id="pm_06_c2"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t3" id="pm_06_t3"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c3" id="pm_06_c3"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_06_t4" id="pm_06_t4"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c4" id="pm_06_c4"></td>
  </tr>
  <tr>
    <td rowspan="4" >HP-RBR</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t5" id="pm_06_t5"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c5" id="pm_06_c5"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_06_t6" id="pm_06_t6"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c6" id="pm_06_c6"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t7" id="pm_06_t7"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c7" id="pm_06_c7"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_06_t8" id="pm_06_t8"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c8" id="pm_06_c8"></td>
  </tr>
  <tr>
    <td rowspan="4" bgcolor="#eee" >LP-LBF</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t9" id="pm_06_t9"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c9" id="pm_06_c9"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_06_t10" id="pm_06_t10"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c10" id="pm_06_c10"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t11" id="pm_06_t11"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c11" id="pm_06_c11"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_06_t12" id="pm_06_t12"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c12" id="pm_06_c12"></td>
  </tr>
  <tr>
    <td rowspan="4" >LP-LBM</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t13" id="pm_06_t13"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c13" id="pm_06_c13"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_06_t14" id="pm_06_t14"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c14" id="pm_06_c14"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t15" id="pm_06_t15"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c15" id="pm_06_c15"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_06_t16" id="pm_06_t16"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c16" id="pm_06_c16"></td>
  </tr>
  <tr>
    <td rowspan="4" bgcolor="#eee" >LP-LBR</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t17" id="pm_06_t17"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c17" id="pm_06_c17"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_06_t18" id="pm_06_t18"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c18" id="pm_06_c18"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_06_t19" id="pm_06_t19"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_06_c19" id="pm_06_c19"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_06_t20" id="pm_06_t20"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_06_c20" id="pm_06_c20"></td>
  </tr>
</table>
	
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina7">
  <tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 7</td>
		</tr>
		<tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>COMPONENTES</strong></td>
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td  align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>N/A</strong></td>
    </tr> 
  <tr>
    <td rowspan="4" bgcolor="#eee" >LP-RBF</td>
    <td width="1100" bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee"  ><input type="text" name="pm_07_t1" id="pm_07_t1"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c1" id="pm_07_c1"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_07_t2" id="pm_07_t2"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c2" id="pm_07_c2"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t3" id="pm_07_t3"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c3" id="pm_07_c3"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_07_t4" id="pm_07_t4"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c4" id="pm_07_c4"></td>
  </tr>
  <tr>
    <td rowspan="4" >LP-RBM</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t5" id="pm_07_t5"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c5" id="pm_07_c5"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_07_t6" id="pm_07_t6"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c6" id="pm_07_c6"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t7" id="pm_07_t7"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c7" id="pm_07_c7"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_07_t8" id="pm_07_t8"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c8" id="pm_07_c8"></td>
  </tr>
  <tr>
    <td rowspan="4" bgcolor="#eee" >LP-RBR</td>
    <td bgcolor="#eee" >Fabricante</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t9" id="pm_07_t9"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c9" id="pm_07_c9"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="pm_07_t10" id="pm_07_t10"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c10" id="pm_07_c10"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/P</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t11" id="pm_07_t11"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c11" id="pm_07_c11"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="pm_07_t12" id="pm_07_t12"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c12" id="pm_07_c12"></td>
  </tr>
  <tr>
    <td rowspan="6" >Sistema Eléctrico</td>
    <td bgcolor="#eee" >N/P    ECM QUANTUM</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t13" id="pm_07_t13"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c13" id="pm_07_c13"></td>
  </tr>
  <tr>
    <td >N/S ECM Quantum</td>
    <td ><input type="text" name="pm_07_t14" id="pm_07_t14"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c14" id="pm_07_c14"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >Código de calibración</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t15" id="pm_07_t15"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c15" id="pm_07_c15"></td>
  </tr>
  <tr>
    <td >N/P ECM Cense</td>
    <td ><input type="text" name="pm_07_t16" id="pm_07_t16"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c16" id="pm_07_c16"></td>
  </tr>
  <tr>
    <td bgcolor="#eee" >N/S ECM Cense</td>
    <td bgcolor="#eee" ><input type="text" name="pm_07_t17" id="pm_07_t17"></td>
    <td align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_07_c17" id="pm_07_c17"></td>
  </tr>
  <tr>
    <td >Código de calibración</td>
    <td ><input type="text" name="pm_07_t18" id="pm_07_t18"></td>
    <td align="center"><input type="checkbox" data-role="none" name="pm_07_c18" id="pm_07_c18"></td>
  </tr>
</table>
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina8">
<tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 8</td>
		</tr>
  <tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding:10px;" ><strong>Chequear durante la operación del motor</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Ruidos    anormales</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_01" id="pm_08_01s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_01" id="pm_08_01n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_01" id="pm_08_01a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Operación    del acelerador</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_02" id="pm_08_02s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_02" id="pm_08_02n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_02" id="pm_08_02a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Fugas    de combustible, lubricante, aceite o refrigerante</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_03" id="pm_08_03s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_03" id="pm_08_03n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_03" id="pm_08_03a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Medidores    defectuosos o inoperativos</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_04" id="pm_08_04s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_04" id="pm_08_04n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_04" id="pm_08_04a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Fallas    en el sistema de admisión de aire</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_05" id="pm_08_05s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_05" id="pm_08_05n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_08_05" id="pm_08_05a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Fallas    en el sistema de escape</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_06" id="pm_08_06s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_06" id="pm_08_06n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_08_06" id="pm_08_06a" value="N/A"></td>
  </tr>
  <tr>
    <td ></td>
    <td ></td>
    <td ></td>
    <td width="38"></td>
    <td width="95"></td>
    <td width="114"></td>
    <td width="76"></td>
    <td width="38"></td>
  </tr>
  <tr>
    <td colspan="2" ></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>MEDIDA</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Velocidad    motor en ralentí</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t1" id="pm_08_t1"></td>
    <td width="95" bgcolor="#eee">RPM</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c1" id="pm_08_c1"></td>
  </tr>
  <tr>
    <td colspan="2" >Presión    de aceite en ralentí</td>
    <td colspan="2" ><input type="text" name="pm_08_t2" id="pm_08_t2"></td>
    <td width="95">PSI</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c2" id="pm_08_c2"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Temperatura    de aceite</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t3" id="pm_08_t3"></td>
    <td width="95" bgcolor="#eee">°F</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c3" id="pm_08_c3"></td>
  </tr>
  <tr>
    <td colspan="2" >Temperatura    de refrigerante</td>
    <td colspan="2" ><input type="text" name="pm_08_t4" id="pm_08_t4"></td>
    <td width="95">°F</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c4" id="pm_08_c4"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Restricción    línea alimentación de combustible</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t5" id="pm_08_t5"></td>
    <td width="95" bgcolor="#eee">Inch    Hg</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c5" id="pm_08_c5"></td>
  </tr>
  <tr>
    <td colspan="2" >Restricción    línea retorno de combustible</td>
    <td colspan="2" ><input type="text" name="pm_08_t6" id="pm_08_t6"></td>
    <td width="95">Inch    Hg</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c6" id="pm_08_c6"></td>
  </tr>
  <tr>
    <td ></td>
    <td ></td>
    <td ></td>
    <td width="38"></td>
    <td width="95"></td>
    <td width="114" align="center"></td>
    <td width="76" align="center"></td>
    <td width="38" align="center"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>Registrar    las siguientes mediciones a través de los sistemas Quantum o Cense mientras    el motor está funcionando a máxima potencia/aceleración:</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>MEDIDA</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Velocidad    motor</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t7" id="pm_08_t7"></td>
    <td width="95" bgcolor="#eee">RPM</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c7" id="pm_08_c7"></td>
  </tr>
  <tr>
    <td colspan="2" >Presión    aceite lubricante</td>
    <td colspan="2" ><input type="text" name="pm_08_t8" id="pm_08_t8"></td>
    <td width="95">PSI</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c8" id="pm_08_c8"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Presión    combustible en el riel</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t9" id="pm_08_t9"></td>
    <td width="95" bgcolor="#eee">PSI</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c9" id="pm_08_c9"></td>
  </tr>
  <tr>
    <td colspan="2" >Temperatura    de escape</td>
    <td colspan="2" ><input type="text" name="pm_08_t10" id="pm_08_t10"></td>
    <td width="95">°F</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c10" id="pm_08_c10"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Temperatura    de agua motor</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t11" id="pm_08_t11"></td>
    <td width="95" bgcolor="#eee">°F</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c11" id="pm_08_c11"></td>
  </tr>
  <tr>
    <td colspan="2" >Temperatura    en el múltiple de admisión</td>
    <td colspan="2" ><input type="text" name="pm_08_t12" id="pm_08_t12"></td>
    <td width="95">°F</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c12" id="pm_08_c12"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >Potencia    de salida</td>
    <td colspan="2" bgcolor="#eee" ><input type="text" name="pm_08_t13" id="pm_08_t13"></td>
    <td width="95" bgcolor="#eee">BHP</td>
    <td colspan="3" align="center" bgcolor="#eee"><input type="checkbox" data-role="none" name="pm_08_c13" id="pm_08_c13"></td>
  </tr>
  <tr>
    <td colspan="2" >Presión    de blowby</td>
    <td colspan="2" ><input type="text" name="pm_08_t14" id="pm_08_t14"></td>
    <td width="95">Inch    H2O</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="pm_08_c14" id="pm_08_c14"></td>
  </tr>
</table>
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina9">
<tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 9</td>
		</tr>
  <tr>
    <td style="background-color:#333;color:#fff;padding:10px;">Observaciones</td>
   
  </tr>
  <tr>
    <td>
      <textarea name="pm_9_observaciones" id="pm_9_observaciones" style="width: 99%; height: 50px;"></textarea>
     </td>
  </tr>
 
</table>
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" style="display: none; margin-bottom:20px" class="sTable ppm ppmpagina10">
<tr class="title">
		<td colspan="8"  align="center" style="background-color: #1d1d1d; color: #fff !important;">Pauta de Puesta en Marcha Página 10</td>
		</tr>
  <tr>
    <td colspan="8" ><em>Nota: Para la revisión de los demás datos    operacionales se debe enviar una Trend Data o DataLogger Cense de la prueba    de potencia y una imagen Quantum (tomada en prueba de parrilla camiones    eléctricos).</em></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#333333"  style="color:#fff;padding:10px;"><strong>INSTRUIR    AL CLIENTE</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;" ><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >1.&nbsp;&nbsp;&nbsp;    Cambio de filtros de combustible y aceite lubricante</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_01" id="pm_10_01s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_01" id="pm_10_01n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_01" id="pm_10_01a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >2.&nbsp;&nbsp;&nbsp;    Cambio de aceite lubricante</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_02" id="pm_10_02s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_02" id="pm_10_02n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_02" id="pm_10_02a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >3.&nbsp;&nbsp;&nbsp;    Uso de combustible y aceite lubricante apropiados</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_03" id="pm_10_03s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_03" id="pm_10_03n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_03" id="pm_10_03a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >4.&nbsp;&nbsp;&nbsp;    Temperatura de operación</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_04" id="pm_10_04s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_04" id="pm_10_04n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_04" id="pm_10_04a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >5.&nbsp;&nbsp;&nbsp;    Procedimientos de partida y parada</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_05" id="pm_10_05s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_05" id="pm_10_05n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_05" id="pm_10_05a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >6.&nbsp;&nbsp;&nbsp;    Daños causados por sobre velocidad</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_06" id="pm_10_06s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_06" id="pm_10_06n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_06" id="pm_10_06a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >7.&nbsp;&nbsp;&nbsp;    Uso de inhibidores de corrosión y anticongelante</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_07" id="pm_10_07s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_07" id="pm_10_07n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_07" id="pm_10_07a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >8.&nbsp;&nbsp;&nbsp;    Uso de aparatos para partida en frio</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_08" id="pm_10_08s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_08" id="pm_10_08n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_08" id="pm_10_08a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >9.&nbsp;&nbsp;&nbsp;    Mantenimiento de filtrado de aire</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_09" id="pm_10_09s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_09" id="pm_10_09n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_09" id="pm_10_09a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >10.&nbsp;    Mantenimiento de correas</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_10" id="pm_10_10s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_10" id="pm_10_10n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_10" id="pm_10_10a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >11.&nbsp;    Cobertura de garantía</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_11" id="pm_10_11s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_11" id="pm_10_11n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_11" id="pm_10_11a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >12.&nbsp;    Información del manual del OEM (fabricante equipo)</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_12" id="pm_10_12s" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_12" id="pm_10_12n" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="pm_10_12" id="pm_10_12a" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>Confirme    la siguiente certificación del cliente</strong></td>
    <td colspan="2" align="center" bgcolor="#333333"  style="color:#fff;padding:10px;"><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>N/A</strong></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#eee" >El    cliente ha sido instruido sistemáticamente en ajustes de importancia,    operación segura, y cobertura de garantía del motor indicado en este reporte.    La inspección detallada anteriormente ha sido realizada y el chequeo de    motores marinos ha sido completado (solo en motores marinos).</td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_13" id="pm_10_13s" value="SI"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_13" id="pm_10_13n" value="NO"></td>
    <td colspan="2" align="center" bgcolor="#eee"><input type="radio" data-role="none" name="pm_10_13" id="pm_10_13a" value="N/A"></td>
  </tr>
</table>