<?php
/** layer class include
 *
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0.1
 * @license GNU General Public License v3.0
 */

require_once( 'rn_node.class.php' );

/* layer class
 *
 * @param int $num_nodes num of nodes that will have the layer
 * @param float $default_weight default weight for each node
 * @param float $default_bias default weight for bias
*/
class layer
{
    public $num_nodes;
	public $nodes;
	public $previous_layer;
	public $next_layer;
	public $imFirst;
	public $imLast;
	public $activation_function;
	
    public function __construct( $num_nodes = 2, $default_weight = [.5], $default_bias = .5) {
		$this->nodes = [];
		
		// Init nodes
		for($i=0;$i<$num_nodes;$i++){
			$this->nodes[] = new node( $default_weight, $default_bias);
		}
		
		$this->default_bias			= $default_bias;
		$this->num_nodes 			= $num_nodes;
		$this->imFirst 				= false; // Input Data
		$this->imLast 				= false; // Output Data
		$this->activation_function 	= 'sigm'; // sigm | tanh | relu | bin
	} // /__construct()

	/**
	 * Get Delta for Last Layer
	 */
	public function deltaLastLayer($id_output_node, $arrInputValues, $arrOutputValues){
		$Y 			= $this->Y( $id_output_node, $arrInputValues );
		$Derivate 	= $this->DerivateFunctionActivation(  $id_output_node, $arrInputValues, $Y );
		$RealError 	= $this->RealError(  $id_output_node, $arrOutputValues, $Y );
		return $Derivate*$RealError;
	} // /deltaLastLayer()



	/**
	 * Get Delta for Layer
	 */
	public function deltaLayer($id_previous_node, $id_output_node, $arrInputValues, $arrOutputValues){
		
		$next_layer 				= $this->next_layer;
		$next_layer_num_nodes 		= $next_layer->num_nodes;
		$previous_layer 			= $this->previous_layer;
		$previous_layer_num_nodes 	= $previous_layer->num_nodes;
		$num_nodes 					= $this->num_nodes;
		$outputNode 				= $this->nodes[$id_output_node];
		$Y 							= $this->Y( $id_output_node, $arrInputValues );

		$sum=0;
		for($i=0; $i<$next_layer_num_nodes; $i++){
			$NextWeight = $this->nodes[$id_output_node]->arr_weights_to_next_layer[$i];
			$DeltaLayer = (($next_layer->imLast)?$next_layer->nodes[$i]->delta:$next_layer->deltaLayer($id_output_node, $i, $arrInputValues, $arrOutputValues));
			$sum 		+= ($NextWeight*$DeltaLayer);
		}

		$Derivate = $this->DerivateFunctionActivation(  $id_output_node, $arrInputValues, $Y );
		return $Derivate * $sum;
	} // /deltaLastLayer()


	/**
	 * Get Real Error for Last Layer
	 */
	public function RealError($id_output_node, $arrOutputValues, $Y){
		return ($Y-$arrOutputValues[$id_output_node]);
	} // /deltaLastLayer()
	
	
	/**
	 * Set the activation function for this layer
	 * sigm = sigmoidal
	 * tanh = hyperbolic tangent
	 * 
	 * We can set different activation funcion for each layer
	 * 
	 * @param string $activation_function ['sigm'|'tanh']
	 */
	public function fSet_activation_function( $activation_function ){
		$this->activation_function = $activation_function;
	} // /fSet_activation_function()
	

	
	/**
	 * Set this layer as first layer in neural network
	 */
	public function fSetImFirst( ){
		$this->imFirst = true;
	} // /fSetImFirst()
	

