$(function() {

/*
********************************************************
**	FIX Sincronizacion
**	- Primero, marcar trabajos con S y N
**	- Luego sincronizar trabajos con S y N
**	- Luego borrar trabajos con S y N
**	- Luego resincronizar trabajos con N (si hijo es != null es por que es continuación y no programado)
**	- Luego se genera revision de trabajos finalizados para borrar en bitacora
**
**
*********************************************************
**/
	
/* Form related plugins
================================================== */

	//===== Usual validation engine=====//

	$("#usualValidate").validate({
		rules: {
			firstname: "required",
			minChars: {
				required: true,
				minlength: 3
			},
			maxChars: {
				required: true,
				maxlength: 6
			},
			mini: {
				required: true,
				min: 3
			},
			maxi: {
				required: true,
				max: 6
			},
			range: {
				required: true,
				range: [6, 16]
			},
			emailField: {
				required: true,
				email: true
			},
			urlField: {
				required: true,
				url: true
			},
			dateField: {
				required: true,
				date: true
			},
			digitsOnly: {
				required: true,
				digits: true
			},
			enterPass: {
				required: true,
				minlength: 5
			},
			repeatPass: {
				required: true,
				minlength: 5,
				equalTo: "#enterPass"
			},
			customMessage: "required",
			
	
			topic: {
				required: "#newsletter:checked",
				minlength: 2
			},
			agree: "required"
		},
		messages: {
			customMessage: {
				required: "Bazinga! This message is editable",
			},
			agree: "Please accept our policy"
		}
	});



	//===== Input limiter =====//
	
	$('.lim').inputlimiter({
		limit: 100
		//boxClass: 'limBox',
		//boxAttach: false
	});


	//===== Multiple select with dropdown =====//
	
	$(".chzn-select").chosen(); 
	
	
	//===== Placeholder =====//
	
	$('input[placeholder], textarea[placeholder]').placeholder();
	
	
	//===== ShowCode plugin for <pre> tag =====//
	
	$('.showCode').sourcerer('js html css php'); // Display all languages
	$('.showCodeJS').sourcerer('js'); // Display JS only
	$('.showCodeHTML').sourcerer('html'); // Display HTML only
	$('.showCodePHP').sourcerer('php'); // Display PHP only
	$('.showCodeCSS').sourcerer('css'); // Display CSS only
	
	
	//===== Autocomplete =====//
	
	var availableTags = [ "ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme" ];
	$( "#ac" ).autocomplete({
	source: availableTags
	});	
	
	
	//===== Masked input =====//
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	$(".maskIntPhone").mask("+33 999 999 999");
	$(".maskTin").mask("99-9999999");
	$(".maskSsn").mask("999-99-9999");
	$(".maskProd").mask("a*-999-a999", { placeholder: " " });
	$(".maskEye").mask("~9.99 ~9.99 999");
	$(".maskPo").mask("PO: aaa-999-***");
	$(".maskPct").mask("99%");
	
	
	//===== Dual select boxes =====//
	
	$.configureBoxes();
	
	
	//===== Wizards =====//
	
	$("#wizard1").formwizard({
		formPluginEnabled: true, 
		validationEnabled: false,
		focusFirstInput : false,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status1").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#w1").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			resetForm: true
		}
	});
	
	$("#wizard2").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : false,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status2").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#w2").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},
		validationOptions : {
			rules: {
				bazinga: "required",
				email: { required: true, email: true }
			},
			messages: {
				bazinga: "Bazinga. This note is editable",
				email: { required: "Please specify your email", email: "Correct format is name@domain.com" }
			}
		}
	});
	
	$("#wizard3").formwizard({
		formPluginEnabled: false, 
		validationEnabled: false,
		focusFirstInput : false,
		disableUIStyles : true
	});
	
	
	//===== Validation engine =====//
	
	$("#validate").validationEngine();
	
	
	//===== WYSIWYG editor =====//
	
	$("#editor").cleditor({
		width:"100%", 
		height:"100%",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text"
	});
	
	
	//===== File uploader =====//
	
	$("#uploader").pluploadQueue({
		runtimes : 'html5,html4',
		url : 'php/upload.php',
		max_file_size : '1mb',
		unique_names : true,
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
			//{title : "Zip files", extensions : "zip"}
		]
	});
	
	
	//===== Tags =====//	
		
	$('#tags').tagsInput({width:'100%'});
		
		
	//===== Autogrowing textarea =====//
	
	$(".autoGrow").autoGrow();



/* General stuff
================================================== */


	//===== Left navigation styling =====//
	
	$('li.this').prev('li').css('border-bottom-color', '#2c3237');
	$('li.this').next('li').css('border-top-color', '#2c3237');
	
	/*$('.smalldd ul li').mouseover(
	function() { $(this).prev('li').css('border-bottom-color', '#3d434a') }
	);
	
	$('.smalldd ul li').mouseout(
	function() { $(this).prev('li').css('border-bottom-color', '#1c252a') }
	);*/

	//$('.smalldd ul li').next('li').css('border-top-color', '#2c3237');

	
	/*$('ul.nav li a').mouseover(
		function(){
		$(this).parent().prev('li').children("> a").addClass('bottomBorder'); 
		}
		);
		
		$('ul.nav li a').mouseout(
		function(){
		$(this).parent().prev('li').children("a").removeClass('bottomBorder'); 
		}
	);*/
	
	
	//===== User nav dropdown =====//		
	
	$('.dd').click(function () {
		$('.userDropdown').slideToggle(200);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("dd"))
		$(".userDropdown").slideUp(200);
	});
	
	$('.dd2').click(function () {
		$('.userDropdown2').slideToggle(200);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("dd2"))
		$(".userDropdown2").slideUp(200);
	});
	
	$('.dd3').click(function () {
		$('.userDropdown3').slideToggle(200);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("dd3"))
		$(".userDropdown3").slideUp(200);
	});
	  
	  
	  
	//===== Statistics row dropdowns =====//	
		
	$('.ticketsStats > h2 a').click(function () {
		$('#s1').slideToggle(150);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("ticketsStats"))
		$("#s1").slideUp(150);
	});
	
	
	$('.visitsStats > h2 a').click(function () {
		$('#s2').slideToggle(150);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("visitsStats"))
		$("#s2").slideUp(150);
	});
	
	
	$('.usersStats > h2 a').click(function () {
		$('#s3').slideToggle(150);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("usersStats"))
		$("#s3").slideUp(150);
	});
	
	
	$('.ordersStats > h2 a').click(function () {
		$('#s4').slideToggle(150);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("ordersStats"))
		$("#s4").slideUp(150);
	});
	
	
	
	//===== Collapsible elements management =====//
	
	$('.exp').collapsible({
		defaultOpen: 'current',
		cookieName: 'navAct',
		cssOpen: 'active',
		cssClose: 'inactive',
		speed: 200
	});
	
	$('.opened').collapsible({
		defaultOpen: 'opened,toggleOpened',
		cssOpen: 'inactive',
		cssClose: 'normal',
		speed: 200
	});
	
	$('.closed').collapsible({
		defaultOpen: '',
		cssOpen: 'inactive',
		cssClose: 'normal',
		speed: 200
	});
	
	
	$('.goTo').collapsible({
		defaultOpen: 'openedDrop',
		cookieName: 'smallNavAct',
		cssOpen: 'active',
		cssClose: 'inactive',
		speed: 100
	});
	
	/*$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("smalldd"))
		$(".smallDropdown").slideUp(200);
	});*/



	
	//===== Middle navigation dropdowns =====//
	
	$('.mUser').click(function () {
		$('.mSub1').slideToggle(100);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("mUser"))
		$(".mSub1").slideUp(100);
	});
	
	$('.mMessages').click(function () {
		$('.mSub2').slideToggle(100);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("mMessages"))
		$(".mSub2").slideUp(100);
	});
	
	$('.mFiles').click(function () {
		$('.mSub3').slideToggle(100);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("mFiles"))
		$(".mSub3").slideUp(100);
	});
	
	$('.mOrders').click(function () {
		$('.mSub4').slideToggle(100);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("mOrders"))
		$(".mSub4").slideUp(100);
	});



	//===== User nav dropdown =====//		
	
	$('.sidedd').click(function () {
		$('.sideDropdown').slideToggle(200);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("sidedd"))
		$(".sideDropdown").slideUp(200);
	});
	
	
	//$('.smalldd').click(function () {
	//	$('.smallDropdown').slideDown(200);
	//});





/* Tables
================================================== */


	//===== Check all checbboxes =====//
	
	$(".titleIcon input:checkbox").click(function() {
		var checkedStatus = this.checked;
		$("#checkAll tbody tr td:first-child input:checkbox").each(function() {
			this.checked = checkedStatus;
				if (checkedStatus == this.checked) {
					$(this).closest('.checker > span').removeClass('checked');
				}
				if (this.checked) {
					$(this).closest('.checker > span').addClass('checked');
				}
		});
	});	
	
	$('#checkAll tbody tr td:first-child').next('td').css('border-left-color', '#CBCBCB');
	
	
	
	//===== Resizable columns =====//
	
	$("#res, #res1").colResizable({
		liveDrag:true,
		draggingClass:"dragging" 
	});
	  
	  
	  
	//===== Sortable columns =====//
	
	$("table").tablesorter();
	
	
	
	//===== Dynamic data table =====//
	
	oTable = $('.dTable').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""l>t<"F"fp>'
	});





/* # Pickers
================================================== */


	//===== Color picker =====//
	
	$('#cPicker').ColorPicker({
		color: '#e62e90',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#cPicker div').css('backgroundColor', '#' + hex);
		}
	});
	
	$('#flatPicker').ColorPicker({flat: true});
	
	
	
	//===== Time picker =====//
	
	$('.timepicker').timeEntry({
		show24Hours: true, // 24 hours format
		showSeconds: true, // Show seconds?
		spinnerImage: 'images/forms/spinnerUpDown.png', // Arrows image
		spinnerSize: [19, 30, 0], // Image size
		spinnerIncDecOnly: true // Only up and down arrows
	});
	
	
	//===== Datepickers =====//
	
	$( ".datepicker" ).datepicker({ 
		defaultDate: +7,
		autoSize: true,
		appendText: '(dd-mm-yyyy)',
		dateFormat: 'dd-mm-yy',
	});	
	
	$( ".datepickerInline" ).datepicker({ 
		defaultDate: +7,
		autoSize: true,
		appendText: '(dd-mm-yyyy)',
		dateFormat: 'dd-mm-yy',
		numberOfMonths: 1
	});	


	








//===== Progress bars =====//
	
	// default mode
	$('#progress1').anim_progressbar();
	
	// from second #5 till 15
	var iNow = new Date().setTime(new Date().getTime() + 5 * 1000); // now plus 5 secs
	var iEnd = new Date().setTime(new Date().getTime() + 15 * 1000); // now plus 15 secs
	$('#progress2').anim_progressbar({start: iNow, finish: iEnd, interval: 1});
	
	// jQuery UI progress bar
	$( "#progress" ).progressbar({
			value: 80
	});
	
	
	
	//===== Animated progress bars =====//
	
	var percent = $('.progressG').attr('title');
	$('.progressG').animate({width: percent},1000);
	
	var percent = $('.progressO').attr('title');
	$('.progressO').animate({width: percent},1000);
	
	var percent = $('.progressB').attr('title');
	$('.progressB').animate({width: percent},1000);
	
	var percent = $('.progressR').attr('title');
	$('.progressR').animate({width: percent},1000);
	
	
	
	
	var percent = $('#bar1').attr('title');
	$('#bar1').animate({width: percent},1000);
	
	var percent = $('#bar2').attr('title');
	$('#bar2').animate({width: percent},1000);
	
	var percent = $('#bar3').attr('title');
	$('#bar3').animate({width: percent},1000);
	
	var percent = $('#bar4').attr('title');
	$('#bar4').animate({width: percent},1000);
	
	var percent = $('#bar5').attr('title');
	$('#bar5').animate({width: percent},1000);

	var percent = $('#bar6').attr('title');
	$('#bar6').animate({width: percent},1000);

	var percent = $('#bar7').attr('title');
	$('#bar7').animate({width: percent},1000);

	var percent = $('#bar8').attr('title');
	$('#bar8').animate({width: percent},1000);

	var percent = $('#bar9').attr('title');
	$('#bar9').animate({width: percent},1000);




