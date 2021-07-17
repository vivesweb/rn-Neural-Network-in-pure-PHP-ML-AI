<?php

/** example rn class. red neuronal (neural network)
 *
 * BASIC USAGE:
 * 
 * Requeriments:
 * - A minimum (minimum, minimum, minimum requeriments is needed). Tested on:
 * 		- Simple Raspberry pi (B +	512MB	700 MHz ARM11) with Raspbian Lite PHP7.3 ^_^
 * 		- VirtualBox Ubuntu Server 20.04.2 LTS (Focal Fossa) with PHP7.4.3 
 * - Needed 1 hidden layer at least
 * 
 * 
 * INSTALLATION:
 * A lot of easy :). It is written in PURE PHP. Only need to inclue the files. Tested on basic PHP installation
 * 
 * require_once( 'rn.class.php' );
 * 
 * 
 * - Define train input items array
 * 
 * $arrTrainInputItems	= [
 *	[0, 0],
 *	[0, 1],
 *	[1, 0],
 *	[1, 1]
 * ];
 * 
 * 
 * - Define desired output values array
 * 
 * $arrTrainOutputItems 	= [
 *	[0.1, 0.2],
 *	[0.3, 0.4],
 *	[0.5, 0.6],
 *	[0.7, 0.8]
 * ];
 * 
 * 
 * - Create neural network object
 * 
 * $rn = new rn( [3, 1, 2] );  // 3x1x2 = 3 layers. 3 input neurons, hidden layer with 1 neuron, 2 output neurons
 * 
 * If you want for example 4 layers (3x12x8x2): 3 input neurons, hidden layer with 12 neurons, hidden layer with 8 neurons, output layer with 2 neurons, simply do:
 * $rn = new rn( [3, 12, 8, 2] );
 * 
 * 
 * - Print All Train Input data, Neural Network Output data & Train Desired Data
 * 
 * $num_sample_data = count($arrTrainInputItems);
 *
 * echo "Default Values: ".PHP_EOL;
 * 
 * for($i=0;$i<$num_sample_data;$i++){
 *   $rn->EchoOutputValues( $arrTrainInputItems[$i], $arrTrainOutputItems[$i] );
 * }
 * 
 * 
 * - Do learn process:
 * 
 * $rn->Learn($arrTrainInputItems, $arrTrainOutputItems);
 * 
 * 
 * For full configuration, please, read the file readme.txt
 * 
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0
 * @license GNU General Public License v3.0
 * 
 * 	Thanks to:
 *	- https://github.com/infostreams/neural-network/blob/master/class_neuralnetwork.php
 *	- https://gist.github.com/ikarius6/26851fb7220837e8016fe0c425d34dd6
 *	- https://mattmazur.com/2015/03/17/a-step-by-step-backpropagation-example/
 *  - https://www.youtube.com/channel/UCy5znSnfMsDwaLlROnZ7Qbg
*/

require_once( 'rn.class.php' );


// Prepare configuration of our Neural Network


// Train Values

$arrTrainInputItems	= [
	[0, 0],
	[0, 1],
	[1, 0],
	[1, 1]
];

$arrTrainOutputItems 	= [
	[0.1, 0.2],
	[0.3, 0.4],
	[0.5, 0.6],
	[0.7, 0.8]
];

// Some variables for use later
$num_sample_data = count($arrTrainInputItems);

$NumEpochs = 1000;


// Most important part of Neural Network

$rn = new rn( [2, 1, 2] );  // 2x1x2 = 3 layers. 2 input neurons, hidden layer with 1 neuron, 2 output neurons.
$rn->fSet_num_epochs( $NumEpochs ); // Set rn Num Epochs (1000 by default config if not set).
$rn->fSet_activation_function( 'sigm' ); // Set the default activation function ('sigm' if not set).
$rn->set_alpha( 1 ); // Set the default activation function ('sigm' if not set).
$rn->InformEachXBlock = 10;


// Print Not trained Neural Network Input data, Output data & Desired Values

echo 'Default Values: '.PHP_EOL;

for($i=0;$i<$num_sample_data;$i++){
    $rn->EchoOutputValues( $arrTrainInputItems[$i], $arrTrainOutputItems[$i] );
}


// Process of learn

echo 'Learning '.$NumEpochs.' Epochs....'.PHP_EOL;

$rn->Learn($arrTrainInputItems, $arrTrainOutputItems);


// Print trained Neural Network Input data, Output data & Desired Values

echo 'Final Values: '.PHP_EOL;

for($i=0;$i<$num_sample_data;$i++){
    $rn->EchoOutputValues( $arrTrainInputItems[$i], $arrTrainOutputItems[$i] );
}


// We can export the data to export the trained model to use it on other sites, as for example, a simple Production Web Server :)
echo $rn->exportData2Json().PHP_EOL;
?>