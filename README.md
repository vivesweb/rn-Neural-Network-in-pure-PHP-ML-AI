# rn Neural Network in pure PHP - ML Machine Learning - AI Artificial Intelligence
RED NEURONAL

# BASIC USAGE:
 
 Requeriments:
 
 - A minimum (minimum, minimum, minimum requeriments is needed). Tested on:
 		
    - Simple Raspberry pi (B +	512MB	700 MHz ARM11) with Raspbian Lite PHP7.3 ^_^
 		
    - VirtualBox Ubuntu Server 20.04.2 LTS (Focal Fossa) with PHP7.4.3 
 - Needed 1 hidden layer at least
 
 
 # INSTALLATION:
 A lot of easy :). It is written in PURE PHP. Only need to inclue the files. Tested on basic PHP installation
 
 require_once( 'rn.class.php' );
 
 
 - Define train input items array
 
 $arrTrainInputItems	= [
	[0, 0],
	[0, 1],
	[1, 0],
	[1, 1]
 ];
 
 
 - Define desired output values array
 
 $arrTrainOutputItems 	= [
	[0.1, 0.2],
	[0.3, 0.4],
	[0.5, 0.6],
	[0.7, 0.8]
 ];
 
 
 - Create neural network object
 
 $rn = new rn( [3, 1, 2] );  // 3x1x2 = 3 layers. 3 input neurons, hidden layer with 1 neuron, 2 output neurons
 
 If you want for example 4 layers (3x12x8x2): 3 input neurons, hidden layer with 12 neurons, hidden layer with 8 neurons, output layer with 2 neurons, simply do:
 
 $rn = new rn( [3, 12, 8, 2] );
 
 
 - Print All Train Input data, Neural Network Output data & Train Desired Data
 
 $num_sample_data = count($arrTrainInputItems);

 echo "Default Values: ".PHP_EOL;
 
 for($i=0;$i<$num_sample_data;$i++){
 
   $rn->EchoOutputValues( $arrTrainInputItems[$i], $arrTrainOutputItems[$i] );
   
 }
 
 
 - Do learn process:
 
 $rn->Learn($arrTrainInputItems, $arrTrainOutputItems);
 
 
# Resume of Methods:

- CREATE NEURAL NETWORK:
 
$rn = new rn( [ARRAY OF INT] );

Example:

$rn = new rn( [3, 1, 2] );  // 3x1x2 = 3 layers. 3 input neurons, hidden layer with 1 neuron, 2 output neurons



- PRINT ALL TRAIN INPUT DATA, NEURAL NETWORK OUTPUT DATA & TRAIN DESIRED DATA:

$rn->EchoOutputValues( $arrTrainInputItems, $arrTrainOutputItems );

Example:

$rn->EchoOutputValues( $arrTrainInputItems[$i], $arrTrainOutputItems[$i] );



- PROCESS OF LEARN

$rn->Learn([ARRAY OF FLOAT], [ARRAY OF FLOAT]);

Example:

$rn->Learn($arrTrainInputItems, $arrTrainOutputItems);



- SET THE NUMBER OF EPOCHS:

$rn->fSet_num_epochs( INT ); // Set rn Num Epochs. Default: 1000

Example:

$rn->fSet_num_epochs( $NumEpochs );



- SET THE ACTIVATION FUNCTION FOR ALL OF LAYERS:

$rn->fSet_activation_function( STRING ); // ['sigm' | 'tanh'] Default: 'sigm'

Example:

$rn->fSet_activation_function( 'sigm' ); // ['sigm' | 'tanh'] Default: 'sigm'



- SET THE ACTIVATION FUNCTION FOR ONE LAYERS:

$layer->fSet_activation_function( STRING ); // ['sigm' | 'tanh'] Default: 'sigm'

Example:

$rn->layer[1]->fSet_activation_function( 'sigm' ); // ['sigm' | 'tanh'] Default: 'sigm'


- SET LEARNING RATE

$rn->set_alpha( FLOAT );

Example:

$rn->set_alpha( .5 );



- GET THE OUPUT VALUE OF NEURAL NETWORK OF ONE OUTPUT NODE:

- Output node: If we have 2 neurons, we can get the output value for Neuron[0] | Neuron[1]

$rn->run( INT OUTPUT NODE ID, ARRAY OF FLOAT INPUT VALUES );

Example:

$rn->run( $id_output_node, $arrInputValues );


- GET THE MEANSQUARE ERROR OF THE MODEL:

$rn->MeanSquareError(ARRAY OF INPUT VALUES, ARRAY OF DESIRED VALUES);

Example:

$rn->MeanSquareError($arrTrainInputItems, $arrTrainOutputItems);


- EXPORT THE TRAINED MODEL CONFIGURATION TO A STANDARD JSON STRING:

echo $rn->exportData2Json();


- IMPORT A TRAINED DATA STRING IN JSON FORMAT TO OUR NEURAL NETWORK CLASS:

$rn->importJson2Data( STRING JSON );

Example:
$JsonDataStr = '{"InaticaNeuralNetwork":{"NumInputNeurons":3,"NumOutputNeurons":2,"CreationDate":"2021-07-11 16:59:26","NumTotalLayers":3,"NumHiddenLayers":1,"NumEpochs":1000,"MeanSquareError":0.0006291862647577675,"Layers":[{"Layer":{"num_nodes":3,"imFirst":true,"imLast":false,"activation_function":"sigm","bias":-2.2457597339700284,"Nodes":[{"Node":{"arr_weights_to_next_layer":[0.5]}},{"Node":{"arr_weights_to_next_layer":[2.9314473212095957]}},{"Node":{"arr_weights_to_next_layer":[1.9449224564817373]}}]}},{"Layer":{"num_nodes":1,"imFirst":false,"imLast":false,"activation_function":"sigm","bias":-2.0579661087844885,"Nodes":[{"Node":{"arr_weights_to_next_layer":[3.0701300830533205,3.739411894753024]}}]}},{"Layer":{"num_nodes":2,"imFirst":false,"imLast":true,"activation_function":"sigm","bias":0.5,"Nodes":[{"Node":{"arr_weights_to_next_layer":0.5}},{"Node":{"arr_weights_to_next_layer":0.5}}]}}]}}';

$JsonData = json_decode( $JsonDataStr );

$rn->importJson2Data($JsonData->InaticaNeuralNetwork);
 
 
 # Future Plans
 
 Deep learning process take a lot of time. Php is not the most efficient tool for do tasks of deep learning, but it is perhaps the most extensive programming language in the world (and i love it ^_^). The opportunity to train complex models on local machines without need to install almost anything and implement them on production servers (like share hosting services) without need to configure anything, gives a clear advantage to this programming model.

 One solution to improve the speed spend in the process of deep learning is using multi-processor threads (process parallelization)... and YES. PHP can do it!!!!
 
 With php, parallelization is possible, then i will have new code soon for the class with parallelization feature. This code will need to be executed on linux servers and CLI environtment, but the code for execute learned models will be remain standar for execute it on any type of server with PHP.
 
 You need to wait some time.... Why? My life is not only virtual and PHP ;D, but i promise to upload the code as fast as i can. There are many problems that can arise when working with multithreads (system messages between processes, shared memory between them, ...). All this must be controlled correctly by means of semaphores so that some processes do not interfere with others giving system errors ... and as if that were not enough, the temperature of the CPU must be controlled, since the deep learning process is a hard task for the processor. Have you put your processor at 100ºC doing deep learning? I do.... in case you are curious to know what happens, the server stops immediately  ^_^'
 
 
 @author Rafael Martin Soto
 
 @author {@link http://www.inatica.com/ Inatica}
 
 @since July 2021
 
 @version 1.0
 
 @license GNU General Public License v3.0
 
 
 A LOT OF THANKS TO:
 
 *  - https://github.com/infostreams/neural-network/blob/master/class_neuralnetwork.php
 *  - https://gist.github.com/ikarius6/26851fb7220837e8016fe0c425d34dd6
 *  - https://mattmazur.com/2015/03/17/a-step-by-step-backpropagation-example/
 *  - https://www.youtube.com/channel/UCy5znSnfMsDwaLlROnZ7Qbg
