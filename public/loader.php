<?php

use Easygd\Convolution;
use Easygd\ConvolutionFilter;
use Easygd\ConvolutionFunctions;
use Easygd\Filter;
use Easygd\Image;

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé

require '../boostrap/conf.php';

$action = $_GET['action'];
$fileSrc =  $_GET['filesrc'];

switch ($action) {

	case 'info':
		$imageInfo = (new Image())->load($fileSrc)->getInfos();
		echo json_encode($imageInfo->toArray());
		break;
	case 'convolution_custom':
		$divisor = $_GET['divisor'];
		$offset = $_GET['offset'];
		$matrix = json_decode($_GET['matrix']);
		$fileSrc = PUBLIC_PATH  . $fileSrc;
		$imgSrc = (new Image())->load($fileSrc);
		$convolution = (new Convolution)->create($matrix)->setDivisor($divisor)->setOffset($offset);
		(new ConvolutionFilter())->create($convolution)->process($imgSrc)->show();
		break;
	case 'convolution_info':
		$convolution = $_GET['convolution'];
		$convolution = ConvolutionFunctions::$convolution();
		$json =
			[
				'matrix' => $convolution->getMatrix(),
				'divisor' => $convolution->getDivisor(),
				'offset' => $convolution->getOffset(),
			];
		echo json_encode($json);
		break;
	case 'histogramme':
		/**
		 * trop long
	  set_time_limit(0);
	  $histogramme = new Histogramme();
	  $imgSrc = (new Image())->createFrom($fileSrc);
	  $runer = RunThrough::create($imgSrc);
	  $runer->attach($histogramme);
	  $runer->process();
	  $histogramme->computeSigma($imgSrc->getWidth() * $imgSrc->getHeight());
	  $histogramme->save(THUMB_HISTO);
		 * 
		 */
		break;
	case 'filter':

		// $filterType = 'FILTER_' . strtoupper($_GET['filtertype']);
		// $filterType = constant('Filter::' . $filterType);

		$filterName = $_GET['filtername'];
		// if ($_GET['filtertype'] != 'lookuptable')
		// 	$filterName = strtoupper($_GET['filtertype']) . '_' . strtoupper($_GET['filtername']);

		$arg1 = isset($_GET['value']) ? $_GET['value'] : 0;

		if (empty($arg1))
			$arg1 = isset($_GET['red']) ? $_GET['red'] : 0;

		$arg2 = isset($_GET['green']) ? $_GET['green'] : 0;
		$arg3 = isset($_GET['blue']) ? $_GET['blue'] : 0;

		$fileSrc = PUBLIC_PATH  . $fileSrc;


		Filter::$filterName($arg1, $arg2, $arg3)->process((new Image())->load($fileSrc))->show();

		break;
	default:
		break;
}
