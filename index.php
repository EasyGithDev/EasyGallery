<?php
require 'conf.php';

$create_thumb = isset($_GET['create_thumb']) ? $_GET['create_thumb'] : 0;

if ($create_thumb) {

    set_time_limit(0);
    $text = \Easy\TrueType::create('Copyright (C) 2013 Florent Brusciano', \Easy\Text::TEXT_MACOS_FONT_PATH . '/Arial Black.ttf')
	    ->setSize(5)
	    ->setColor(Easy\Color::White())
	    ->setPosition(Easy\Position::create(40, 190));

    foreach (new \Easy\ImageFilterIterator(new DirectoryIterator(THUMB_ORIGINAL)) as $file) {

	if (($image = Easy\Image::createFrom($file->getPathname())) === FALSE)
	    continue;

	\Easy\Transformation::thumbnail($image, 100)
		->save(THUMB_100 . $file->getFilename(), 100, FALSE);

	\Easy\Transformation::thumbnail($image, 200)
		->addText($text)
		->save(THUMB_200 . $file->getFilename(), 100, FALSE);

	\Easy\Transformation::thumbnail($image, 400)
		->save(THUMB_400 . $file->getFilename(), 100, FALSE);
    }
}

$iterator = new \Easy\ImageFilterIterator(new DirectoryIterator(THUMB_200));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Bootstrap, from Twitter</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="assets/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="stylesheet">
        <link href="assets/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="assets/bootstrap/js/html5shiv.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/bootstrap/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/bootstrap/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/bootstrap/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/bootstrap/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="assets/bootstrap/ico/favicon.png">


	<style>
            body {
                padding-top: 20px;
            }

	    /* Custom container */
	    .container {
		margin: 0 auto;
		max-width: 1000px;
	    }
	    .container > hr {
		margin: 60px 0;
	    }

            #myModal {
                max-height: 800px;
                width: 900px; /* SET THE WIDTH OF THE MODAL */
                margin: 0px 0 0 -450px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
            }

	    #myModal .modal-header {
		padding-top: 10px;
		max-height: 35px;
	    }

	    #myModal .modal-footer {
		padding: 0px 15px 0px;
	    }

	    #myModal .modal-body {
		min-height: 420px;
		padding-top: 5px;
	    }

	    #myModal .modal-body .span5 {
		width: 405px;
		margin-left: 5px;
	    }

	    #myModal .modal-body .span6 {
		width: 445px;
		margin-left: 5px;
	    }

	    #myModal .modal-tabs {

	    }

	    #myModal #modal-form-customs .input-mini {
		width: 25px;
	    }

	    #myModal #modal-form-customs table td {
		text-align: center;
		padding-bottom: 0;
	    }

	    #myModal #modal-tab-customs label {
		display: inline;
		padding-right: 10px;
	    }

	    #myModal .pager {
		margin-top: 5px;
		margin-bottom: 5px;
	    }

            #modal-img {
                /*max-width: 400px;*/
            }

	    span {
		/*border: 1px solid #000000;*/
	    }

        </style>

	<script src="assets/js/jquery.1.9.1.js"></script>
	<script src="assets/js/jquery-ui-1.10.1.custom.min.js"></script>
        <script src="assets/js/jQueryRotateCompressed.2.2.js"></script>
	<script src="assets/js/screenfull.min.js"></script>

        <script>

	    var thumb_original = "<?php echo THUMB_ORIGINAL; ?>";
	    var thumb_400 = "<?php echo THUMB_400; ?>";
	    var thumb_200 = "<?php echo THUMB_200; ?>";
	    var thumb_100 = "<?php echo THUMB_100; ?>";

	    var date = new Date();
            var angle = 0;

            $(document).ready( function() {
    
    
		$('.container .thumbnail img').click(function () {
		    var filename = $(this).attr('alt').split('/').pop();
		    loadModal(filename);
		    $('#myModal').modal('show');
		}); 

                $('#myModal').on('hidden', function() {
                    $(this).removeData('modal');
                    angle = 0;
                    $('#modal-img').rotate(angle);
                });
    
		$('#modal-btn-rotate-left').click(  function() {
		    angle -= 90;
		    $('#modal-img').rotate(angle);
		});
		
		$('#modal-btn-rotate-right').click(  function() {
		    angle += 90;
		    $('#modal-img').rotate(angle);
		});
		
		$('#modal-btn-fullscreen').click(function() {
		    if ( screenfull ) {		
			var img = thumb_original + getFilename();
			$("#modal-img").attr("src", img);
			screenfull.toggle( $("#modal-img")[0] );
		    }
		});
		
		if (screenfull.enabled) {
		    screenfull.onchange = function() {
			if(!screenfull.isFullscreen) {
			    var img = thumb_400 + getFilename();
			    $("#modal-img").attr("src", img);
			}
			console.log('Am I fullscreen? ' + screenfull.isFullscreen ? 'Yes' : 'No');
		    };
		}
	
		
		$('#custom-selection').change(function () {
		    var filesrc = thumb_400 + getFilename();
		    var src = 'loader.php?action=filter&filesrc='+filesrc+'&filtertype=convolution&filtername='+$(this).val();
		
		    $("#modal-img").attr("src", src);
		
		    src = 'loader.php?action=convolution_info&filesrc='+filesrc+'&convolution='+$(this).val();
		    $.getJSON(src + '&t=' + date.getTime(), function(data) {
		  
			for(i=0;i<3;i++){
			    for(j=0;j<3;j++){
				var m = '#m_' + i + '_' + j;
				$(m).val(data.matrix[i][j])
			    }
			}
			$('#divisor').val(data.divisor);
			$('#offset').val(data.offset)
		    });
		
		});
	    
		
		$('#btn-img-prev').click( function(){
		    
		    var img = getFilename();
		    var prev = '';
		    for(i=0; i<images.length; i++) {
			if(images[i] == img) {
			    if(prev == '') 
				return;
			    loadModal(prev);
			    return;	
			}
			prev = images[i];
		    }
		} );
		
		$('#btn-img-next').click( function(){
		    
		    var img = getFilename();
		    var prev = '';
		    for(i=images.length; i>=0; i--) {
			if(images[i] == img) {
			    if(prev == '') 
				return;
			    loadModal(prev);
			    return;	
			}
			prev = images[i];
		    }

		} );
		
		
		$('#modal-nav li').click(function(e) {
		    e.preventDefault();
		    $('#modal-nav li').removeClass('active');		    
		    $(this).addClass('active');
		    
		    $('.modal-tab').addClass('hide');
		    var tabContent = '#modal-tab-' + $(this).attr('id');
		    $(tabContent).removeClass('hide');
		   
		});
		
	    });
        
	
	    function getFilename() {
		return  $("#modal-img").attr("alt");
	    }
	
	    function loadModal(filename){
		loadFilters(filename);
		loadTools();
		loadInfos(filename);
		$("#modal-img").attr("src", thumb_400 + filename);
		$("#modal-img").attr("alt", filename);
		$('#myModal .modal-header H4').html(filename);
	    }
    
	    function loadFilters(filename) {
		    
		var  src = 'loader.php?action=filter&filesrc=' + thumb_100 + filename;
		
		$("#myModal .thumbnail").find('*').each( function (key, val) {
		
		    var thumbnail = $(val).attr('id').split('-');		    
		    var filtertype = thumbnail[0];
		    var filtername = thumbnail[1];
		    var filterstring = '&filtertype='+filtertype+'&filtername='+filtername+ '&t=' + date.getTime(); 
		    //console.log(thumbnail)
		    $(val).attr("src", src + filterstring);
		    $(val).click(function(){	
			var filesrc = thumb_400 + getFilename();	    
			var  src = 'loader.php?action=filter&filesrc=' + filesrc + filterstring;
			$("#modal-img").attr("src", src + '&t=' + date.getTime() );
			
		    });
	    
		});
	
	    }
	    
	    function loadTools() {
	    
		$( "#modal-slider-brightness" ).slider({ values: [ 0 ], min: -255, max: 255 });
		$( "#modal-slider-contrast" ).slider({ values: [ 0 ], min: -100, max: 100 });
		$( "#modal-slider-smooth" ).slider({ values: [ 0 ],min: -8, max: 8 });
		$( "#modal-slider-pixelate" ).slider({ values: [ 0 ], min: 0, max: 10 });    
		$( "#modal-slider-red" ).slider({ values: [ 0 ], min: -255, max: 255 });
		$( "#modal-slider-green" ).slider({ values: [ 0 ], min: -255, max: 255 });
		$( "#modal-slider-blue" ).slider({ values: [ 0 ], min: -255, max: 255 });
		
		$("#myModal #modal-tab-tools").find('div').each( function (key, val) {
		    var filtername = $(val).attr('id').split('-').pop();
		    $(val).on( "slidestop", function( event, ui ) {
			if(filtername == 'red' || filtername == 'green' || filtername == 'blue'){
			    $(val).slider('value', ui.value);
			    changeColor();
			}
			else {
			    var filesrc = thumb_400 + getFilename();
			    var  src = 'loader.php?action=filter&filesrc=' + filesrc + '&filtertype=preset&filtername='+filtername+'&value='+ui.value;
			    $("#modal-img").attr("src", src );
			}
		    } );
		
		});
		
	    }
	    
	    function loadInfos(filename) {
	    
		var src ='loader.php?action=info&filesrc=' + thumb_original + filename;
		$.getJSON(src + '&t=' + date.getTime(), function(data) {
		    var items = [];
		    $.each(data, function(key, val) {
			if(key == 'size')
			    val = Math.round( val / 1024 ) + ' Ko';
			items.push('<li id="' + key + '">' + key + ': ' + val + '</li>');
		    });
		    
		    $('#modal-tab-infos > #text-infos').html('<ul>'+items.join('')+'</ul>');
		});
		
		//loadHistogramme(filename);
	    } 
	   
	    function loadHistogramme(filename) {
	    
		var src ='loader.php?action=histogramme&filesrc=' + thumb_original + filename;
		$.getJSON(src + '&t=' + date.getTime(), function(data) {
		    
		});
	    
	    }

	    function changeColor() {
	   
		var filesrc = thumb_400 + getFilename();	    
		var  src = 'loader.php?action=filter&filesrc=' + filesrc + '&filtertype=preset&filtername=colorize';
		var red = $( "#modal-slider-red" ).slider( "value" );
		var blue = $( "#modal-slider-blue" ).slider( "value" );
		var green = $( "#modal-slider-green" ).slider( "value" );
		
		src += '&red=' + red + '&green=' + green + '&blue=' + blue; 
	
		$("#modal-img").attr("src", src );
	    }
	
	    
	    function applyConvolution() {
		var divisor = $('#divisor').val();
		var offset = $('#offset').val();
		var matrix = [];
		    
		for(i =0; i<3;i++){
		    for(j=0;j<3;j++){
			matrix.push($('#m_'+i+'_'+j).val());
		    }
		}
		matrix = JSON.stringify(matrix);
		//console.log(matrix);
		var filesrc = thumb_400 + getFilename();
		var  src = 'loader.php?action=convolution_custom&filesrc=' + filesrc + '&divisor='+divisor+'&offset='+offset+'&matrix='+matrix;
		//console.log(src)
		$("#modal-img").attr("src", src );
		return false;
	    }

        </script>
    </head>

    <body>

        <div class="container">

	    <div class="masthead">
		<h3 class="muted">Gallery using EasyGD</h3>
		<?php if (iterator_count($iterator) == 0) : ?>
    		<p><a href="?create_thumb=1" class="btn btn-primary btn-large">Generate Thumbs</a></p>
		<?php endif; ?>        
	    </div>

	    <ul class="thumbnails">

		<?php $images = array(); ?>

		<?php foreach ($iterator as $file) : ?>

		    <?php $images[] = $file->getFilename(); ?>

    		<li>
    		    <div class="thumbnail">
    			<img src="<?php echo $file->getPathname(); ?>" alt="<?php echo $file->getPathname(); ?>">
    		    </div>
    		    <!--
    		    <div>Nom : <?php echo $file->getFilename(); ?></div>
    		    <div>Poids : <?php echo round($file->getSize() / 1024); ?> Ko</div>   
    		    -->
    		</li>

		<?php endforeach; ?>

	    </ul>

	</div> <!-- /container -->

	<script>
	    var images = <?php echo json_encode($images); ?>
	</script>

	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4></h4>
	    </div>
	    <div class="modal-body">

		<span class="span5">
		    <div>
			<img id="modal-img" alt="">
		    </div>
		    <div style="margin-top:5px">
			<a id="modal-btn-rotate-left" class="btn btn-small" href="#"><i class="icon-arrow-left"></i></a>
			<a id="modal-btn-rotate-right" class="btn btn-small" href="#"><i class="icon-arrow-right"></i></a>
			<a id="modal-btn-fullscreen" class="btn btn-small" href="#"><i class="icon-fullscreen"></i></a>
		    </div>
		</span>
		<span class="span6">

		    <ul id="modal-nav" class="nav nav-tabs">
			<li class="active" id="filters"><a href="#">Filtres</a></li>
			<li id="tools"><a href="#">Outils</a></li>
			<li id="customs"><a href="#">Perso</a></li>
			<li id="infos"><a href="#">Infos</a></li>
		    </ul>

		    <div class="modal-tabs">
			<div class="modal-tab" id="modal-tab-filters">
			    <ul class="thumbnails">
				<li>
				    <div class="thumbnail">
					<img id="lookuptable-lightnessgray" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="lookuptable-averagegray" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="lookuptable-luminositygray" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="lookuptable-thresholding" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="preset-edgedetect" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="preset-emboss" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="preset-gaussian_blur" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="preset-mean_removal" />
				    </div>
				</li>
				<li>
				    <div class="thumbnail">
					<img id="preset-negate" />
				    </div>
				</li>
			    </ul>
			</div>


			<div id="modal-tab-tools" class="modal-tab hide">

			    <h6>Luminosité</h6>
			    <div id="modal-slider-brightness" ></div>

			    <h6>Contraste</h6>
			    <div id="modal-slider-contrast" ></div>

			    <h6>Lissage</h6>
			    <div id="modal-slider-smooth" ></div>

			    <h6>Pixelisation</h6>
			    <div id="modal-slider-pixelate" ></div>

			    <h6>Rouge / Vert / Bleu</h6>
			    <div id="modal-slider-red" ></div><br/>
			    <div id="modal-slider-green" ></div><br/>
			    <div id="modal-slider-blue" ></div>

			</div>

			<div id="modal-tab-customs" class="modal-tab hide">

			    <?php
			    $convolutions = Easy\Convolution::getConvolutionList();
			    ?>
			    <form id="modal-form-customs" class="well" onsubmit="return applyConvolution();">
				<div>
				    <label for="custom-selection">Sélections :</label>

				    <select id="custom-selection" class="input-xlarge">

					<?php foreach ($convolutions as $v) : ?>

    					<option value="<?php echo str_replace('CONVOLUTION_', '', $v); ?>"><?php echo $v; ?></option>

					<?php endforeach; ?>

				    </select>
				</div>

				<table class="table table-bordered">
				    <tbody>
					<tr>
					    <td><input id="m_0_0" type="text" value="" class="input-mini"></td>
					    <td><input id="m_0_1" type="text" value="" class="input-mini"></td>
					    <td><input id="m_0_2" type="text" value="" class="input-mini"></td>
					</tr>
					<tr>
					    <td><input id="m_1_0" type="text" value="" class="input-mini"></td>
					    <td><input id="m_1_1" type="text" value="" class="input-mini"></td>
					    <td><input id="m_1_2" type="text" value="" class="input-mini"></td>
					</tr>
					<tr>
					    <td><input id="m_2_0" type="text" value="" class="input-mini"></td>
					    <td><input id="m_2_1" type="text" value="" class="input-mini"></td>
					    <td><input id="m_2_2" type="text" value="" class="input-mini"></td>
					</tr>
				    </tbody>
				</table>

				<div>
				    <label class="control-label" for="divisor">Diviseur :</label>
				    <input id="divisor" type="text" value="" class="input-mini">
				</div>
				<div>
				    <label class="control-label" for="offset">Décallage :</label>
				    <input id="offset" type="text" value="" class="input-mini">
				</div>
				<div>
				    <button id="submit" type="submit" class="btn">Appliquer</button>
				</div>

			    </form>

			</div>

			<div id="modal-tab-infos" class="modal-tab hide">

			    <span id="text-infos"></span>
			    <!--
			    <span id="histo-infos">
				<img src="red.png" width="50" height="50" />
				<img src="green.png" width="50" height="50" />
				<img src="blue.png" width="50" height="50" />
			    </span>
			    -->
			</div>
		    </div>



		</span>

	    </div>
	    <div class="modal-footer">
		<ul class="pager">
		    <li class="previous"><a id="btn-img-prev" href="#">&larr; Prev</a></li>
		    <li class="next"><a id="btn-img-next" href="#">Next &rarr;</a></li>
		</ul>
	    </div>
	</div>


	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->

	<script src="assets/bootstrap/js/bootstrap-transition.js"></script>
	<script src="assets/bootstrap/js/bootstrap-alert.js"></script>
	<script src="assets/bootstrap/js/bootstrap-modal.js"></script>
	<script src="assets/bootstrap/js/bootstrap-dropdown.js"></script>
	<script src="assets/bootstrap/js/bootstrap-scrollspy.js"></script>
	<script src="assets/bootstrap/js/bootstrap-tab.js"></script>
	<script src="assets/bootstrap/js/bootstrap-tooltip.js"></script>
	<script src="assets/bootstrap/js/bootstrap-popover.js"></script>
	<script src="assets/bootstrap/js/bootstrap-button.js"></script>
	<script src="assets/bootstrap/js/bootstrap-collapse.js"></script>
	<script src="assets/bootstrap/js/bootstrap-carousel.js"></script>
	<script src="assets/bootstrap/js/bootstrap-typeahead.js"></script>

    </body>
</html>