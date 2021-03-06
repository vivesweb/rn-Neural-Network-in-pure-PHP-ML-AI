<?php

/** rn class. red neuronal (neural network)
 *
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0.1
 * @license GNU General Public License v3.0
 * 
 * 	Thanks to:
 *	- https://github.com/infostreams/neural-network/blob/master/class_neuralnetwork.php
 *	- https://gist.github.com/ikarius6/26851fb7220837e8016fe0c425d34dd6
 *	- https://mattmazur.com/2015/03/17/a-step-by-step-backpropagation-example/
 *  - https://www.youtube.com/channel/UCy5znSnfMsDwaLlROnZ7Qbg
*/

require_once( 'rn_layer.class.php' );

/** rn class. red neuronal (neural network)
 *  
 * Create a neural network class with one array with number of nodes each layer.
 * Example: If we want a neural network with 4 layers (2 inputs neurons, 12 hidden neurons, 8 hidden neurons and 1 output neuron) as 2x12x8x1
 * Only need to send as paramater an array as: [2, 12, 8, 1]
 * 
 * $rn = new rn( [2, 12, 8, 1] );  // 2x12x8x1
 * 
 * @param array $arrLayersNodes
*/

class rn
{
    public $layers					= [];
	public $num_layers				= 0;
	public $alpha 					= 1;
	public $InformEachXBlock 		= 100; // echoes actual error each 100 blocks of datasets
	public $InformEachXEpoch 		= 100; // echoes actual error each 100 epochs
	public $MaxItemsMeanSquareError = 1000000; // Max number of Train items to get the Mean Square Error
	public $num_epochs 				= 1000;
	public $MeanSquareError			= 0;
	public $system_resources		= null;
	
    public function __construct( $arrLayersNodes = [2, 12, 8, 1] ) {
		$this->layers = [];
		$this->num_layers = count( $arrLayersNodes );
		$previous_layer = null;
		$system_resources = new system_resources(); // Create obj class system_resources, needed for control CPU Temperature in process of BackPropagation of the Deep Learning
		
		// Init layers
		for($i=0;$i<$this->num_layers;$i++){
			$NumOfNewNodes = $arrLayersNodes[$i];
			
			$this->layers[] = new layer( $NumOfNewNodes );
			
			if( $previous_layer != null ){
				$previous_layer->next_layer = $this->layers[$i];
				
				$arrNodeWeightsToNextLayer = array_fill(0, $NumOfNewNodes, .5);
				
				$previous_layer->fInitNodeWeightsToNextLayer( $arrNodeWeightsToNextLayer, .5 );
			}
			
			$this->layers[$i]->previous_layer = $previous_layer;
			$previous_layer = $this->layers[$i];
		} // /for i
		
		$this->layers[0]->fSetImFirst();
		$this->layers[$this->num_layers-1]->fSetImLast();
	} // / __construct


	
	/**
	 * Set the activation function for all the layers
	 * sigm = sigmoidal
	 * tanh = hyperbolic tangent
	 * 
	 * Optionaly, we can set different activation funcion for each layer
	 * 
	 * @param string $activation_function  ['sigm'|'tanh']
	 */
	public function fSet_activation_function( $activation_function ){
		for($i=0;$i<$this->num_layers;$i++){
			$this->layers[$i]->fSet_activation_function($activation_function);
		}
	} // /fSet_activation_function()
		



	/**
	 * Set Num Epochs to model
	 * 
	 * @param array $arrTrainInputItems
	 * @param array $arrTrainOutputItems
	 * @param int $Epochs (Optional. Default 1000 Epochs)
	 */
	public function fSet_num_epochs( $Epochs ){
		$this->num_epochs = $Epochs;
	} // /fSet_num_epochs

	
	

	/**
	 * Set Num of max items to be calculated at Mean Square Error
	 * 
	 * @param array $MaxItemsMeanSquareError
	 */
	public function fSet_MaxItemsMeanSquareError( $MaxItemsMeanSquareError ){
		$this->MaxItemsMeanSquareError = $MaxItemsMeanSquareError;
	} // /fSet_MaxItemsMeanSquareError
	
	
	/**
	 * Run our model
	 * 
	 * @param int $id_output_node
	 * @param array $arrInputValues
	 */
	public function run( $id_output_node, $arrInputValues ){
		$idLastLayer = $this->num_layers-1;
		return $this->layers[$idLastLayer]->Y( $id_output_node, $arrInputValues );
	} // /run()
	