/* Other plugins
================================================== */


	//===== File manager =====//
	
	$('#fm').elfinder({
		url : 'php/connector.php',
	});

	
	//===== Calendar =====//
	
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('.calendar').fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		editable: true,
		events: [
			{
				title: 'All day event',
				start: new Date(y, m, 1)
			},
			{
				title: 'Long event',
				start: new Date(y, m, 5),
				end: new Date(y, m, 8)
			},
			{
				id: 999,
				title: 'Repeating event',
				start: new Date(y, m, 2, 16, 0),
				end: new Date(y, m, 3, 18, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating event',
				start: new Date(y, m, 9, 16, 0),
				end: new Date(y, m, 10, 18, 0),
				allDay: false
			},
			{
				title: 'Background color could be changed',
				start: new Date(y, m, 30, 10, 30),
				end: new Date(y, m, d+1, 14, 0),
				allDay: false,
				color: '#5c90b5'
			},
			{
				title: 'Lunch',
				start: new Date(y, m, 14, 12, 0),
				end: new Date(y, m, 15, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday PARTY',
				start: new Date(y, m, 18),
				end: new Date(y, m, 20),
				allDay: false
			},
			{
				title: 'Clackable',
				start: new Date(y, m, 27),
				end: new Date(y, m, 29),
				url: 'http://themeforest.net/user/Kopyov'
			}
		]
	});
	
	
	
	
