# rn Neural Network in pure PHP - ML Machine Learning - AI Artificial Intelligence
RED NEURONAL

 ## WHAT DO THIS LIBRARY IN PURE PHP OF ARTIFICIAL INTELLIGENCE?:
It is a library for machine learning (deep learning) to learn patterns with your datasets. You can create a neural network structure of the layers you want with the number of neurons you want. Each layer can have a different activation function. Ya can train your data in the network with backpropagation function integrated. The result can be exported in JSON format to take the trained network to any other server. 100% written in PHP (pure PHP). Easy to use on any type of server. You can use it freely and working without install any package more. With the default configuration of your server, this code, with your trained data in local server, can be used in your shared hosting server, for example, as simple as that :smiley:

No more easy use is possible. You only need to include a master file .php as... **_require_once( 'rn.class.php' );_** and with very little code begin to train your data. You have an example.php file for test it.

 # SCREENSHOT:
![Screenshot of the neural network written in Pure PHP](https://github.com/vivesweb/rn-Neural-Network-in-pure-PHP-ML-AI/blob/main/2Captura-de-pantalla-2021-07-15-a-les-14.00.49.jpg)
* This screenshot belongs to the result of the example file *example.php*, executed on a raspberry pi (B + 512MB	700 MHz ARM11). The time spend for the script is less than 2 seconds. The output is: 1) Default network values, 2) Process of learning and backpropagation, 3) Final values of the network learned, 4) Export of the values of the configuration of our neural network in JSON format.

 
 # REQUERIMENTS:
 
 - A minimum (minimum, minimum, minimum requeriments is needed). Tested on:
 		
    - Simple Raspberry pi (B +	512MB	700 MHz ARM11) with Raspbian Lite PHP7.3 (i love this gadgets)  :heart_eyes:
 		
    - VirtualBox Ubuntu Server 20.04.2 LTS (Focal Fossa) with PHP7.4.3 
 - Needed 1 hidden layer at least
 
 
  # FILES:
 There are 3 basic files:
 
 *rn.class.php* -> **Neural network class**. This file is the main file that you need to include in your code. This file includes inside rn_layer.class.php
 
 *rn_layer.class.php* -> **Layer class**. This file includes inside rn_node.class.php
 
 *rn_node.class.php* -> **Node/Neuron** class
 
 
 # INSTALLATION:
 A lot of easy :smiley:. It is written in PURE PHP. Only need to include the files. Tested on basic PHP installation
 
         require_once( 'rn.class.php' );
 
 # BASIC USAGE:
 
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
 
 
# RESUME OF METHODS:

- **CREATE NEURAL NETWORK:**
 
*$rn = new rn( [ARRAY OF INT] );*

Example:

        $rn = new rn( [3, 1, 2] );  // 3x1x2 = 3 layers. 3 input neurons, hidden layer with 1 neuron, 2 output neurons



- **PRINT ALL TRAIN INPUT DATA, NEURAL NETWORK OUTPUT DATA & TRAIN DESIRED DATA:**

*$rn->EchoOutputValues( $arrTrainInputItems, $arrTrainOutputItems );*

Example:

        $rn->EchoOutputValues( $arrTrainInputItems[$i], $arrTrainOutputItems[$i] );
	
	
- **PRINT ALL TRAIN INPUT DATA & NEURAL NETWORK OUTPUT:**

*$rn->EchoOutputValues( $arrTrainInputItems );*

This method is the same as previous method, with only 1 parameter. The parameter of DesiredData is optional in this method.

Example:

        $rn->EchoOutputValues( $arrTrainInputItems[$i] );



- **PROCESS OF LEARN:**

*$rn->Learn([ARRAY OF FLOAT], [ARRAY OF FLOAT]);*

Example:

        $rn->Learn($arrTrainInputItems, $arrTrainOutputItems);



- **SET THE NUMBER OF EPOCHS:**

*$rn->fSet_num_epochs( INT );*

Example:

        $rn->fSet_num_epochs( 10000 ); // 10000 Epochs



- **SET THE ACTIVATION FUNCTION FOR ALL OF LAYERS:**

*$rn->fSet_activation_function( STRING );*

Example:

        $rn->fSet_activation_function( 'sigm' ); // ['sigm' | 'tanh'] Default: 'sigm'



- **SET THE ACTIVATION FUNCTION FOR ONE LAYER:**

*$layer->fSet_activation_function( STRING );*

Example:

        $rn->layer[1]->fSet_activation_function( 'sigm' ); // ['sigm' | 'tanh'] Default: 'sigm'


- **SET LEARNING RATE:**

*$rn->set_alpha( FLOAT );*

Example:

            $rn->set_alpha( .5 );



- **GET THE OUPUT VALUE OF NEURAL NETWORK OF ONE OUTPUT NODE:**

- Output node: If we have 2 neurons, we can get the output value for Neuron[0] | Neuron[1]

*$rn->run( INT OUTPUT NODE ID, ARRAY OF FLOAT INPUT VALUES );*

Example:

            $rn->run( $id_output_node, $arrInputValues );


- **GET THE MEANSQUARE ERROR OF THE MODEL:**

*$rn->MeanSquareError(ARRAY OF INPUT VALUES, ARRAY OF DESIRED VALUES);*

Example:

            $rn->MeanSquareError($arrTrainInputItems, $arrTrainOutputItems);


- **EXPORT THE TRAINED MODEL CONFIGURATION TO A STANDARD JSON STRING:**

            echo $rn->exportData2Json();


- **IMPORT A TRAINED DATA STRING IN JSON FORMAT TO OUR NEURAL NETWORK CLASS:**

*$rn->importJson2Data( STRING JSON );*