	/**
	 * BackPropagation function
	 * 
	 * @param array $arrInputValues
	 * @param array $arrOutputValues
	 */
	public function BackPropagation( $arrInputValues, $arrOutputValues ){
		$idLastLayer 				= $this->num_layers-1;
		$OutputLayer 				= $this->layers[$idLastLayer];
		$previous_layer				= $OutputLayer->previous_layer;
		$previous_layer_num_nodes	= $previous_layer->num_nodes;
		$num_output_nodes 			= $OutputLayer->num_nodes;
		$inputLayer					= $this->layers[0];
		$input_layer_num_nodes		= $inputLayer->num_nodes;

		for($i=0; $i<$num_output_nodes; $i++){
			$OutputLayer->nodes[$i]->delta = $OutputLayer->deltaLastLayer( $i, $arrInputValues, $arrOutputValues );
		}
		
		// Set Error to the other layers

		for($id_layer=0;$id_layer<($this->num_layers-1);$id_layer++){
			$layer 						= $this->layers[$id_layer];
			$next_layer 				= $layer->next_layer;
			$next_layer_num_nodes		= $next_layer->num_nodes;
			$num_nodes 					= $layer->num_nodes;
			
		
			for($i=0; $i<$num_nodes; $i++){
				$layer_node = $layer->nodes[$i];
				$Y = $layer->Y( $i, $arrInputValues );
				for($j=0;$j<$next_layer_num_nodes;$j++){
					$this->layers[$id_layer]->nodes[$i]->arr_weights_to_next_layer[$j] -= $this->alpha * $this->error($Y, $i, $j, $arrInputValues, $arrOutputValues, $layer->next_layer);
					$next_layer->nodes[$j]->bias -= $this->alpha * (($next_layer->imLast)?$next_layer->nodes[$j]->delta:$next_layer->deltaLayer($i, $j, $arrInputValues, $arrOutputValues));
					//exit(1);
				}
			}
		}
	} // /BackPropagation()


	/**
	 * Set Alpha (Learning rate)
	 * 
	 * @param float $alpha
	 */
	public function set_alpha($alpha){
		$this->alpha = $alpha;
	}


	/**
	 * Get the error
	 * 
	 * @param float $Y
	 * @param int $i
	 * @param int $j
	 * @param array $arrTrainInputItems
	 * @param array $arrTrainOutputItems
	 * @param object $next_layer
	 * @return float error
	 */
	public function error($Y, $i, $j, $arrInputValues, $arrOutputValues, $next_layer){
		$Delta = (($next_layer->imLast)?$next_layer->nodes[$j]->delta:$next_layer->deltaLayer($i , $j, $arrInputValues, $arrOutputValues));
		return $Y * $Delta;
	}


	/**
	 * Master function who calls BackPropagation process
	 * in the future it will be multithread
	 * 
	 * @param array $arrTrainInputItems
	 * @param array $arrTrainOutputItems
	 * @param int $Epochs (Optional. Default 1000 Epochs)
	 */
	public function Learn($arrTrainInputItems, $arrTrainOutputItems, $arrValidationInputItems = NULL, $arrValidationOutputItems = NULL, $arrTestInputItems = NULL, $arrTestOutputItems = NULL, $Epochs = NULL){
		if( !isset($arrValidationInputItems) || is_null($arrValidationInputItems) ){
			$arrValidationInputItems 	= $arrTrainInputItems;
			$arrValidationOutputItems 	= $arrTrainOutputItems;
		}
		if( !isset($arrTestInputItems) || is_null($arrTestInputItems) ){
			$arrTestInputItems	 		= $arrValidationInputItems;
			$arrTestOutputItems 		= $arrValidationOutputItems;
		}
		if( isset($Epochs) && !is_null($Epochs) ){
			$this->num_epochs 			= $Epochs;
		}

		$num_sample_data = count($arrTrainInputItems);
		for($i = 0;$i<$this->num_epochs;$i++){

			for($j=0;$j<$num_sample_data;$j++){
				$this->BackPropagation($arrTrainInputItems[$j],$arrTrainOutputItems[$j]);

				if( $j%$this->InformEachXBlock == 0 ){
					$MeanSquareErrorValidationData 	= $this->MeanSquareError( $arrValidationInputItems, $arrValidationOutputItems );
					$MeanSquareErrorTestData 		= $this->MeanSquareError( $arrTestInputItems, $arrTestOutputItems );
					$StrEcho  = 'Item '.$j.'/'.$num_sample_data.' . Epoch '.$i.'/'.$this->num_epochs;
					$StrEcho .= '. Actual error: (Validaton Data: '.number_format($MeanSquareErrorValidationData, 4, '.', ','). ')';
					$StrEcho .= '/ (Test Data: '.number_format($MeanSquareErrorTestData, 4, '.', ','). ')';
					echo $StrEcho.PHP_EOL;
					$this->MeanSquareError = $MeanSquareErrorValidationData;
				}
			}
			if( $i%$this->InformEachXEpoch == 0 && $this->InformEachXEpoch > $this->InformEachXBlock ){
				$MeanSquareErrorValidationData 	= $this->MeanSquareError( $arrValidationInputItems, $arrValidationOutputItems );
				$MeanSquareErrorTestData 		= $this->MeanSquareError( $arrTestInputItems, $arrTestOutputItems );
				$StrEcho  = 'Epoch '.$i.'/'.$this->num_epochs;
				$StrEcho .= '. Actual error: (Validaton Data: '.number_format($MeanSquareErrorValidationData, 4, '.', ','). ')';
				$StrEcho .= '/ (Test Data: '.number_format($MeanSquareErrorTestData, 4, '.', ','). ')';
				echo $StrEcho.PHP_EOL;
				$this->MeanSquareError = $MeanSquareErrorValidationData;
			}
		}
	} // /Learn()



