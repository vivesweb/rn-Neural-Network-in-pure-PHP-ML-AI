<?php

/** node class
 * 
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0.1
 * @license GNU General Public License v3.0
 * 
 * @param array $arr_weights_to_next_layer
*/

class node
{
    public $arr_weights_to_next_layer = array();
	public $bias 		= .5;
	public $delta		= null; // Used for nodes of last layer in BackPropagation function
	
    public function __construct( $arr_weights_to_next_layer = [.5], $default_bias = .5) { 
		$this->arr_weights_to_next_layer 	= $arr_weights_to_next_layer;
		$this->bias 						= $default_bias;
	} // /__construct()
	


	/**
	 * Init the weights from this node to each node of the next layer
	 * 
	 * @param array array of weights
	 */
	public function setNodeWeightsToNextLayer( $arrNewWeights = [.5] ) { 
		$this->arr_weights_to_next_layer = $arrNewWeights;
	} // /setNodeWeightsToNextLayer()


	/**
	 * Export the data of node into jSON object
	 * 
	 * @return json $arrJSON
	 */
	public function exportData2Json(){
		$arrJSON = [ 'Node' => 
						[ 	'arr_weights_to_next_layer'  => $this->arr_weights_to_next_layer,
							'bias'         				 => $this->bias,
						]
						
		];

		return json_encode($arrJSON);
	} // /exportData2Json()




	/**
	 * Import the data of jSON object into node
	 * 
	 * @param json $JsonData
	 */
	public function importJson2Data($JsonData){
		$JsonDataNode 						= $JsonData->Node;
		$this->arr_weights_to_next_layer 	= $JsonDataNode->arr_weights_to_next_layer;
		$this->bias							= $JsonDataNode->bias;
	} // /importJson2Data()
}
?>