Example:

    $JsonDataStr = '{"InaticaNeuralNetwork":{"NumInputNeurons":2,"NumOutputNeurons":2,"CreationDate":"2021-07-17 09:36:34","NumTotalLayers":3,"NumHiddenLayers":1,"NumEpochs":1000,"MeanSquareError":0.00010787600008628726,"Layers":[{"Layer":{"num_nodes":2,"imFirst":true,"imLast":false,"activation_function":"sigm","Nodes":[{"Node":{"arr_weights_to_next_layer":[3.131511783516275],"bias":0.5}},{"Node":{"arr_weights_to_next_layer":[2.0871213721523483],"bias":0.5}}]}},{"Layer":{"num_nodes":1,"imFirst":false,"imLast":false,"activation_function":"sigm","Nodes":[{"Node":{"arr_weights_to_next_layer":[3.3833022095458305,3.221540608053369],"bias":-2.4182794115880726}}]}},{"Layer":{"num_nodes":2,"imFirst":false,"imLast":true,"activation_function":"sigm","Nodes":[{"Node":{"arr_weights_to_next_layer":[0.5],"bias":-2.3007544811282843}},{"Node":{"arr_weights_to_next_layer":[0.5],"bias":-1.7218937670832613}}]}}]}}';
      
    $JsonData = json_decode( $JsonDataStr );
      
    $rn->importJson2Data($JsonData->InaticaNeuralNetwork);
 
 - **INFORM ABOUT THE LEARNING PROCESS**

We can to do echoes periodically of the actual neural network process while the Machine is learning, with 2 simple variables of the network class:

*$rn->InformEachXBlock*

*$rn->InformEachXEpoch*

If the process of learning is really fast, we can use InformEachXEpoch, for example, for do one echo of the values every 100 Epochs:

            $rn->InformEachXEpoch = 100;

If the process of learning is tooooo slooooow, we can use InformEachXBlock, for example, for do one echo every block of 10 samples learned:

            $rn->InformEachXBlock = 10;
 
 
 
 # FUTURE PLANS
 
 Machine learning is magic. Artificial intelligence is an exciting world, but deep learning process at Backpropagation algorithm take a lot of time. PHP is not the most efficient programming language for do tasks of deep learning, but it is perhaps the most extensive programming language in the world (and i love it :heart_eyes:). The opportunity to train complex models on local machines without need to install almost anything and later implement them on STANDARD production servers (like shared hosting services) without need to configure anything, gives a clear advantage to this programming model and opens up endless possibilities.
 
 
 **1) SOME BUG. NOT 100% GOOD RESULTS IN MEANSQUARE ERROR:**
 
 The system of obtaining the quadratic error of the network will surely be changed, since the current system looks for the error on the first 100 data of the model to be learned (so as not to perform the calculation on the entire entire database). An alternative solution will be sought to improve the accuracy of the error.
 
 
 
 **2) ADD SOME FEATURES**
 
 I have in mind to implement different characteristics to the class, such as **MOMENTUM**, other activation functions as **RELU** or **SOFTMAX**, .... among others.
 
 It would be very interesting to add specific functions to speed up programming and its use in **convolutional neural networks**.
 
 Another interesting feature will be to add to the class the option to save or read the current configuration of the neural network learned data from a file. Currently, it is possible to import and export the configuration of our network using the JSON data format as input or output, but reading and writing these same data into files would speed up many processes in an automated way.
 
 As an extra utility, we also want to prepare the system so that it can obtain the train, desired and evaluation data directly from .CSV files.



 ### ACCELERATE LEARNING SPEED
 
 
 **3) MULTITHREAD & MULTI-PROCESSORS**
 
 One solution to improve the speed spend in the process of deep learning is using **multi-processor threads (process parallelization)**... and **YES!!!. PHP can do it natively!!!!** :blush:
 
 With PHP, parallelization is possible. I will have new code soon for the class with parallelization feature. This future code obligatorily **WILL NEED** to be executed on **GNU/LINUX** servers and **CLI** environtment, but the code for execute learned models can be executed on any type of server with php, as a basic web server, for example (CLI, cgi, windows, linux, web server on shared hosting, ....).
 
 You will need to wait some time.... Why? My life is not only virtual and PHP :wink:, but i promise to upload the code as fast as i can. There are many problems that can arise when working with multithreads (system messages between processes, shared memory between them, ...). All this must be controlled correctly by means of semaphores so that some processes do not interfere with others giving system errors ... and as if that were not enough, the temperature of the CPU must be controlled, since the deep learning process is a hard task for the CPU. Have you put your CPU up to **100ºC**:thermometer: doing deep learning???? I do.... in case you are curious to know what happens, the server stops immediately  :sweat_smile: :man_facepalming:
 
  ### THE GREAT PROJECT
  
 **4) DEEP LEARNING SERVER FARM WITH PHP**

 The last step will be to create a service of Deep Learning Server Farm... but we need to wait. Everything will come in due time. Much work remains to be done before :smiley:
 
 
 **Of course. You can use it freely :vulcan_salute::alien:**
 
 By Rafa.
 
 
 @author Rafael Martin Soto
 
 @author {@link http://www.inatica.com/ Inatica}
 
 @since July 2021
 
 @version 1.0
 
 @license GNU General Public License v3.0
 
 
 A LOT OF THANKS TO:
 
 *   https://github.com/infostreams/neural-network/blob/master/class_neuralnetwork.php
 *   https://gist.github.com/ikarius6/26851fb7220837e8016fe0c425d34dd6
 *   https://mattmazur.com/2015/03/17/a-step-by-step-backpropagation-example/
 *   https://www.youtube.com/channel/UCy5znSnfMsDwaLlROnZ7Qbg