	/**
	 * Mean Square Error
	 * 
	 * @param array $arrValidationInputItems
	 * @param array $arrValidationOutputItems
	 * @return float $MeanSquareError
	 */
	public function MeanSquareError($arrValidationInputItems, $arrValidationOutputItems){
		$last_id_layer = $this->num_layers - 1;
		$num_output_nodes = $this->layers[$last_id_layer]->num_nodes;
		$ErrorSum = 0;
		$num_sample_data = count($arrValidationInputItems);

		if($num_sample_data > $this->MaxItemsMeanSquareError){
			$num_sample_data = $this->MaxItemsMeanSquareError;
		}

		$NumItemsAdded = 0;
		for($i=0;$i<$num_sample_data ;$i++){
			for($j=0;$j<$num_output_nodes;$j++){
				$ValueEstimated = $this->run($j, $arrValidationInputItems[$i]);
				$Diff = ( $arrValidationOutputItems[$i][$j]-$ValueEstimated );
				$ErrorSum += pow($Diff, 2);
				++$NumItemsAdded;
			}
		}

		$MeanSquareError = ( $ErrorSum / ($NumItemsAdded) );
		return $MeanSquareError;
	} // /MeanSquareError()


	/**
	 * Echo output values of neural network with given values. If DesiredValues given, it will echo it too
	 * 
	 * @param array $arrTrainInputItems
	 * @param array $arrTrainOutputItems
	 * @param int $Epochs (Optional. Default 1000 Epochs)
	 */
	public function EchoOutputValues($arrInputItems, $arrDesiredOutputItems = null){
		$last_id_layer = $this->num_layers - 1;
		$num_output_nodes = $this->layers[$last_id_layer]->num_nodes;

		echo 'Input Values: '.implode(',', $arrInputItems).PHP_EOL;
		for($j=0;$j<$num_output_nodes;$j++){
			echo 'Output neuron ['.$j.']: '. $this->run($j, $arrInputItems);
			if( isset($arrDesiredOutputItems) ){
			  echo '. Expect: '.$arrDesiredOutputItems[$j];
			}
			echo PHP_EOL;
		}
		echo PHP_EOL;
	} // /EchoOutputValues()

	
	/**
	 * Export the data of neural network into jSON object
	 * 
	 * @return json $arrJSON
	 */
	public function exportData2Json(){
		$FirstLayer 		= $this->layers[0];
		$NumInputNodes 		= $FirstLayer->num_nodes;
		$arrJSONLayers 		= [];


		// Create an array of jSON'S data layers
		foreach($this->layers as $layer){
			$arrJSONLayers[] = json_decode( $layer->exportData2Json() );
		}

		$arrJSON = [ 'InaticaNeuralNetwork' => 
						[ 'NumInputNeurons'         => $this->layers[0]->num_nodes,
						  'NumOutputNeurons'        => $this->layers[$this->num_layers-1]->num_nodes,
						  'CreationDate'         	=> date("Y-m-d H:i:s"),
						  'NumTotalLayers'   		=> $this->num_layers,
						  'NumHiddenLayers'   		=> $this->num_layers -2, // All layers without input layer and without output layer
						  'NumEpochs'               => $this->num_epochs, // number of epochs that the neural network has done to learn
						  'MeanSquareError'			=> $this->MeanSquareError,
						  'Layers'					=> $arrJSONLayers
						  ]
						
		];

		return json_encode($arrJSON);
	} // /exportData2Json()


	

	/**
	 * Import the data of jSON object into rn
	 * 
	 * @param json $JsonData
	 */
	public function importJson2Data($JsonData){
		$this->num_layers 		= $JsonData->NumTotalLayers;
		$this->NumEpochs 		= $JsonData->NumEpochs;
		$this->MeanSquareError 	= $JsonData->MeanSquareError;
		$previous_layer = null;

		$this->layers = [];

		$JsonLayers = $JsonData->Layers;
		
		$i = 0;
		foreach($JsonLayers as $Layer){
			$this->layers[] = new layer( $Layer->Layer->num_nodes );
			$this->layers[$i]->importJson2Data($Layer);

			if( $previous_layer != null ){
				$previous_layer->next_layer = $this->layers[$i];
			}
			
			$this->layers[$i]->previous_layer = $previous_layer;
			$previous_layer = $this->layers[$i];

			++$i;
		} // /foreach
	} // /importJson2Data()
} // /rn class
?>