	/**
	 * Set this layer as last layer in neural network
	 */
	public function fSetImLast( ){
		$this->imLast = true;
	} // /fSetImLast()

		
	/**
	 * Set the weights to next layer
	 * 
	 * @param array array of weights
	 */
	public function fInitNodeWeightsToNextLayer( $arrNewWeights, $bias_weight = .5 ){
		// Init nodes
		for($i=0;$i<$this->num_nodes;$i++){
			$this->nodes[$i]->setNodeWeightsToNextLayer ( $arrNewWeights );
			$this->nodes[$i]->bias = $bias_weight;
		}
	} // /fInitNodeWeightsToNextLayer()


	/**
	 * Get the derivate function of activacion function
	 * 
	 * @param int $id_output_node
	 * @param array $arrInputValues
	 * @return float
	 */
	public function DerivateFunctionActivation(  $id_output_node, $arrInputValues, $Y=null ){
		if(! $Y ){
			$f = $this->Y( $id_output_node, $arrInputValues );
		} else {
			$f = $Y;
		}
		switch($this->activation_function){
			case 'relu': 	return $f > 0;
							break;
			case 'tanh': 	$tanh = tanh($f);
							return (1 - $tanh) * (1 + $tanh);
							break;
			case 'sigm':
			default:		return $f*(1-$f);
							break;
		}
	}// /DerivateFunctionActivation()
	
	
	/** Activation function
	 * 
	 * @param float
	 */
	function f($x){
		switch( $this->activation_function ){
			case 'relu':	return $x * ($x > 0); // or max(0, $x);
							break;
			case 'tanh':	return tanh($x);
							break;
			case 'sigm':
			default:		return 1 / (1 + exp(-$x));
							break;
		}
	} // /f()

	
	/** Output value
	 * 
	 * @param int $id_output_node
	 * @param array $arrInputValues
	 * @return float
	 */
	public function Y( $id_output_node, $arrInputValues ){
		if( $this->imFirst ){
			return  $arrInputValues[$id_output_node];
		} else {
			$Y = 0;
			$PreviousLayer 		= $this->previous_layer;
			$NumNodesPrevLayer 	= $PreviousLayer->num_nodes;

			for($i=0;$i<$NumNodesPrevLayer;$i++){
					$sumPreviousLayer 	= $PreviousLayer->Y( $i, $arrInputValues );
					$NextWeight		 	= $PreviousLayer->nodes[$i]->arr_weights_to_next_layer[$id_output_node];
					$Y 					+= ( $sumPreviousLayer*$NextWeight );
					
				}

			$Y += $this->nodes[$id_output_node]->bias;

			return $this->f($Y);
		} // if !imFirst
	} // /Y()


	/**
	 * Export the data of layer into jSON object
	 * 
	 * @return json $arrJSON
	 */
	public function exportData2Json(){
		$arrJSONNodes = [];

		// Create an array of jSON'S data layers
		foreach($this->nodes as $node){
			$arrJSONNodes[] = json_decode( $node->exportData2Json() );
		}

		$arrJSON = [ 'Layer' => 
						[ 'num_nodes'         		=> $this->num_nodes,
						  'imFirst'         		=> $this->imFirst,
						  'imLast'         			=> $this->imLast,
						  'activation_function'   	=> $this->activation_function,
						  'Nodes'					=> $arrJSONNodes
						  ]
						
		];

		return json_encode($arrJSON);
	} // /exportData2Json()




	/**
	 * Import the data of jSON object into layer
	 * 
	 * @param json $JsonData
	 */
	public function importJson2Data($JsonData){
		$JsonDataLayer 				= $JsonData->Layer;
		$this->num_nodes 			= $JsonDataLayer->num_nodes;
		$this->imFirst 				= $JsonDataLayer->imFirst;
		$this->imLast 				= $JsonDataLayer->imLast;
		$this->activation_function 	= $JsonDataLayer->activation_function;

		$this->nodes = [];
		
		$i = 0;
		foreach($JsonDataLayer->Nodes as $Node){
			$this->nodes[] = new node( );
			$this->nodes[$i++]->importJson2Data($Node);
		}
	} // /importJson2Data()
} // /class
?>