/* UI stuff
================================================== */


	//===== Sparklines =====//
	
	$('.negBar').sparkline('html', {type: 'bar', barColor: '#db6464'} );
	$('.posBar').sparkline('html', {type: 'bar', barColor: '#6daa24'} );
	$('.zeroBar').sparkline('html', {type: 'bar', barColor: '#4e8fc6'} ); 
	
	
	
	//===== Tooltips =====//
	
	$('.tipN').tipsy({gravity: 'n',fade: true});
	$('.tipS').tipsy({gravity: 's',fade: true});
	$('.tipW').tipsy({gravity: 'w',fade: true});
	$('.tipE').tipsy({gravity: 'e',fade: true});
	
		
	
	//===== Accordion =====//		
	
	$('div.menu_body:eq(0)').show();
	$('.acc .title:eq(0)').show().css({color:"#2B6893"});
	
	$(".acc .title").click(function() {	
		$(this).css({color:"#2B6893"}).next("div.menu_body").slideToggle(300).siblings("div.menu_body").slideUp("slow");
		$(this).siblings().css({color:"#404040"});
	});
	
	
	//===== Tabs =====//
		
	$.fn.contentTabs = function(){ 
	
		$(this).find(".tab_content").hide(); //Hide all content
		$(this).find("ul.tabs li:first").addClass("activeTab").show(); //Activate first tab
		$(this).find(".tab_content:first").show(); //Show first tab content
	
		$("ul.tabs li").click(function() {
			$(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
			$(this).addClass("activeTab"); //Add "active" class to selected tab
			$(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			$(activeTab).show(); //Fade in the active content
			return false;
		});
	
	};
	$("div[class^='widget']").contentTabs(); //Run function on any div with class name of "Content Tabs"
	
	
	
	//===== Notification boxes =====//
	
	$(".hideit").click(function() {
		$(this).fadeTo(200, 0.00, function(){ //fade
			$(this).slideUp(300, function() { //slide up
				$(this).remove(); //then remove from the DOM
			});
		});
	});	
	
	
	
	//===== Lightbox =====//
	
	$("a[rel^='lightbox']").prettyPhoto();
	
	
	
	//===== Image gallery control buttons =====//
	
	$(".gallery ul li").hover(
		function() { $(this).children(".actions").show("fade", 200); },
		function() { $(this).children(".actions").hide("fade", 200); }
	);
	
	
	//===== Spinner options =====//
	
	var itemList = [
		{url: "http://ejohn.org", title: "John Resig"},
		{url: "http://bassistance.de/", title: "J&ouml;rn Zaefferer"},
		{url: "http://snook.ca/jonathan/", title: "Jonathan Snook"},
		{url: "http://rdworth.org/", title: "Richard Worth"},
		{url: "http://www.paulbakaus.com/", title: "Paul Bakaus"},
		{url: "http://www.yehudakatz.com/", title: "Yehuda Katz"},
		{url: "http://www.azarask.in/", title: "Aza Raskin"},
		{url: "http://www.karlswedberg.com/", title: "Karl Swedberg"},
		{url: "http://scottjehl.com/", title: "Scott Jehl"},
		{url: "http://jdsharp.us/", title: "Jonathan Sharp"},
		{url: "http://www.kevinhoyt.org/", title: "Kevin Hoyt"},
		{url: "http://www.codylindley.com/", title: "Cody Lindley"},
		{url: "http://malsup.com/jquery/", title: "Mike Alsup"}
	];
	
	var opts = {
		'sDec': {decimals:2},
		'sStep': {stepping: 0.25},
		'sCur': {currency: '$'},
		'sInline': {},
		'sLink': {
			//
			// Two methods of adding external items to the spinner
			//
			// method 1: on initalisation call the add method directly and format html manually
			init: function(e, ui) {
				for (var i=0; i<itemList.length; i++) {
					ui.add('<a href="'+ itemList[i].url +'" target="_blank">'+ itemList[i].title +'</a>');
				}
			},
	
			// method 2: use the format and items options in combination
			format: '<a href="%(url)" target="_blank">%(title)</a>',
			items: itemList
		}
	};
	
	for (var n in opts)
		$("#"+n).spinner(opts[n]);
	
	$("button").click(function(e){
		if($(this)!=null&&$(this).attr('id')!=null){
			var ns = $(this).attr('id').match(/(s\d)\-(\w+)$/);
			if (ns != null)
				$('#'+ns[1]).spinner( (ns[2] == 'create') ? opts[ns[1]] : ns[2]);
		}
	});
	
	
	
	//===== UI dialog =====//
	
	$( "#dialog-message" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$( "#opener" ).click(function() {
		$( "#dialog-message" ).dialog( "open" );
		return false;
	});	
		
	
	
	//===== Breadcrumbs =====//
	
	$('#breadcrumbs').xBreadcrumbs();
	
		
		
	//===== jQuery UI sliders =====//	
	
	$( ".uiSlider" ).slider(); /* Usual slider */
	
	
	$( ".uiSliderInc" ).slider({ /* Increments slider */
		value:100,
		min: 0,
		max: 500,
		step: 50,
		slide: function( event, ui ) {
			$( "#amount" ).val( "$" + ui.value );
		}
	});
	$( "#amount" ).val( "$" + $( ".uiSliderInc" ).slider( "value" ) );
		
		
	$( ".uiRangeSlider" ).slider({ /* Range slider */
		range: true,
		min: 0,
		max: 500,
		values: [ 75, 300 ],
		slide: function( event, ui ) {
			$( "#rangeAmount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		}
	});
	$( "#rangeAmount" ).val( "$" + $( ".uiRangeSlider" ).slider( "values", 0 ) +" - $" + $( ".uiRangeSlider" ).slider( "values", 1 ));
			
			
	$( ".uiMinRange" ).slider({ /* Slider with minimum */
		range: "min",
		value: 37,
		min: 1,
		max: 700,
		slide: function( event, ui ) {
			$( "#minRangeAmount" ).val( "$" + ui.value );
		}
	});
	$( "#minRangeAmount" ).val( "$" + $( ".uiMinRange" ).slider( "value" ) );
	
	
	$( ".uiMaxRange" ).slider({ /* Slider with maximum */
		range: "max",
		min: 1,
		max: 100,
		value: 20,
		slide: function( event, ui ) {
			$( "#maxRangeAmount" ).val( ui.value );
		}
	});
	$( "#maxRangeAmount" ).val( $( ".uiMaxRange" ).slider( "value" ) );	



	//===== Form elements styling =====//
	
	$("input:checkbox, input:radio, input:file").uniform();

	$("#unidad_id").change(function(){
		//console.log($("#unidad_id").attr("unidad_id"));
		$('#esn').val("");
		var id = "";
		if($("#unidad_id").attr("unidad_id")!=undefined&&$("#unidad_id").attr("unidad_id")!=null&&$("#unidad_id").attr("unidad_id")!=""){
			//$("#unidad_id").val($("#unidad_id").attr("unidad_id"));
		}
		id = $("#unidad_id").val();
		if (id == "" || id == null || id == undefined) {
			return false;
		}
		
		var faena_id="";
		var flota_id="";
		
		if($("#faena_id")!=null&&$("#faena_id").val()!=undefined){
			faena_id=$("#faena_id").val();
		}
		
		if($("#flota_id")!=null&&$("#flota_id").val()!=undefined){
			flota_id=$("#flota_id").val();
		}
		
		$.get( "/Unidad/esn/" + id+"/"+faena_id+"/"+flota_id, function(data) {
			var obj = $.parseJSON(data);
			if (obj.Unidad != null && obj.Unidad.esn != null && obj.Unidad.esn != '') {
				$('#esn').val(obj.Unidad.esn);
				$('#esn').attr("readonly","readonly");
			}else{
				$('#esn').removeAttr("readonly");
			}
		});
		
		$("#tipointervencion").val("");
		$("#tipointervencion").change();
	});
	// Select de programacion
	$("#faena_id").change(function(){
		if ($('#flota_id').attr("noajax") != undefined && $('#flota_id').attr("noajax") != null && $('#flota_id').attr("noajax") == "1") {
			return false;
		}
		
		$('#flota_id').html("<option value=\"0\">Cargando...</opcion>\n");
		$("#unidad_id").prop("disabled", true);
		var html = '';
		html += "<option value=\"0\"></opcion>\n";
		var id = $("#faena_id").val();
		
		var flota_id = 0;
		if ($('#flota_id').attr("flota_id") != undefined && $('#flota_id').attr("flota_id") != "" && $('#flota_id').attr("flota_id") != 'undefined') {
			flota_id = $('#flota_id').attr("flota_id");
		}
		
		if (id == "undefined") {
			id = 0;	
		}
		
		$.get( "/Flota/select/" + id, function(data) {
			$("div#uniform-flota_id span").text("");
			$("div#uniform-unidad_id span").text("");
			var obj = $.parseJSON(data);
			$.each(obj, function(i, item) {
				var sel = '';
				if (item.UnidadDetalle.flota_id == flota_id) {
					sel = ' selected="selected"';
					//$("div#uniform-flota_id span").text(item.UnidadDetalle.flota);
				}
				html += "<option value=\""+item.UnidadDetalle.flota_id+"\""+sel+">"+item.UnidadDetalle.flota+"</opcion>\n";
			});
			$('#flota_id').html(html);
		});
	});
	
	$("#flota_id").change(function(){
		//console.log("js");
		if ($('#unidad_id').attr("noajax") != undefined && $('#unidad_id').attr("noajax") != null && $('#unidad_id').attr("noajax") == "1") {
			return false;
		}
		$('#unidad_id').html("<option value=\"0\"></opcion>\n");
		$("#unidad_id").prop("disabled", false);
		
		var unidad_id = 0;
		if ($('#unidad_id').attr("unidad_id") != undefined && $('#unidad_id').attr("unidad_id") != "") {
			unidad_id = $('#unidad_id').attr("unidad_id");
		}
		//console.log(unidad_id);
		var html = '';
		html += "<option value=\"0\"></opcion>\n";
		var flota_id = ''
		if ($('#flota_id').attr("flota_id") != undefined && $('#flota_id').attr("flota_id") != "") {
			flota_id = $('#flota_id').attr("flota_id");
			if($("#flota_id").val()!=undefined&&$("#flota_id").val()!=null&&$("#flota_id").val()!="0"&&$("#flota_id").val()!=$('#flota_id').attr("flota_id")){
				flota_id=$("#flota_id").val();
			}
		} else {
			flota_id = $("#flota_id").val();
		}
		
		//flota_id = $("#flota_id").val();
		//console.log(flota_id);
		var faena_id = $("#faena_id").val();
		//alert(faena_id);
		$.get( "/Unidad/select/?flota_id="+flota_id+"&faena_id=" + faena_id, function(data) {
			var obj = $.parseJSON(data);
			$.each(obj, function(i, item) {
				var sel = '';
				if (item.UnidadDetalle.id == unidad_id) {
					sel = 'selected="selected"';
				}
				html += "<option value=\""+item.UnidadDetalle.id+"\""+sel+">"+item.UnidadDetalle.unidad+"</opcion>\n";
			});
			$('#unidad_id').html(html);
		});
		if(unidad_id==0){
			//$("#unidad_id").val("");
		}
		$("#unidad_id").change();
		
		$("#tipointervencion").val("");
		$("#tipointervencion").change();
	});
	
	
	$("#categoria_id").change(function(){
		var sintoma_id = 0;
		if ($('#sintoma_id').attr("sintoma_id") != undefined && $('#sintoma_id').attr("sintoma_id") != "") {
			sintoma_id = $('#sintoma_id').attr("sintoma_id");
		}
		$('#sintoma_id').html("<option value=\"0\">Cargando...</opcion>\n");
		var html = '';
		html += "<option value=\"0\"></opcion>\n";
		var id = $("#categoria_id").val();
		
		if (id == "") {
			$('#sintoma_id').html("<option></opcion>\n");
			return;
		}
		
		$.get( "/Sintoma/select/" + id, function(data) {
			var obj = $.parseJSON(data);
			$.each(obj, function(i, item) {
				var sel = '';
				if (item.Sintoma.id == sintoma_id) {
					sel = 'selected="selected"';
				}
				if (id == "23") {
					html += "<option value=\""+item.Sintoma.id+"\""+sel+">"+item.Sintoma.codigo+" "+item.Sintoma.nombre+"</opcion>\n";
				} else {
					html += "<option value=\""+item.Sintoma.id+"\""+sel+">"+item.Sintoma.nombre+"</opcion>\n";
				}
			});
			$('#sintoma_id').html(html);
		});
		
		$('#sintoma_id').change();
	});
	
	
	
	
	
	$(".nomp").show();
	$(".mp").hide();
	$("#tipointervencion").change(function(){
		$(".info_backlog").hide();
		$(".info_backlog_comentario").hide();
		// Validamos que exista v
		if ($(this).attr("v")!=undefined&&$(this).attr("v")!=null&&$(this).attr("v")!=""&&$(this).val()==""){
			$(this).val($(this).attr("v"));
		}
		var val = $("#tipointervencion").val();
		if (val == "MP") {
			$(".nomp").hide();
			$(".bl").hide();
			$(".mp").show();
			$('#backlog_id').val("");
			$('#backlog_id').attr("backlog_id","");
			$(".info_backlog_comentario").text("");
			$('#categoria_id').val("");
			$('#sintoma_id').val("");
			$('#sintoma_id').attr("sintoma_id","");
		} else if (val == "BL") {
			$(".bl").show();
			$(".nomp").hide();
			$(".mp").hide();
			$('#categoria_id').val("");
			$('#sintoma_id').val("");
			$('#sintoma_id').attr("sintoma_id","");
			$(".info_backlog_comentario").text("");
			$('#tipomantencion').val("");
			var id = $("#unidad_id").val()
			if(id==null){
				var id=$("#unidad_id").attr("unidad_id");
			}
			$('#backlog_id').html("<option value=\"0\">Cargando...</opcion>\n");
			var html = '';
			$.get( "/Backlog/select/" + id, function(data) {
				var obj = $.parseJSON(data);
				html += "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					var criticidad = "";
					var responsable = "";
					var style = "";
					if (item.Backlog.criticidad == "1") {
						criticidad = "Alto";
						style = "background-color: red; color: white;";
					} else if (item.Backlog.criticidad == "2") {
						criticidad = "Medio";
						style = "background-color: yellow; color: black;";
					} else if (item.Backlog.criticidad == "3") {
						criticidad = "Bajo";
						style = "background-color: green; color: white;";
					}
					if(item.Backlog.responsable_id == "1"){
						responsable = "DCC";
					}else if (item.Backlog.responsable_id == "2"){
						responsable = "OEM";
					}else{
						responsable = "MINA";
					}
					var fecha = new Date(item.Backlog.fecha);
					fecha = fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear();
					if ($("#backlog_id").attr("backlog_id")!=null&&$("#backlog_id").attr("backlog_id")==item.Backlog.id) {
						html += "<option value=\""+item.Backlog.id+"\" selected=\"selected\" style=\""+style+"\" title=\""+item.Backlog.comentario+"\">"+fecha+" | "+ responsable +" | "+criticidad+" | "+item.Sistema.nombre+"</opcion>\n";
						$(".info_backlog_comentario").text("Comentario: " + item.Backlog.comentario);
					} else {
						html += "<option value=\""+item.Backlog.id+"\" style=\""+style+"\" title=\""+item.Backlog.comentario+"\">"+fecha+" | "+ responsable +" | "+criticidad+" | "+item.Sistema.nombre+"</opcion>\n";
					}
				});
				$('#backlog_id').html(html);
			});
			$('#backlog_id').change();
		} else {
			$(".nomp").show();
			$(".mp").hide();
			$(".bl").hide();
			$('#backlog_id').val("");
			$('#backlog_id').attr("backlog_id","");
			$(".info_backlog_comentario").text("");
			$('#tipomantencion').val("");
		}
	});
	
	$("#backlog_id").change(function(){
		$(".info_backlog").show();
		$(".info_backlog_comentario").show();
		if ($(this).val() != "" && $(this).val() != "0" && $.isNumeric($(this).val())) {
			console.log("val");
			$(".info_backlog_comentario").text($(this).find("option:selected").attr("title"));
		} else if ($("#backlog_id").attr("backlog_id")!=null&&$("#backlog_id").attr("backlog_id")!=""&&$.isNumeric($("#backlog_id").attr("backlog_id"))) {
			//console.log("attr");
			//c/onsole.log($(this).attr("backlog_id"));
			//console.log($(this).find("option[value='"+$(this).attr("backlog_id")+"']").prevObject[0].selectedOptions);
			//console.log($('#backlog_id option[value="'+$("#backlog_id").attr("backlog_id")+'"]').attr("title"));
			//$(".info_backlog_comentario").text($('#backlog_id option[value="'+$("#backlog_id").attr("backlog_id")+'"]').attr("title"));
		} else {
			$(".info_backlog").hide();
			$(".info_backlog_comentario").hide();
			$(".info_backlog_comentario").text("");
		}
	});
	
	if (window.location.toString().indexOf("/Trabajo/ex/") > 5 || window.location.toString().indexOf("/Trabajo/ri/") > 10 || window.location.toString().indexOf("/Trabajo/bl/") > 10 || window.location.toString().indexOf("/Trabajo/mp/") > 10) {
		$("#flota_id").unbind("change");
		$("#faena_id").unbind("change");
		$("#flota_id").unbind("click");
		$("#equipo_id").unbind("click");
		$("#motivo_llamado").unbind("click");
		$("#categoria_sintoma").unbind("click");
		$("#sintoma").unbind("click");
	} else {
		$("#faena_id").change();
		$("#categoria_id").change();
		$("#tipointervencion").change();
		$("#flota_id").change();
	}
	
	$("#fecha_despliegue").change(function(){
		var fd = $(this).val();
		if (fd != '' && fd != 'dd-mm-aaaa') {
			window.location = '/Planificacion/?fd='+fd;
		}
		return;
	});
	
	$("#fecha_resumen").change(function(){
		var fd = $(this).val();
		if (fd != '' && fd != 'dd-mm-aaaa') {
			window.location = '/Planificacion/historial?fecha='+fd;
		}
		return;
	});
	
	$('#lotes_entra').tagsInput({
		'width':'150px;',
		'height':'30px',
		'defaultText':'Entrada',
		'removeWithBackspace' : true,
	});
	
	$('#lotes_sale').tagsInput({
		'width':'150px;',
		'height':'30px',
		'defaultText':'Salida',
		'removeWithBackspace' : true,
	});
	
	$('#os_sap').tagsInput({
		'width':'150px;',
		'height':'30px',
		'defaultText':'',
		'removeWithBackspace' : true,
	});
	
	/* Customizacion de Sitio DCC */
	$(".show").click(function(){
		var show = $(this).attr("show");
		$("."+show).toggle(400, function() {
			// Animation complete.
		});
	});
	
	$("#confimar_vaciar").click(function (){
		alert("Por seguridad, esta característica se encuentra temporalmente deshabilitada.");
		return false;
		/*
		if (confirm("Realmente desea vaciar la base de datos, eliminara todas las planificaciones. Recuerda eliminar tambien la base de datos local de Bitacora!")) {
			return true;
		}
		return false;
		*/
	});
	
	// Muestra comentario
	$(".mostrar_comentario").click(function(){
		$("#transparentbg").css({ opacity: 0.6, "zIndex": 1011, width: "100%", height: "100%" });
		$(".box_comentario").hide();
		var id = $(this).attr("comentario");
		$("#" + id).css({ "zIndex": 1012 });
		$("#" + id).show();
	});
	
	$(".cerrar_comentario").click(function(){
		$(".box_comentario").hide();
		$("#transparentbg").css({ opacity: 0, "zIndex": 0, width: "0", height: "0" });
	});
	
	$("#transparentbg").click(function(){
		$(".cerrar_comentario").click();
		$(".cerrar_delta").click();
	});
	
	$("#cambio_faena").change(function(){
		var val = $(this).val();
		if ($.isNumeric(val)) {
			window.location = "/Nivel/faena/" + val;
			return false;
		}
	});
	
	// Filtro tablas
	$("#flota_id_h").change(function(){
		var unidad_id = 0;
		if ($('#unidad_id_h').attr("unidad_id") != undefined && $('#unidad_id_h').attr("unidad_id") != "") {
			unidad_id = $('#unidad_id_h').attr("unidad_id");
		}
		
		var html = '';
		html += "<option value=\"\">Todos</opcion>\n";
		var flota_id = ''
		/*if ($('#flota_id').attr("flota_id") != undefined && $('#flota_id').attr("flota_id") != "") {
			flota_id = $('#flota_id').attr("flota_id");
		} else {*/
			flota_id = $("#flota_id_h").val();
		//}
		var faena_id = $("#faena_id").val();
		//alert(faena_id);
		$.get( "/Unidad/select/?flota_id="+flota_id+"&faena_id=" + faena_id, function(data) {
			var obj = $.parseJSON(data);
			$.each(obj, function(i, item) {
				var sel = '';
				if (item.UnidadDetalle.id == unidad_id) {
					sel = 'selected="selected"';
				}
				html += "<option value=\""+item.UnidadDetalle.id+"\""+sel+">"+item.UnidadDetalle.unidad+"</opcion>\n";
			});
			$('#unidad_id_h').html(html);
		});
	});
	
	$("#flota_id_h").change();
	
	$("#ocultar_menu").toggle(
	function(e){
		// Cerrar
		$("body").css({
			"background-size": "50px"
		});
		
		$("#leftSide").css({
			"width": "44px"
		});
		
		$(".logo").css({
			"display": "none"
		});
		
		$(".sidebarSep").css({
			"display": "none"
		});
		$(".userNav").css({
			"margin-right": "64px"
		});
		
		$("#menu").css({
			"display": "none"
		});
		$("#imagen_menu_div").css({
			"margin-top": "27px"
		});
		$(".imagen_menu").attr("src", "/images/arrow_right.png");
		$(".imagen_menu").attr("title", "Mostrar menú");
		localStorage.setItem("menu", "collapsed");
		
		return false;
	},
	function(e){ 
		localStorage.removeItem("menu");
		$("body").css({ 
			"background-size": "220px"
		});
		$("#leftSide").css({
			"width": "216px"
		});
		$(".logo").css({
			"display": "block"
		});
		$(".sidebarSep").css({
			"display": "block"
		});
		  $(".userNav").css({
			"margin-right": "214px"
		});
		$("#menu").css({
			"display": "block"
		});
		
		$("#imagen_menu_div").css({
			"margin-top": "10px"
		});
		$(".imagen_menu").attr("title", "Ocultar menú");
		$(".imagen_menu").attr("src", "/images/arrow_left.png");
		
		return false;
	});
	
	if (localStorage.getItem("menu") != null && localStorage.getItem("menu") == "collapsed") {
		$("#ocultar_menu").click();
	}
	
	$("#tipo_evento").change(function(){
		var val = $(this).val();
		var sel = $('#tipo_intervencion').attr("tipo_intervencion");
		var html = '';
		html += "<option value=\"\">Todos</opcion>\n";
		if (val == "PR") {
			html += "<option value=\"MP\""+ (sel == "MP" ? " selected=\"selected\"" : "") +">MP</opcion>\n";
			html += "<option value=\"RP\""+ (sel == "RP" ? " selected=\"selected\"" : "") +">RP</opcion>\n";
			html += "<option value=\"OP\""+ (sel == "OP" ? " selected=\"selected\"" : "") +">OP</opcion>\n";
		} else if (val == "NP") {
			html += "<option value=\"EX\""+ (sel == "EX" ? " selected=\"selected\"" : "") +">EX</opcion>\n";
			html += "<option value=\"RI\""+ (sel == "RI" ? " selected=\"selected\"" : "") +">RI</opcion>\n";
		}
		$('#tipo_intervencion').html(html);
	});
	
	$("#tipo_evento").change();
	
	// Muestra comentario
	$(".show_delta_1").click(function(){
		$("#transparentbg").css({ opacity: 0.6, "zIndex": 1011, width: "100%", height: "100%" });
		$(".contenedor_delta").hide();
		$("#show_delta_1").css({ "zIndex": 1012 });
		$(".cerrar_delta").css({ "zIndex": 1013 });
		$("#show_delta_1").show();
	});
	
	$(".show_delta_2").click(function(){
		$("#transparentbg").css({ opacity: 0.6, "zIndex": 1011, width: "100%", height: "100%" });
		$(".contenedor_delta").hide();
		$("#show_delta_2").css({ "zIndex": 1012 });
		$(".cerrar_delta").css({ "zIndex": 1013 });
		$("#show_delta_2").show();
	});
	
	$(".show_delta_3").click(function(){
		/**/
		var fecha_intervencion = $(".fecha_intervencion_data").text();
		fecha_intervencion = fecha_intervencion.split(" ");
		var fecha_intervencion2 = fecha_intervencion[0].split("-");
		fecha_intervencion = fecha_intervencion2[2] + "-"+fecha_intervencion2[1]+"-"+fecha_intervencion2[0]+" "+fecha_intervencion[1]+" "+fecha_intervencion[2];
		
		var fecha_operacion = $(".fecha_operacion_data").text();
		fecha_operacion = fecha_operacion.split(" ");
		var fecha_operacion2 = fecha_operacion[0].split("-");
		fecha_operacion = fecha_operacion2[2] + "-"+fecha_operacion2[1]+"-"+fecha_operacion2[0]+" "+fecha_operacion[1]+" "+fecha_operacion[2];
		
		//console.log(fecha_intervencion);
		//console.log(fecha_operacion);
		var diff = (new Date(fecha_operacion)) - (new Date(fecha_intervencion));
		console.log(diff);
		diff = diff / (60 * 1000);
		var hora = Math.floor(diff / 60);
		var minutos = diff - hora * 60;
		console.log(diff);
		$("#delta_3").text(hora + "h " + minutos + "m");
		$("#d3_ing").text(hora + "h " + minutos + "m");
		$("#transparentbg").css({ opacity: 0.6, "zIndex": 1011, width: "100%", height: "100%" });
		$(".contenedor_delta").hide();
		$("#show_delta_3").css({ "zIndex": 1012 });
		$(".cerrar_delta").css({ "zIndex": 1013 });
		
		/**actualizamos data*/
		if($(".fecha_operacion_data").text()!=undefined&&$(".fecha_operacion_data").text()!="N/A"){
			$(".minuto").change();
			$(".delta_hora").change();
		}
		$("#show_delta_3").show();
	});
	
	$(".cerrar_delta").click(function(){
		$(".contenedor_delta").hide();
		$("#transparentbg").css({ opacity: 0, "zIndex": 0, width: "0", height: "0" });
	});
	/*
	$(".cerrar_comentario").click(function(){
		$(".box_comentario").hide();
		$("#transparentbg").css({ opacity: 0, "zIndex": 0, width: "0", height: "0" });
	});*/
	
	/* Fix hora de equipo a operacion */
	$(".operacion_change").change(function(){
		$(".show_delta_3").css("color","black");
		$(".show_delta_3").css("background-color","#F3F781");
		$(".fecha_operacion_data").text("N/A");
		if ($("#fecha_operacion").val() != "" && $("#hora_operacion").val() != "" && $("#minutos_operacion").val() != "" && $("#periodo_operacion").val() != "") {
			var fo = (new Date($("#fecha_operacion").val() + " " + $("#hora_operacion").val() + ":" + $("#minutos_operacion").val() + " " + $("#periodo_operacion").val())).getTime();
			var ft = (new Date($("#fecha_termino").val() + " " + $("#hora_termino").val() + ":" + $("#minuto_termino").val() + " " + $("#periodo_termino").val())).getTime();
			if(fo<ft){
				alert("La fecha de equipo a operación ingresada no es válida.");
				$("#fecha_operacion").val("");
				$("#hora_operacion").val("");
				$("#minutos_operacion").val("");
				$("#periodo_operacion").val("");
				return false;
			}
			if(fo==ft){
				$(".show_delta_3").css("color","white");
				$(".show_delta_3").css("background-color","green");
			}	
			var fecha_operacion = $("#fecha_operacion").val().split("-");
			$(".fecha_operacion_data").text(fecha_operacion[2]+"-"+fecha_operacion[1]+"-"+fecha_operacion[0] + " " + $("#hora_operacion").val() + ":" + $("#minutos_operacion").val() + " " + $("#periodo_operacion").val());
			
		} else {
			$(".fecha_operacion_data").text("N/A");
		}
	});
	
	$(".operacion_change").change();
	
	$(".cliente_aprobar").click(function(){
		if (confirm("¿Realmente desea aprobar esta intervención?")) {
			var comentario = prompt("Ingrese comentario de la aprobación:", "");
			var id = $(this).attr("planificacion_id");
			$("#comentario_" + id).val(comentario);
			return true;
		}
		return false;
	});
	
	$(".cliente_rechazar").click(function(){
		if (confirm("¿Realmente desea rechazar esta intervención?")) {
			var comentario = prompt("Ingrese comentario del rechazo:", "");
			var id = $(this).attr("planificacion_id");
			$("#comentario_" + id).val(comentario);
			return true;
		}
		return false;
	});
	
	
	/* Resumen planificacion */
	$("#motivo_llamado").change(function(){
		var val = $(this).val();
		var sintoma_id = $("#categoria_sintoma").attr("categoria_sintoma_id");
		if (val == "FC") {
			$("#categoria_sintoma").val("23");
		} else if (val == "OT") {
			$("#categoria_sintoma").val("16");
		} else if (val != "") {
			$("#categoria_sintoma").val(sintoma_id);
		}
		$("#categoria_sintoma").change();
	});
	
	$("#categoria_sintoma").change(function(){
		var val = $(this).val();
		var sintoma_id = $("#sintoma").attr("sintoma_id");
		if (val != "") {
			$('#sintoma').html("<option value=\"0\">Cargando...</opcion>\n");
			var html = '';
			html += "<option value=\"0\"></opcion>\n";
			$.get( "/Sintoma/select/" + val, function(data) {
				var obj = $.parseJSON(data);
				$.each(obj, function(i, item) {
					var sel = '';
					if (item.Sintoma.id == sintoma_id) {
						sel = 'selected="selected"';
					}
					if (val == "23") {
						html += "<option value=\""+item.Sintoma.id+"\""+sel+">"+item.Sintoma.codigo+" "+item.Sintoma.nombre+"</opcion>\n";
					} else {
						html += "<option value=\""+item.Sintoma.id+"\""+sel+">"+item.Sintoma.nombre+"</opcion>\n";
					}
				});
				$('#sintoma').html(html);
			});
			
			$('#sintoma').change();
		}
	});
	
	$("#motivo_llamado").change();
	$("#categoria_sintoma").change();
	
	/*$("#horometro_cabina").change(function(){
		var val = $(this).val();
		if (val == "") {
			return;
		}
		val = val.replace(",", ".");
		if (!$.isNumeric(val)) {
			alert("El horometro ingresado no es valido!");
			$("#horometro_cabina").val("");
			$("#horometro_cabina").change();
			$("#horometro_cabina").focus();
			return false;
		}
		
		// Por definicion de requerimientos, el horometro tiene dos decimales.
		var val = parseFloat(val).toFixed(2);
		val = val.replace(",", ".");
		if (val < 0.00 || val > 120000.00) {
			alert("El valor de horometro ingresado no es valido!");
			$("#horometro_cabina").val("");
			$("#horometro_cabina").change();
			$("#horometro_cabina").focus();
			return false;
		} else {
			$(this).val(val);
		}
	});*/
	
	$(".horometro").change(function(){
		var val = $(this).val();
		if (val == "") {
			return;
		}
		val = val.replace(",", ".");
		if (!$.isNumeric(val)) {
			alert("El horometro ingresado no es valido!");
			$(".horometro").val("");
			$(".horometro").change();
			$(".horometro").focus();
			return false;
		}
		
		// Por definicion de requerimientos, el horometro tiene dos decimales.
		var val = parseFloat(val).toFixed(2);
		val = val.replace(",", ".");
		if (val < 0.00 || val > 120000.00) {
			alert("El valor de horometro ingresado no es valido!");
			$(".horometro").val("");
			$(".horometro").change();
			$(".horometro").focus();
			return false;
		} else {
			$(this).val(val);
		}
		
		if ($("#horometro_cabina").val() != undefined && $("#horometro_final").val() != undefined) {
			if (parseFloat($("#horometro_cabina").val()) > parseFloat($("#horometro_final").val())) {
				alert("El horometro ingresado no es válido!");
				$(this).val("");
				$(this).focus();
				return false;
			}
		}
		
		if ($("#horometro_cabina").val() != undefined && $("#horometro_pm").val() != undefined) {
			if (parseFloat($("#horometro_cabina").val()) > parseFloat($("#horometro_pm").val())) {
				alert("El horometro ingresado no es válido!");
				$(this).val("");
				$(this).focus();
				return false;
			}
		}
	});
	
	$(".delta_hora").change(function(){
		var val = $(this).val();
		if (val == "") {
			$(this).val("0");
			return;
		}
		if (!$.isNumeric(val)) {
			$(this).val("0");
			return;
		}
		if (parseInt(val) < 0) {
			$(this).val("0");
			return;
		}
		var id_ = $(this).attr("id");
		var id = $(this).attr("id").substr(0, 3);
		var total = 0;
		var delta = 0;
		if (id == "ds1") {
			var d = $("#delta_1").text();
			d = d.replace("h ", "*60+");
			delta = eval(d.replace("m", ""));
		} else if (id == "ds2") {
			var d = $("#delta_2").text();
			d = d.replace("h ", "*60+");
			delta = eval(d.replace("m", ""));
		} else if (id == "ds3") {
			var d = $("#delta_3").text();
			d = d.replace("h ", "*60+");
			delta = eval(d.replace("m", ""));
		}
		// Sumamos todas las horas y minutos
		$.each($(".delta_hora"), function(i, item) {
			var sel = '';
			if (item.id.substr(0, 3) == id) {
				if (total + $("#"+item.id).val() * 60 <= delta) {
					total += $("#"+item.id).val() * 60;
				} else {
					alert("Ha ingresado un tiempo mayor al delta disponible.");
					$("#"+id_).val("0");
					$("#"+id_).focus();
					return false;
				}
			}
			//console.dir(item.id.substr(0, 3)==id);
		});
		
		$.each($(".minuto"), function(i, item) {
			var sel = '';
			if (item.id.substr(0, 3) == id) {
				if (total + parseInt($("#"+item.id).val()) <= delta) {
					total += parseInt($("#"+item.id).val());
				} else {
					alert("Ha ingresado un tiempo mayor al delta disponible.");
					$("#"+id_).val("0");
					$("#"+id_).focus();
					return false;
				}
			}
			//console.dir(item.id.substr(0, 3)==id);
		});
		
		// Delta change
		if(id=="ds3"){
			if($("#fecha_operacion").val() != undefined && $("#hora_operacion").val() != undefined && $("#minutos_operacion").val() != undefined && $("#periodo_operacion").val() != undefined) {
				var i_i = (new Date($("#fecha_termino").val() + " " + $("#hora_termino").val() + ":"+$("#minuto_termino").val() +":00 "+$("#periodo_termino").val())).getTime()/60000;
				var i_t = (new Date($("#fecha_operacion").val() + " " + $("#hora_operacion").val() + ":"+$("#minutos_operacion").val() +":00 "+$("#periodo_operacion").val())).getTime()/60000;
				var disp=i_t-i_i;
				if(disp==total){
					
						$(".show_delta_1").css("background-color","green");
						$(".show_delta_1").css("color","white");
					
				}else{
					//console.log("d3 incompleto");
					$(".show_delta_3").css("background-color","#F3F781");
					$(".show_delta_3").css("color","black");
				}
				total=disp-total;
				var hours = Math.floor(total/60);          
				var minutes = total%60;
				$("#d3_ing").text(hours+"h "+minutes+"m");
			}
		}
		if(id=="ds1"){
			if($("#f_t_a").val() != undefined && $("#f_t_a").val() != "") {
				//console.log(new Date($("#f_t_a").val()));
				//console.log(new Date($("#f_i_i").val() + " " + $("#h_i_i").val() + ":"+$("#m_i_i").val() +":00 "+$("#p_i_i").val()));
				var i_i = (new Date($("#f_t_a").val())).getTime()/60000;
				var i_t = (new Date($("#f_i_i").val() + " " + $("#h_i_i").val() + ":"+$("#m_i_i").val() +":00 "+$("#p_i_i").val())).getTime()/60000;
				var disp=i_t-i_i;
				//console.log(total);
				//console.log(disp);
				if(disp==total){
					
						$(".show_delta_1").css("background-color","green");
						$(".show_delta_1").css("color","white");
					
				}else{
					//console.log("d1 incompleto");
					$(".show_delta_1").css("background-color","#F3F781");
					$(".show_delta_1").css("color","black");
				}
				total=disp-total;
				var hours = Math.floor(total/60);          
				var minutes = total%60;
				$("#d1_ing").text(hours+"h "+minutes+"m");
			}
		}
	});
	
	$(".minuto").change(function(){
		var val = $(this).val();
		if (val == "") {
			$(this).val("00");
			return;
		}
		if (!$.isNumeric(val)) {
			$(this).val("00");
			return;
		}
		if (parseInt(val) < 0) {
			$(this).val("00");
			return;
		}
		var id_ = $(this).attr("id");
		var id = $(this).attr("id").substr(0, 3);
		var total = 0;
		var delta = 0;
		if (id == "ds1") {
			var d = $("#delta_1").text();
			d = d.replace("h ", "*60+");
			delta = eval(d.replace("m", ""));
		} else if (id == "ds2") {
			var d = $("#delta_2").text();
			d = d.replace("h ", "*60+");
			delta = eval(d.replace("m", ""));
		} else if (id == "ds3") {
			var d = $("#delta_3").text();
			d = d.replace("h ", "*60+");
			delta = eval(d.replace("m", ""));
		}
		// Sumamos todas las horas y minutos
		$.each($(".delta_hora"), function(i, item) {
			var sel = '';
			if (item.id.substr(0, 3) == id) {
				if (total + parseInt($("#"+item.id).val())*60 <= delta) {
					total += parseInt($("#"+item.id).val())*60;
				} else {
					alert("Ha ingresado un tiempo mayor al delta disponible.");
					$("#"+id_).val("00");
					$("#"+id_).focus();
					return false;
				}
			}
			//console.dir(item.id.substr(0, 3)==id);
		});
		
		$.each($(".minuto"), function(i, item) {
			var sel = '';
			if (item.id.substr(0, 3) == id) {
				if (total + parseInt($("#"+item.id).val()) <= delta) {
					total += parseInt($("#"+item.id).val());
				} else {
					alert("Ha ingresado un tiempo mayor al delta disponible.");
					$("#"+id_).val("00");
					$("#"+id_).focus();
					return false;
				}
			}
			//console.dir(item.id.substr(0, 3)==id);
		});
		// Delta change
		if(id=="ds3"){
			if($("#fecha_operacion").val() != undefined && $("#hora_operacion").val() != undefined && $("#minutos_operacion").val() != undefined && $("#periodo_operacion").val() != undefined) {
				var i_i = (new Date($("#fecha_termino").val() + " " + $("#hora_termino").val() + ":"+$("#minuto_termino").val() +":00 "+$("#periodo_termino").val())).getTime()/60000;
				var i_t = (new Date($("#fecha_operacion").val() + " " + $("#hora_operacion").val() + ":"+$("#minutos_operacion").val() +":00 "+$("#periodo_operacion").val())).getTime()/60000;
				var disp=i_t-i_i;
				if(disp==total){
				
						$(".show_delta_1").css("background-color","green");
						$(".show_delta_1").css("color","white");
				
				}else{
					//console.log("d3 incompleto");
					$(".show_delta_3").css("background-color","#F3F781");
					$(".show_delta_3").css("color","black");
				}
				total=disp-total;
				var hours = Math.floor(total/60);          
				var minutes = total%60;
				$("#d3_ing").text(hours+"h "+minutes+"m");
				
			}
		}
		if(id=="ds1"){
			if($("#f_t_a").val() != undefined && $("#f_t_a").val() != "") {
				var i_i = (new Date($("#f_t_a").val())).getTime()/60000;
				var i_t = (new Date($("#f_i_i").val() + " " + $("#h_i_i").val() + ":"+$("#m_i_i").val() +":00 "+$("#p_i_i").val())).getTime()/60000;
				var disp=i_t-i_i;
				if(disp==total){
					$(".show_delta_1").css("background-color","green");
					$(".show_delta_1").css("color","white");
				}else{
					//console.log("d1 incompleto");
					$(".show_delta_1").css("background-color","#F3F781");
					$(".show_delta_1").css("color","black");
				}
				total=disp-total;
				var hours = Math.floor(total/60);          
				var minutes = total%60;
				$("#d1_ing").text(hours+"h "+minutes+"m");
			}
		}
	});

	
	$.each($(".delta_hora"), function(i, item) {
		var val = $(this).val();
		if (val == "") {
			$(this).val("0");
		}
	});
	
	$("#btnGuadarBorrador").click(function(){
		if ($("#flota_id").val() == "0") {
			alert("Debe seleccionar una flota.");
			$("#flota_id").focus();
			return false;
		}
		if ($("#unidad_id").val() == "0") {
			alert("Debe seleccionar un equipo.");
			$("#unidad_id").focus();
			return false;
		}
		if ($("#programacion_fecha").val() == "") {
			alert("Debe ingresar una fecha de programación.");
			$("#programacion_fecha").focus();
			return false;
		} else {
			// Validar fecha
			var fecha_max = new Date(new Date().setYear(new Date().getFullYear() + 1));
			var fecha_min = new Date(new Date().setYear(new Date().getFullYear() - 1));
			var fecha = new Date($("#programacion_fecha").val());
			//console.log(fecha);
			//console.log(fecha_min);
			//console.log(fecha_max);
			//console.log(fecha > fecha_max);
			//console.log(fecha < fecha_min);
			if (fecha > fecha_max || fecha < fecha_min) {
				alert("Debe ingresar una fecha de programación válida.");
				return false;
			}
		}
		if ($("#programacion_hora").val() == "" || $("#programacion_minuto").val() == "" || $("#programacion_periodo").val() == "") {
			alert("Debe ingresar una hora de programación.");
			$("#programacion_hora").focus();
			return false;
		}
		if ($("#tipointervencion").val() == "") {
			alert("Debe seleccionar un tipo de intervención.");
			$("#tipointervencion").focus();
			return false;
		}
		// Caso Backlog
		if ($("#tipointervencion").val() == "BL") {
			if ($("#backlog_id").val() == "") {
				alert("Debe seleccionar un backlog.");
				$("#backlog_id").focus();
				return false;
			}
		}
		// Caso MP
		if ($("#tipointervencion").val() == "MP") {
			if ($("#tipomantencion").val() == "") {
				alert("Debe seleccionar un tipo de mantención.");
				$("#tipomantencion").focus();
				return false;
			}
		}
		// Caso RP
		if ($("#tipointervencion").val() == "RP") {
			if ($("#categoria_id").val() == "") {
				alert("Debe seleccionar una categoría.");
				$("#categoria_id").focus();
				return false;
			}
			if ($("#sintoma_id").val() == "0") {
				alert("Debe seleccionar un síntoma.");
				$("#sintoma_id").focus();
				return false;
			}
		}
		if ($("#estimado_hora").val() == "" || $("#estimado_minuto").val() == "") {
			alert("Debe ingresar un estimado de la duración del trabajo.");
			$("#estimado_hora").focus();
			return false;
		}
		return true;
	});
	
	$(".d_t_v").click(function(){
		if (confirm("Al volver los cambios ingresados no serán guardados.\n ¿Está seguro que desea volver?")) {
			window.location = "/Trabajo/revisar";
			return true;
		}
		return false;
	});
	
	$(".v_d_s").click(function(){
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "") {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "") {
				var i_i = $("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val();
				var i_t = $("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val();
				if (i_i == i_t) {
					alert("La intervención debe tener una duración de al menos 15 minutos.");
					return false;
				}
			}
		}
		if (confirm("¿Realmente desea guardar los cambios ingresados y continuar a la siguiente página?")) {
			return true;
		}
		return false;
	});
	
	
	$(".agregar_tec_extra").click(function(){
		if (parseInt($(".num_tecnicos").val()) == 6) {
			return false;	
		}
		var tt = parseInt($(".num_tecnicos").val()) + 1;
		$(".tecnico_"+tt).show();
		$("#tecnico_"+tt).val("");
		$(".num_tecnicos").val(tt);
		//$(".quitar_tec_extra").hide();
		//$(".quitar_tec_"+tt).show();
	});
	
	/*$(".tecnico_soporte").change(function(){
		var num = $(this).attr("num");
		if ($(this).val() == "" && num == $(".num_tecnicos").val()) {
			$(".tecnico_"+num).hide();
			num = parseInt(num) - 1;
			$(".num_tecnicos").val(num);
		}
	});*/
	
	$(".quitar_tec_extra").click(function(){
		var num = $(this).attr("num");
		if (num == $(".num_tecnicos").val()) {
			$(".tecnico_"+num).hide();
			$("#tecnico_"+num).val("");
			$("#tecnico_"+num).change();
			num = parseInt(num) - 1;
			$(".num_tecnicos").val(num);
			$(".quitar_tec_extra").hide();
			$(".quitar_tec_"+num).show();
		} else {
			var val = parseInt(num);
			var total = parseInt($(".num_tecnicos").val())
			for (i = val; i < total; i++) {
				var sig = i + 1;
				$("#tecnico_"+i).val($("#tecnico_"+sig).val());
			}
			$(".num_tecnicos").val(total - 1);
			$(".quitar_tec_"+total).val("");
			$(".tecnico_"+total).hide();
		}
	});
	
	if ($(".num_tecnicos").val() != undefined) {
		//$(".quitar_tec_extra").hide();
		//$(".quitar_tec_"+$(".num_tecnicos").val()).show();
	}
	/*
	$("#tecnico_principal").change(function(){
		var val = $(this).val();
		$(".tecnico_soporte option").removeAttr('disabled');
		if (val != "") {
			$(".tecnico_soporte option[value=" + val + "]").attr('disabled','disabled');
		}
	});
	$("#tecnico_principal").change();
	// Fix tecnicos
	$(".tecnico_soporte").change(function(){
		var val = $(this).val();
		var id = $(this).attr("id");
		$("#tecnico_principal option").removeAttr('disabled');
		$(".tecnico_soporte option").removeAttr('disabled');
		if (val != "") {
			$("#tecnico_principal option[value=" + val + "]").attr('disabled','disabled');
			$(".tecnico_soporte option[value=" + val + "]").attr('disabled','disabled');
		}
		$("#"+id+" option").removeAttr('disabled');
	});
	
	$(".tecnico_soporte").change();*/
	/*
	$(".tecnico_soporte").change(function(){
		$(".tecnico_soporte option").removeAttr('disabled');
		var id = $(this).attr("id");
		for(i = 1; i < 8; i++){
			var nid = "tecnico_"+i;
			if(id!=nid){
				var val = $("#"+nid).val();
				if(val!=""){
					$(".tecnico_soporte option[value=" + val + "]").attr('disabled','disabled');
				}
			}
		}
	});*/
	
	$(".tecnico_soporte").change(function() {
		var id = $(this).attr("id");
		var val1 = $("#tecnico_principal").val();
		var val2 = $("#tecnico_2").val();
		var val3 = $("#tecnico_3").val();
		var val4 = $("#tecnico_4").val();
		var val5 = $("#tecnico_5").val();
		var val6 = $("#tecnico_6").val();
		if(id!="tecnico_principal") {
			$("#tecnico_principal option").each(function(){
				if ((val2 == $(this).val() || val3 == $(this).val() || val4 == $(this).val() || val5 == $(this).val() || val6 == $(this).val()) && $(this).val() != "") {
					$(this).attr("disabled", "disabled");
				} else {
					$(this).removeAttr("disabled");
				}
			});
		}
		if(id!="tecnico_2") {
			$("#tecnico_2 option").each(function(){
				if ((val1 == $(this).val() || val3 == $(this).val() || val4 == $(this).val() || val5 == $(this).val() || val6 == $(this).val()) && $(this).val() != "") {
					$(this).attr("disabled", "disabled");
				} else {
					$(this).removeAttr("disabled");
				}
			});
		}
		if(id!="tecnico_3") {
			$("#tecnico_3 option").each(function(){
				if ((val1 == $(this).val() || val2 == $(this).val() || val4 == $(this).val() || val5 == $(this).val() || val6 == $(this).val()) && $(this).val() != "") {
					$(this).attr("disabled", "disabled");
				} else {
					$(this).removeAttr("disabled");
				}
			});
		}
		if(id!="tecnico_4") {
			$("#tecnico_4 option").each(function(){
				if ((val1 == $(this).val() || val3 == $(this).val() || $(this).val() == val2 || val5 == $(this).val() || $(this).val() == val6) && $(this).val() != "") {
					$(this).attr("disabled", "disabled");
				} else {
					$(this).removeAttr("disabled");
				}
			});
		}
		if(id!="tecnico_5") {
			$("#tecnico_5 option").each(function(){
				if ((val1 == $(this).val() || val3 == $(this).val() || $(this).val() == val2 || val4 == $(this).val() || $(this).val() == val6) && $(this).val() != "") {
					$(this).attr("disabled", "disabled");
				} else {
					$(this).removeAttr("disabled");
				}
			});
		}
		if(id!="tecnico_6") {
			$("#tecnico_6 option").each(function(){
				if ((val1 == $(this).val() || val3 == $(this).val() || $(this).val() == val2 || val4 == $(this).val() || $(this).val() == val5) && $(this).val() != "") {
					$(this).attr("disabled", "disabled");
				} else {
					$(this).removeAttr("disabled");
				}
			});
		}
	});
	$(".tecnico_soporte").change();
	
	//$(".delta_hora").change();
	
	$(".delta1_data").change(function(){
		if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
				var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
				var m = i_t - i_i;
				if (m >= 0) {
					$(".delta1_duracion").css("backgroundColor", "transparent");
				} else {
					$(".delta1_duracion").css("backgroundColor", "red");
				}
				var h = Math.floor(m / 60);
				var m = m - h * 60;
				$(".delta1_duracion").text(h+"h "+m+"m");
				return;
			}
		}
		$(".delta1_duracion").text("0h 0m");
	});
	
	$(".delta2_data").change(function(){
		if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
			if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
				var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
				var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
				var m = i_t - i_i;
				if (m >= 0) {
					$(".delta2_duracion").css("backgroundColor", "transparent");
				} else {
					$(".delta2_duracion").css("backgroundColor", "red");
				}
				var h = Math.floor(m / 60);
				var m = m - h * 60;
				$(".delta2_duracion").text(h+"h "+m+"m");
				return;
			}
		}
		$(".delta2_duracion").text("0h 0m");
	});
		
	$(".delta3_data").change(function(){
		if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
			if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
				var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
				var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
				var m = i_t - i_i;
				if (m >= 0) {
					$(".delta3_duracion").css("backgroundColor", "transparent");
				} else {
					$(".delta3_duracion").css("backgroundColor", "red");
				}
				var h = Math.floor(m / 60);
				var m = m - h * 60;
				$(".delta3_duracion").text(h+"h "+m+"m");
				return;
			}
		}
		$(".delta3_duracion").text("0h 0m");
	});
	
	$(".delta4_data").change(function(){
		if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
			if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
				var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
				var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
				var m = i_t - i_i;
				if (m >= 0) {
					$(".delta4_duracion").css("backgroundColor", "transparent");
				} else {
					$(".delta4_duracion").css("backgroundColor", "red");
				}
				var h = Math.floor(m / 60);
				var m = m - h * 60;
				$(".delta4_duracion").text(h+"h "+m+"m");
				return;
			}
		}
		$(".delta4_duracion").text("0h 0m");
	});
	
	$('.marcar_todos').click(function(){
		if (this.checked) {
			$('.matriz_opciones').attr('checked', true);
			$('.matriz_opciones').change();
			$(".checker span:first-child").addClass("checked");
		} else {
			$('.matriz_opciones').attr('checked', false);
			$('.matriz_opciones').change();
			$(".checker span:first-child").removeClass("checked");
		}
	});
	
	/*$('.marcar_todos_file').click(function(){
		var filtro=$("#aEstados").val();
		if(filtro==""||filtro=="3"){
			$('.marcar_todos_file').prop('checked',false);
			$.uniform.update('.marcar_todos_file');
			return false;
		}
		if (this.checked) {
			$('.matriz_opciones.estado'+filtro).prop('checked',true);
			$.uniform.update('.matriz_opciones.estado'+filtro);
			
			//$('.matriz_opciones.perfil'+filtro).attr('checked', true);
			//$('.matriz_opciones.perfil'+filtro).change();
			//$(".checker span:first-child").addClass("checked");
		} else {
			$('.matriz_opciones.estado'+filtro).prop('checked',false);
			$.uniform.update('.matriz_opciones.estado'+filtro);
			//$('.matriz_opciones.perfil'+filtro).attr('checked', false);
			//$('.matriz_opciones.perfil'+filtro).change();
			//$(".checker span:first-child").removeClass("checked");
		}
	});*/
	
	
	
	// Fix para seleccion de codigo elemento revision dcc
	$('body').on('change','.ele_codigo',function() {
		var codigo_id = '#'+$(this).attr("id");
		if ($(id).val()!='') {
			var subsistema_id = codigo_id.replace("ele_codigo_", "ele_subsistema_");
			var sistema_id = codigo_id.replace("ele_codigo_", "ele_sistema_");
			var elemento_posicion_id = codigo_id.replace("ele_codigo_", "ele_elemento_posicion_");
			var diagnostico_id = codigo_id.replace("ele_codigo_", "ele_diagnostico_");
			var elemento_id = codigo_id.replace("ele_codigo_", "ele_elemento_");
			var codigo = ($(this).val()+'').toUpperCase();
			if($(sistema_id).val()==null||$(sistema_id).val()=="null"||codigo==null||codigo==""||codigo=="null"){
				return false;
			}
			if (subsistema_id!=null&&subsistema_id!="null"){
				$.get("/Utilidades/sel_elemento_codigo/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+$(subsistema_id).val()+"/"+codigo, function(data) {
					var obj = $.parseJSON(data);
					var html = "";//"<option value=\"\"></opcion>\n";
					var cant=0;
					$.each(obj, function(i, item) {
						//if(nvalue!=""&&nvalue==item.Elemento.id){
							var codUpper = (item.Sistema_Subsistema_Motor_Elemento.codigo+'').toUpperCase();
							html += "<option value=\""+item.Elemento.id+"\" codigo=\""+item.Sistema_Subsistema_Motor_Elemento.codigo+"\" selected=\"selected\">"+item.Sistema_Subsistema_Motor_Elemento.codigo+ " - " +item.Elemento.nombre+"</opcion>\n";
						//}else{
							//html += "<option value=\""+item.Elemento.id+"\" codigo=\""+item.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+item.Sistema_Subsistema_Motor_Elemento.codigo+ " - " +item.Elemento.nombre+"</opcion>\n";
						//}
						cant++;
					});
					if(cant==0||cant>1){
						alert("Debe ingresar un ID válido!");
						return false;
					}else{
						$(elemento_id).removeAttr("disabled")
						$(elemento_id).html(html);
						$(".ele_elemento").change();
					}
				});
			}
			
			var value = $(elemento_posicion_id).attr("v");
			if ($(elemento_id).val()!=null&&$(elemento_id).val()!=""){
				$.get("/Utilidades/sel_posicion_elemento/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+$(subsistema_id).val()+"/"+codigo+"/"+$(elemento_id).val(), function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(value!=""&&value==item.Posicion.id){
							html += "<option value=\""+item.Posicion.id+"\" selected=\"selected\">"+item.Posicion.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</opcion>\n";
						}
					});
					$(elemento_posicion_id).removeAttr("disabled")
					$(elemento_posicion_id).html(html);
				});
			}
			var nvalue = $(diagnostico_id).attr("v");
			if ($(elemento_id).val()!=null&&$(elemento_id).val()!=""){
				$.get("/Utilidades/sel_diagnostico/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+$(subsistema_id).val()+"/"+$(elemento_id).val(), function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(nvalue!=""&&nvalue==item.Diagnostico.id){
							html += "<option value=\""+item.Diagnostico.id+"\" selected=\"selected\">"+item.Diagnostico.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Diagnostico.id+"\">"+item.Diagnostico.nombre+"</opcion>\n";
						}
					});
					$(diagnostico_id).removeAttr("disabled")
					$(diagnostico_id).html(html);
				});
			}
		}
	});
	
	
	$('body').on('change','.ele_solucion',function() {
		var id = '#'+$(this).attr("id");
		var value = $(this).attr("v");
		if ($(id+" option").length == 0) { 
			$.get("/Utilidades/sel_solucion/", function(data) {
				var obj = $.parseJSON(data);
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					if(item.Solucion.e=="0"&&value!=item.Solucion.id){					
					}else{
						if(value!=""&&value==item.Solucion.id){
							html += "<option value=\""+item.Solucion.id+"\" selected=\"selected\" e=\""+item.Solucion.e+"\">"+item.Solucion.nombre+"</opcion>\n";
						} else {
							html += "<option value=\""+item.Solucion.id+"\" e=\""+item.Solucion.e+"\">"+item.Solucion.nombre+"</opcion>\n";
						}
					}
				});
				$(id).append(html);
			});
		}
	});
	
	var sistemas_ok = [];
	$('body').on('change','.ele_sistema',function() {
		var id = '#'+$(this).attr("id");
		var value = $(this).attr("v");
		if (sistemas_ok[id.replace("sistema_","")]==null||sistemas_ok[id.replace("sistema_","")]!=id) {
			//console.log("Carga sistema " + id);
			sistemas_ok[id.replace("sistema_","")]=id;
			$.get("/Utilidades/sel_sistema/" + $("#motor_id").val(), function(data) {
				var obj = $.parseJSON(data);
				
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					if(value!=""&&value==item.Sistema.id){
						html += "<option value=\""+item.Sistema.id+"\" selected=\"selected\">"+item.Sistema.nombre+"</opcion>\n";
					} else {
						html += "<option value=\""+item.Sistema.id+"\">"+item.Sistema.nombre+"</opcion>\n";
					}
				});
				$(id).append(html);
				$(id).change();
			});
		}else if ($(id).val() != "") {
			// Cargamos subsistema
			var sistema_id = $(id).val();
			var subsistema_id = id.replace("ele_sistema_", "ele_subsistema_");
			var nvalue = $(subsistema_id).attr("v");
			//console.log("/Utilidades/sel_subsistema/"+$("#motor_id").val()+"/"+sistema_id);
			if(sistema_id!=undefined&&sistema_id!=null&&sistema_id!=""){
				$.get("/Utilidades/sel_subsistema/"+$("#motor_id").val()+"/"+sistema_id, function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(nvalue!=""&&nvalue==item.Subsistema.id){
							html += "<option value=\""+item.Subsistema.id+"\" selected=\"selected\">"+item.Subsistema.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Subsistema.id+"\">"+item.Subsistema.nombre+"</opcion>\n";
						}
					});
					$(subsistema_id).html(html);
					$(subsistema_id).change();
				});
			}
		}
	});
	
	$('body').on('change','.ele_subsistema',function() {
		var id = '#'+$(this).attr("id");
		if ($(id).val() != "") {
			// Cargamos subsistema posicion
			var subsistema_id = $(id).val();
			var codigo_id = id.replace("ele_subsistema_", "ele_codigo_");
			var sistema_id = id.replace("ele_subsistema_", "ele_sistema_");
			var elemento_id =  id.replace("ele_subsistema_", "ele_elemento_");
			var subsistema_posicion_id = id.replace("ele_subsistema_", "ele_subsistema_posicion_");
			var value = $(subsistema_posicion_id).attr("v");
			if (subsistema_id!=null&&subsistema_id!="null"){
				$.get("/Utilidades/sel_posicion/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+subsistema_id, function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(value!=""&&value==item.Posicion.id){
							html += "<option value=\""+item.Posicion.id+"\" selected=\"selected\">"+item.Posicion.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</opcion>\n";		
						}
					});
					$(subsistema_posicion_id).html(html);
				});
			}
			
			// carga de elementos
			var codigo = $(codigo_id).val();
			//console.log(codigo);
			//
			var nvalue = $(elemento_id).attr("v");
			if (subsistema_id!=null&&subsistema_id!="null"){
				$.get("/Utilidades/sel_elemento/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+subsistema_id, function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(nvalue!=""&&nvalue==item.Elemento.id&&codigo==item.Sistema_Subsistema_Motor_Elemento.codigo){
							html += "<option value=\""+item.Elemento.id+"\" codigo=\""+item.Sistema_Subsistema_Motor_Elemento.codigo+"\" selected=\"selected\">"+item.Sistema_Subsistema_Motor_Elemento.codigo+ " - " +item.Elemento.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Elemento.id+"\" codigo=\""+item.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+item.Sistema_Subsistema_Motor_Elemento.codigo+ " - " +item.Elemento.nombre+"</opcion>\n";
						}
					});
					$(elemento_id).removeAttr("disabled")
					$(elemento_id).html(html);
					$(elemento_id).change();
					//$(".ele_codigo").change();
					//$(".ele_elemento").change();
				});
			}
		}
	});
	
	$('body').on('change','.ele_elemento',function() {
		var elemento_id = '#'+$(this).attr("id");
		if ($(id).val() != "") {
			var subsistema_id = elemento_id.replace("ele_elemento_", "ele_subsistema_");
			var sistema_id = elemento_id.replace("ele_elemento_", "ele_sistema_");
			var elemento_posicion_id = elemento_id.replace("ele_elemento_", "ele_elemento_posicion_");
			var diagnostico_id = elemento_id.replace("ele_elemento_", "ele_diagnostico_");
			var codigo_id = elemento_id.replace("ele_elemento_", "ele_codigo_");
			var codigo = $('option:selected', this).attr('codigo');
			var value = $(elemento_posicion_id).attr("v");
			//$(codigo_id).val(codigo);
			if ($(elemento_id).val()!=null&&$(elemento_id).val()!=""){
				$.get("/Utilidades/sel_posicion_elemento/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+$(subsistema_id).val()+"/"+codigo+"/"+$(elemento_id).val(), function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(value!=""&&value==item.Posicion.id){
							html += "<option value=\""+item.Posicion.id+"\" selected=\"selected\">"+item.Posicion.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</opcion>\n";
						}
					});
					$(elemento_posicion_id).removeAttr("disabled")
					$(elemento_posicion_id).html(html);
				});
			}
			var nvalue = $(diagnostico_id).attr("v");
			if ($(elemento_id).val()!=null&&$(elemento_id).val()!=""){
				$.get("/Utilidades/sel_diagnostico/"+$("#motor_id").val()+"/"+$(sistema_id).val()+"/"+$(subsistema_id).val()+"/"+$(elemento_id).val(), function(data) {
					var obj = $.parseJSON(data);
					var html = "<option value=\"\"></opcion>\n";
					$.each(obj, function(i, item) {
						if(nvalue!=""&&nvalue==item.Diagnostico.id){
							html += "<option value=\""+item.Diagnostico.id+"\" selected=\"selected\">"+item.Diagnostico.nombre+"</opcion>\n";
						}else{
							html += "<option value=\""+item.Diagnostico.id+"\">"+item.Diagnostico.nombre+"</opcion>\n";
						}
					});
					$(diagnostico_id).removeAttr("disabled")
					$(diagnostico_id).html(html);
				});
			}
		}
	});
	
	// Fix revision delta supervisor
	$("input,select").change(function(){
		$(".show_delta_3").css("color","black");
		$(".show_delta_3").css("background-color","#F3F781");
		$(".show_delta_1").css("color","black");
		$(".show_delta_1").css("background-color","#F3F781");
		if ($("#fecha_operacion").val() != "" && $("#hora_operacion").val() != "" && $("#minutos_operacion").val() != "" && $("#periodo_operacion").val() != "") {
			var suma=0;
			var resp=0;
			var fo = (new Date($("#fecha_operacion").val() + " " + $("#hora_operacion").val() + ":" + $("#minutos_operacion").val() + " " + $("#periodo_operacion").val())).getTime();
			var ft = (new Date($("#fecha_termino").val() + " " + $("#hora_termino").val() + ":" + $("#minuto_termino").val() + " " + $("#periodo_termino").val())).getTime();
			if(fo==ft){
				$(".show_delta_3").css("color","white");
				$(".show_delta_3").css("background-color","green");
			} else if(fo>ft){
				$('input,select').each(function(index){  
						if($(this).attr("id")!=undefined&&$(this).attr("id").substr(0,4)=="ds3_"){
							var id=$(this).attr("id");
							if($(this).attr("id").substr(-2)=="_h"||$(this).attr("id").substr(-2)=="_m"){
								if($(this).val()!=undefined&&$(this).val()!=null&&$(this).val()!=""&&parseInt($(this).val())>0){
									if($(this).attr("id").substr(-2)=="_h"){
										suma+=parseInt($(this).val())*60;
									}
									if($(this).attr("id").substr(-2)=="_m"){
										suma+=parseInt($(this).val());
									}
									var new_id=$(this).attr("id").replace("_m","_r");
									new_id=new_id.replace("_h","_r");
									if($("#"+new_id).val()=="0"){
										resp++;
									}
								}
							}
						}
					}
				);
				var diff=fo-ft;
				suma=suma*60000;
				if(suma==diff&resp==0){
					$(".show_delta_3").css("color","white");
					$(".show_delta_3").css("background-color","green");
				}
			}
		}
		if ($("#f_t_a").val() != undefined && $("#f_t_a").val() != null && $("#f_t_a").val() != "") {
			var suma=0;
			var resp=0;
			var fo = (new Date($("#f_i_i").val() + " " + $("#h_i_i").val() + ":" + $("#m_i_i").val() + " " + $("#p_i_i").val())).getTime();
			var ft = (new Date($("#f_t_a").val())).getTime();
			if(fo==ft){
				$(".show_delta_1").css("color","white");
				$(".show_delta_1").css("background-color","green");
			} else if(fo>ft){
				$('input,select').each(function(index){  
						if($(this).attr("id")!=undefined&&$(this).attr("id").substr(0,4)=="ds1_"){
							var id=$(this).attr("id");
							if($(this).attr("id").substr(-2)=="_h"||$(this).attr("id").substr(-2)=="_m"){
								if($(this).val()!=undefined&&$(this).val()!=null&&$(this).val()!=""&&parseInt($(this).val())>0){
									if($(this).attr("id").substr(-2)=="_h"){
										suma+=parseInt($(this).val())*60;
									}
									if($(this).attr("id").substr(-2)=="_m"){
										suma+=parseInt($(this).val());
									}
									var new_id=$(this).attr("id").replace("_m","_r");
									new_id=new_id.replace("_h","_r");
									console.log(new_id);
									if($("#"+new_id).val()=="0"){
										resp++;
									}
								}
							}
						}
					}
				);
				var diff=fo-ft;
				suma=suma*60000;
				if(suma==diff&resp==0){
					$(".show_delta_1").css("color","white");
					$(".show_delta_1").css("background-color","green");
				}
			}
		}
	});
	
	$(".quitar_backlog" ).live("click",function(){
		var id = $(this).attr("id").replace("b","");
		var val = $("#bkdel").val();
		$("#bkdel").val(id+","+val);
		$(this).closest("tr").remove();
		return false;
	}); 
	/*
	$(".quitar_backlog").click(function(){
		//if(confirm("¿Realmente desea eliminar este backlog?")){
			var id = $(this).attr("id").replace("b","");
			var val = $("#bkdel").val();
			$("#bkdel").val(id+","+val);
			$("#bk"+id).remove();
			return false;
		//}
	});*/
	
	$(".addbkl").click(function(){
		var html = $(".blksistema").html().replace(" selected=\"selected\"", "");
		$("table.backlog tr:last").after('<tr><td align="center">Nuevo</td><td align="center"><select name="blnewcriticidad[]">'+
					'<option value="1">Alto</option>'+
					'<option value="2">Medio</option>'+
					'<option value="3">Bajo</option>'+
				'</select></td><td align="center"><select name="blnewsistema[]">'+html+'</select></td><td><input type="text" name="blnewcomentario[]" value="" style="width:90%;" /></td><td align="center"><img src="/images/icons/color/cross.png" class="quitar_backlog" id="0" title="Quitar backlog" style="cursor:pointer;" /></td></tr>');
	});
	
	
	// Filtros on the fly para administración
	/*$("#aEstados").change(function(){
		var val=$(this).val();
		if(val!=""){
			$(".tbResultados tr").hide();
			$(".tbResultados tr.e"+val).show();
		}else{
			$(".tbResultados tr").show();
		}
	});
	
	$("#aNiveles").change(function(){
		var val=$(this).val();
		if(val!=""){
			$(".tbResultados tr").hide();
			$(".tbResultados tr.n"+val).show();
		}else{
			$(".tbResultados tr").show();
		}
	});*/
	
	/*
	$("#aRut").keyup(function(){
		var u=$.trim($("#aRut").val());
		if(u==""){
			$(".tbResultados tbody tr").show();
			return;
		}
		$(".tbResultados tbody tr td.u").each(function() {
			var text = $.trim($(this).html());
			if(text!=""&&u!=""&&text.indexOf(u)>-1){
				$(this).closest("tr").show();
			}else{
				$(this).closest("tr").hide();
			}
		});
		$('.marcar_todos_file').prop('checked',false);
		$.uniform.update('.marcar_todos_file')
	});*/
	
	/*$("#aNombre").keyup(function(){
		var u=$.trim($("#aNombre").val().toUpperCase());
		if(u==""){
			$(".tbResultados tbody tr").show();
			return;
		}
		$(".tbResultados tbody tr td.n").each(function() {
			var text = $.trim($(this).html().toUpperCase());
			if(text!=""&&u!=""&&text.indexOf(u)>-1){
				$(this).closest("tr").show();
			}else{
				$(this).closest("tr").hide();
			}
		});
		$('.marcar_todos_file').prop('checked',false);
		$.uniform.update('.marcar_todos_file')
	});*/
	
	/*$("#aApellido").keyup(function(){
		var u=$.trim($("#aApellido").val().toUpperCase());
		if(u==""){
			$(".tbResultados tbody tr").show();
			return;
		}
		$(".tbResultados tbody tr td.a").each(function() {
			var text = $.trim($(this).html().toUpperCase());
			if(text!=""&&u!=""&&text.indexOf(u)>-1){
				$(this).closest("tr").show();
			}else{
				$(this).closest("tr").hide();
			}
		});
		$('.marcar_todos_file').prop('checked',false);
		$.uniform.update('.marcar_todos_file')
	});*/
	
	var hotFilter = function(){
		var u="";
		var n="";
		var a="";
		var flota="";
		var tipoequipo="";
		var motor="";
		var serie="";
		var esn="";
		var unidad="";
		var horometro="";
		var fabricante="";
		var modelomotor="";
		var modeloequipo="";
		var c="";
		var e="";
		var f="";
		var sistema="";
		var subsitema="";
		var posicionsubsistema="";
		var elemento="";
		var posicionelemento="";
		var idelemento="";
		var diagnostico="";
		var subsistema="";
		
		if($("#aRut")!=null&&$("#aRut").val()!=null&&$("#aRut").val().toUpperCase()!=null){
			u=$.trim($("#aRut").val().toUpperCase());
		}
		if($("#aNombre")!=null&&$("#aNombre").val()!=null&&$("#aNombre").val().toUpperCase()!=null){
			n=$.trim($("#aNombre").val().toUpperCase());
		}
		if($("#aApellido")!=null&&$("#aApellido").val()!=null&&$("#aApellido").val().toUpperCase()!=null){
			a=$.trim($("#aApellido").val().toUpperCase());
		}
		if($("#aFlota")!=null&&$("#aFlota").val()!=null&&$("#aFlota").val().toUpperCase()!=null){
			flota=$.trim($("#aFlota").val().toUpperCase());
		}
		if($("#aEMTipo")!=null&&$("#aEMTipo").val()!=null&&$("#aEMTipo").val().toUpperCase()!=null){
			tipoequipo=$.trim($("#aEMTipo").val().toUpperCase());
		}
		if($("#aMotor")!=null&&$("#aMotor").val()!=null&&$("#aMotor").val().toUpperCase()!=null){
			motor=$.trim($("#aMotor").val().toUpperCase());
		}
		if($("#aSerie")!=null&&$("#aSerie").val()!=null&&$("#aSerie").val().toUpperCase()!=null){
			serie=$.trim($("#aSerie").val().toUpperCase());
		}
		if($("#aEsn")!=null&&$("#aEsn").val()!=null&&$("#aEsn").val().toUpperCase()!=null){
			esn=$.trim($("#aEsn").val().toUpperCase());
		}
		if($("#aUnidad")!=null&&$("#aUnidad").val()!=null&&$("#aUnidad").val().toUpperCase()!=null){
			unidad=$.trim($("#aUnidad").val().toUpperCase());
		}
		if($("#aHorometro")!=null&&$("#aHorometro").val()!=null&&$("#aHorometro").val().toUpperCase()!=null){
			horometro=$.trim($("#aHorometro").val().toUpperCase());
		}
		if($("#aFabricante")!=null&&$("#aFabricante").val()!=null&&$("#aFabricante").val().toUpperCase()!=null){
			fabricante=$.trim($("#aFabricante").val().toUpperCase());
		}
		if($("#aModeloMotor")!=null&&$("#aModeloMotor").val()!=null&&$("#aModeloMotor").val().toUpperCase()!=null){
			modelomotor=$.trim($("#aModeloMotor").val().toUpperCase());
		}
		if($("#aModeloEquipo")!=null&&$("#aModeloEquipo").val()!=null&&$("#aModeloEquipo").val().toUpperCase()!=null){
			modeloequipo=$.trim($("#aModeloEquipo").val().toUpperCase());
		}
		if($("#aSistema")!=null&&$("#aSistema").val()!=null&&$("#aSistema").val().toUpperCase()!=null){
			sistema=$.trim($("#aSistema").val().toUpperCase());
		}
		if($("#aSubsistema")!=null&&$("#aSubsistema").val()!=null&&$("#aSubsistema").val().toUpperCase()!=null){
			subsistema=$.trim($("#aSubsistema").val().toUpperCase());
		}
		if($("#aPosicionSistema")!=null&&$("#aPosicionSistema").val()!=null&&$("#aPosicionSistema").val().toUpperCase()!=null){
			posicionsubsistema=$.trim($("#aPosicionSistema").val().toUpperCase());
		}
		if($("#aElemento")!=null&&$("#aElemento").val()!=null&&$("#aElemento").val().toUpperCase()!=null){
			elemento=$.trim($("#aElemento").val().toUpperCase());
		}
		if($("#aPosicionElemento")!=null&&$("#aPosicionElemento").val()!=null&&$("#aPosicionElemento").val().toUpperCase()!=null){
			posicionelemento=$.trim($("#aPosicionElemento").val().toUpperCase());
		}
		if($("#aIdElemento")!=null&&$("#aIdElemento").val()!=null&&$("#aIdElemento").val().toUpperCase()!=null){
			idelemento=$.trim($("#aIdElemento").val().toUpperCase());
		}
		if($("#aDiagnostico")!=null&&$("#aDiagnostico").val()!=null&&$("#aDiagnostico").val().toUpperCase()!=null){
			diagnostico=$.trim($("#aDiagnostico").val().toUpperCase());
		}
		
		
		if($("#aNiveles")!=null&&$("#aNiveles").val()!=null){
			c=$("#aNiveles").val();
		}
		
		if($("#aEstados")!=null&&$("#aEstados").val()!=null){
			e=$("#aEstados").val();
		}
		
		if($("#aFaenas")!=null&&$("#aFaenas").val()!=null){
			f=$("#aFaenas").val();
		}
		
		$(".tbResultados tbody tr").show();
		if(u==""&&c==""&&e==""&&f==""&&n==""&&a==""&&flota==""&&tipoequipo==""&&motor==""&&serie==""&&esn==""&&unidad==""&&horometro==""&&fabricante==""&&modelomotor==""&&modeloequipo==""&&sistema==""&&subsistema==""&&posicionsubsistema==""&&elemento==""&&posicionelemento==""&&idelemento==""&&diagnostico==""){
			//$(".tbResultados tbody tr").show();
			return;
		}
		$(".tbResultados tbody tr td").each(function() {
			var txt=$.trim($(this).html().toUpperCase());
			var clss=$(this).attr('class');
			if(clss=="u"&&u!=""&&txt.indexOf(u)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="n"&&n!=""&&txt.indexOf(n)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="a"&&a!=""&&txt.indexOf(a)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="idelemento"&&idelemento!=""&&txt.indexOf(idelemento)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="flota"&&flota!=""&&txt.indexOf(flota)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="serie"&&serie!=""&&txt.indexOf(serie)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="esn"&&esn!=""&&txt.indexOf(esn)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="unidad"&&unidad!=""&&txt.indexOf(unidad)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="horometro"&&horometro!=""&&txt.indexOf(horometro)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="fabricante"&&fabricante!=""&&txt.indexOf(fabricante)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="modelomotor"&&modelomotor!=""&&txt.indexOf(modelomotor)==-1){
				$(this).closest("tr").hide();
			}
			if(clss=="modeloequipo"&&modeloequipo!=""&&txt.indexOf(modeloequipo)==-1){
				$(this).closest("tr").hide();
			}
			// Select
			if(c!=""){		
				c=$.trim($("#aNiveles").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="c"&&c!=""&&txt.indexOf(c)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(f!=""){
				f=$.trim($("#aFaenas").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="f"&&f!=""&&txt.indexOf(f)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(e!=""){
				e=$.trim($("#aEstados").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="e"&&e!=""&&txt.indexOf(e)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(clss=="tipoequipo"&&tipoequipo!=""&&txt.indexOf(tipoequipo)==-1){
				$(this).closest("tr").hide();
			}
			if(flota!=""){
				flota=$.trim($("#aFlota").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="flota"&&flota!=""&&txt.indexOf(flota)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(motor!=""){
				motor=$.trim($("#aMotor").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="motor"&&motor!=""&&txt.indexOf(motor)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(sistema!=""){
				sistema=$.trim($("#aSistema").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="sistema"&&sistema!=""&&txt.indexOf(sistema)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(subsistema!=""){
				subsistema=$.trim($("#aSubsistema").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="subsistema"&&subsistema!=""&&txt.indexOf(subsistema)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(posicionsubsistema!=""){
				posicionsubsistema=$.trim($("#aPosicionSistema").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="posicionsubsistema"&&posicionsubsistema!=""&&txt.indexOf(posicionsubsistema)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(posicionelemento!=""){
				posicionelemento=$.trim($("#aPosicionElemento").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="posicionelemento"&&posicionelemento!=""&&txt.indexOf(posicionelemento)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(elemento!=""){
				elemento=$.trim($("#aElemento").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="elemento"&&elemento!=""&&txt.indexOf(elemento)!=0){
					$(this).closest("tr").hide();
				}
			}
			if(diagnostico!=""){
				diagnostico=$.trim($("#aDiagnostico").children("option").filter(":selected").text()).toUpperCase();
				if(clss=="diagnostico"&&diagnostico!=""&&txt.indexOf(diagnostico)!=0){
					$(this).closest("tr").hide();
				}
			}
		});
		$('.marcar_todos_file').prop('checked',false);
		$.uniform.update('.marcar_todos_file');
	};
	
	$(".flTexto").keyup(function(){
		hotFilter();
	});
	
	$(".flCargas").change(function(){
		hotFilter();
	});
	
	$('.marcar_todos_file').click(function(){
		$(".matriz_opciones").each(function() {
			if($(this).closest("tr").css("display")!="none") {
				//console.log($(this).closest("tr").css("display"));
				if ($('.marcar_todos_file').prop('checked')) {
					$(this).prop('checked',true);
				} else {
					$(this).prop('checked',false);
				}
			}
		});
		$.uniform.update('.matriz_opciones')
	});
	
	/*
	
	$(".flTexto").keyup(function(){
		var u=$("#aRut").val();
		var n=$("#aNiveles").val();
		var e=$("#aEstados").val();
		var f=$("#aFaenas").val();
		var x=$("#aNombre").val();
		var y=$("#aApellido").val();
		if(x!=""){
			x=x.toUpperCase();
		}
		if(y!=""){
			y=y.toUpperCase();
		}
		$(".tbResultados tr").hide();
		$(".tbResultados tr.th").show();
		if(u==""&&n==""&&e==""&&f==""&&x==""&&y==""){
			$(".tbResultados tr").show();
			return false;
		}
		if(u!=""&&n==""&&e==""&&f==""&&x==""&&y==""){
			$("tr[class*='u"+u+"']").show();
			return false;
		}
		if(x!=""&&n==""&&e==""&&f==""&&u==""&&y==""){
			$("tr[class*='x"+x+"']").show();
			return false;
		}
		if(y!=""&&n==""&&e==""&&f==""&&x==""&&u==""){
			$("tr[class*='y"+y+"']").show();
			return false;
		}
		if(u!=""){
			$("tr[class*='u"+u+"']").each(function() {
				if(e!=""&&n!=""&&f!=""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")&&$(this).hasClass("f"+f+"f")){
					console.log("p f e");
					$(this).show();
				}else if(e!=""&&n!=""&&f==""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")){
					console.log("p e");
					$(this).show();
				}else if(e!=""&&f!=""&&n==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("e"+e+"e")){
					console.log("f e");
					$(this).show();
				}else if(f!=""&&n!=""&&e==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("n"+n+"n")){
					console.log("faena y perfil");
					$(this).show();
				}else if(e!=""&&f==""&&n==""&&$(this).hasClass("e"+e+"e")){
					$(this).show();
				}else if(n!=""&&e==""&&f==""&&$(this).hasClass("n"+n+"n")){
					console.log("perfil");
					$(this).show();
				}else if(f!=""&&n==""&&e==""&&$(this).hasClass("f"+f+"f")){
					console.log("faena");
					$(this).show();
				}else{
					console.log("else");
					//$(this).show();
				}
			});
		}else{
			$(".tbResultados tr").each(function() {
				if(e!=""&&n!=""&&f!=""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")&&$(this).hasClass("f"+f+"f")){
					console.log("p f e");
					$(this).show();
				}else if(e!=""&&n!=""&&f==""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")){
					console.log("p e");
					$(this).show();
				}else if(e!=""&&f!=""&&n==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("e"+e+"e")){
					console.log("f e");
					$(this).show();
				}else if(f!=""&&n!=""&&e==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("n"+n+"n")){
					console.log("faena y perfil");
					$(this).show();
				}else if(e!=""&&f==""&&n==""&&$(this).hasClass("e"+e+"e")){
					$(this).show();
				}else if(n!=""&&e==""&&f==""&&$(this).hasClass("n"+n+"n")){
					console.log("perfil");
					$(this).show();
				}else if(f!=""&&n==""&&e==""&&$(this).hasClass("f"+f+"f")){
					console.log("faena");
					$(this).show();
				}else{
					console.log("else");
					$(this).hide();
				}
			});
		}
		$(".tbResultados tr.th").show();
	});*/
	
	$("#aEstados").change(function(){
		/*if($(this).val()!=""&&$(this).val()!="3"){
			$('.marcar_todos_file').prop('disabled',false);
		}else{
			$('.marcar_todos_file').prop('disabled',true);
		}*/
		$('.marcar_todos_file').prop('checked',false);
		$.uniform.update('.marcar_todos_file');
	});
	
	/*$(".flCargas").change(function(){
		//console.log("filtro");
		var n=$("#aNiveles").val();
		var e=$("#aEstados").val();
		var f=$("#aFaenas").val();
		var u=$("#aRut").val();
		var x=$("#aNombre").val();
		var y=$("#aApellido").val();
		if(x!=""){
			x=x.toUpperCase();
		}
		if(y!=""){
			y=y.toUpperCase();
		}
		$(".tbResultados tr").hide();
		$(".tbResultados tr.th").show();
		if(u==""&&n==""&&e==""&&f==""&&x==""&&y==""){
			$(".tbResultados tr").show();
			return false;
		}
		if(u!=""&&n==""&&e==""&&f==""&&x==""&&y==""){
			$("tr[class*='u"+u+"']").show();
			return false;
		}
		if(x!=""&&n==""&&e==""&&f==""&&u==""&&y==""){
			$("tr[class*='x"+x+"']").show();
			return false;
		}
		if(y!=""&&n==""&&e==""&&f==""&&x==""&&u==""){
			$("tr[class*='y"+y+"']").show();
			return false;
		}
		if(u==""){
			$(".tbResultados tr").each(function() {
				if(e!=""&&n!=""&&f!=""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")&&$(this).hasClass("f"+f+"f")){
					console.log("p f e");
					$(this).show();
				}else if(e!=""&&n!=""&&f==""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")){
					console.log("p e");
					$(this).show();
				}else if(e!=""&&f!=""&&n==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("e"+e+"e")){
					console.log("f e");
					$(this).show();
				}else if(f!=""&&n!=""&&e==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("n"+n+"n")){
					console.log("faena y perfil");
					$(this).show();
				}else if(e!=""&&f==""&&n==""&&$(this).hasClass("e"+e+"e")){
					$(this).show();
				}else if(n!=""&&e==""&&f==""&&$(this).hasClass("n"+n+"n")){
					console.log("perfil");
					$(this).show();
				}else if(f!=""&&n==""&&e==""&&$(this).hasClass("f"+f+"f")){
					console.log("faena");
					$(this).show();
				}else{
					console.log("else");
					$(this).hide();
				}
			});
		}else{
			$("tr[class*='u"+u+"']").each(function() {
				if(e!=""&&n!=""&&f!=""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")&&$(this).hasClass("f"+f+"f")){
					console.log("p f e");
					$(this).show();
				}else if(e!=""&&n!=""&&f==""&&$(this).hasClass("e"+e+"e")&&$(this).hasClass("n"+n+"n")){
					console.log("p e");
					$(this).show();
				}else if(e!=""&&f!=""&&n==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("e"+e+"e")){
					console.log("f e");
					$(this).show();
				}else if(f!=""&&n!=""&&e==""&&$(this).hasClass("f"+f+"f")&&$(this).hasClass("n"+n+"n")){
					console.log("faena y perfil");
					$(this).show();
				}else if(e!=""&&f==""&&n==""&&$(this).hasClass("e"+e+"e")){
					$(this).show();
				}else if(n!=""&&e==""&&f==""&&$(this).hasClass("n"+n+"n")){
					console.log("perfil");
					$(this).show();
				}else if(f!=""&&n==""&&e==""&&$(this).hasClass("f"+f+"f")){
					console.log("faena");
					$(this).show();
				}else{
					console.log("else");
					//$(this).show();
				}
			});
		}
		$(".tbResultados tr.th").show();
	});*/
	
	$(".mayus").keyup(function(){
		$(this).val($(this).val().toUpperCase());	
	});
	
	//$("[data-toggle=tooltip]").tooltip();

	
});
