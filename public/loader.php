<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé

require 'conf.php';

$action = $_GET['action'];
$fileSrc = $_GET['filesrc'];
switch ($action) {

    case 'info' :
	$imageInfo = Easy\Image::getInfos($fileSrc);
	echo json_encode($imageInfo->toArray());
	break;
    case 'convolution_custom':
	$divisor = $_GET['divisor'];
	$offset = $_GET['offset'];
	$matrix = json_decode($_GET['matrix']);
	$imgSrc = Easy\Image::createFrom($fileSrc);
	$convolution = \Easy\Convolution::create($matrix);
	$convolution->setDivisor($divisor)->setOffset($offset);
	$convolution->process($imgSrc)->show();
	break;
    case 'convolution_info' :
	$convolution = 'CONVOLUTION_' . $_GET['convolution'];
	$convolution = \Easy\Convolution::$convolution();
	$json = array(
	    'matrix' => $convolution->getMatrix(),
	    'divisor' => $convolution->getDivisor(),
	    'offset' => $convolution->getOffset(),
	);
	echo json_encode($json);
	break;
    case 'histogramme' :
	/**
	 * trop long
	  set_time_limit(0);
	  $histogramme = new Easy\Histogramme();
	  $imgSrc = Easy\Image::createFrom($fileSrc);
	  $runer = Easy\RunThrough::create($imgSrc);
	  $runer->attach($histogramme);
	  $runer->process();
	  $histogramme->computeSigma($imgSrc->getWidth() * $imgSrc->getHeight());
	  $histogramme->save(THUMB_HISTO);
	 * 
	 */
	break;
    case 'filter' :

	$filterType = 'FILTER_' . strtoupper($_GET['filtertype']);
	$filterType = constant('Easy\Filter::' . $filterType);
	
	$filterName = strtoupper($_GET['filtername']);
	if ($_GET['filtertype'] != 'lookuptable')
	    $filterName = strtoupper($_GET['filtertype']) . '_' . strtoupper($_GET['filtername']);

	$arg1 = isset($_GET['value']) ? $_GET['value'] : 0;
	
	if (empty($arg1))
	    $arg1 = isset($_GET['red']) ? $_GET['red'] : 0;

	$arg2 = isset($_GET['green']) ? $_GET['green'] : 0;
	$arg3 = isset($_GET['blue']) ? $_GET['blue'] : 0;

	$imgSrc = Easy\Image::createFrom($fileSrc);
	$filter = Easy\Filter::create($filterType, $filterName, $arg1, $arg2, $arg3);
	$filter->process($imgSrc)->show();

	break;
    default :
	break;
}
?>