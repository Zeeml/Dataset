![build](https://travis-ci.org/Zeeml/Dataset.svg?branch=master)

# Dataset

A multi-purpose dataSet for Machine Learning algorithms training.

## Create a DataSet

to create a DataSet to use for Zeeml Machine Learning, you need to specify a source : either a csv file or an array

### Create a dataSet from a csv file

    $dataSet =  DataSetFactory::create('/path/to/csv', ['name', 'Gender'], ['Height]);

The keys set in the header (first row of the CSV file) are used as keys for the dataSet

### Create a dataSet from an Array

    $dataSet =  DataSetFactory::create(
        [
            ['name' => 'Zac',    'gender' => 'Male',    'height' => 180],
            ['name' => 'Emily',  'gender' => 'Female',  'height' => 177],
            ['name' => 'Edward', 'gender' => 'Male',    'height' => 175],
            ['name' => 'Mark',   'gender' => 'Male',    'height' => 183],
            ['name' => 'Lesly',  'gender' => 'Female',  'height' => 170],
        ]
    );

Any other array format will throw an exception

# Specify inputs and outputs

<b>The prepare method must be called prior to any other call or an exception will be thrown.</b>

    $mapper = new Mapper(['name', 'gendre'], ['height']);
    $dataSet->prepare($mapper);
    

where <b>['name', 'gendre']</b> are the indexes to use as inputs 
and <b>['height']</b> are the indexes to use as outputs.

There is no limit to the number of inputs and outputs to pick from the entry 

<b>If a key does not exist it will throw an exception.</b>

# Manipulating the dataSet

In order to manipulate and change the values of the dataSet (cleaning, renaming ...) you
can apply a "Policy".

A Policy is called when creating the Mapper.
Each column can define multiple Policies :

    $dataSet = DataSetFactory::create(
          [
              [180, 'Male'],
              [177, 'Female'],
              [170, ''],
              [183, 'Male'],
          ]
    );
    $mapper = new Mapper(
        [
            0 => [Policy::replaceWithAvg(), Policy::rename('height')], 
        ], 
        [
            1 => [Policy::skip()]
        ]
    );
    $dataSet->prepare($mapper);
 

###Supported policies :

-   <b>Policy::skip()</b> : If the value at the corresponding index is empty (NULL, false, '') the whole row will be skipped

    Example :
        
        $data = [
            [1, 2, 3],
            [4, null, 5],
            [6, 7, null],
            [null, 8, 9],
        ];
        
        $dataSet =  DataSetFactory::create($data);
        $mapper = new Mapper([0, 1 => Policy::skip()], [2 => Policy::skip()]);
        $dataSet->prepare($mapper);
        
        will use the following Inputs/Outputs :
        
        Inputs:                                             
        [                                                 
            [1, 2],                                        
            [null, 8], //No policy applied on 0           
        ]                                                
        
        Outputs:    
        [
            [3],
            [9],
        ]   
                
-   <b>Policy::replaceWith(<value>)</b> : If the value at the corresponding index is empty (NULL, false, '') it will be replaced with the given value

    Example :
        
        $data = [
            [1, 2, 3],
            [4, null, 5],
            [6, 7, null],
            [null, 8, 9],
        ];
        
        $dataSet =  DataSetFactory::create($data);
        $mapper = new Mapper([0, 1 => Policy::replaceWith('Unknown')], [2 => Policy::replaceWith(-1)]);
        $dataSet->prepare($mapper);
        
        will use the following Inputs/Outputs :
        
        Inputs:                                           
        [                                                  
            [1, 2],                                          
            [4, 'Unknown'],                                  
            [6, 7],                                          
            [null, 8], //No policy applied on 0              
        ]                                                  
        
        Outputs:                                          
        [
            [3],
            [5], 
            [-1],
            [9]
        ] 
                
-   <b>Policy::replaceWithAvg()</b> : The empty values will be replaced with the average value of that column calculated from the original DataSet.

    Example :
        
        $data = [
            [1, 2, 3],
            [4, null, 5],
            [6, 7, null],
            [null, 8, 9],
        ];
        
        $dataSet =  DataSetFactory::create($data);
        $mapper = new Mapper([0 => Policy::replaceWithAvg(), 1 => Policy::skip()], [2 => Policy::replaceWithAvg()]);
        $dataSet->prepare($mapper);
        
        will use the following Inputs/Outputs :
        
        Inputs:                                                              
        [                                                                    
            [1, 2],                                                            
            [6, 7],                                                                                    
            [2.75, 8], // Avg(0) = 1 + 4 + 6 + 0 = 11 / 4 = 2.75               
        ]                                                                    
        
        Outputs:
        [
            [3],
            [-1],
            [9],
        ]   
                                                                        ]
-   <b>Policy::replaceWithMostCommon()</b> : The empty values will be replaced with the most common value (the value that occurs the most)
    If multiple values have the same frequency, one is taken randomly.
    
    Example :
        
        $data = [
            [1, 2, 3],
            [1, null, 5],
            [6, 7, null],
            [null, 8, 9],
        ];
        
        $dataSet =  DataSetFactory::create($data);
        $mapper = new Mapper([0=> Policy::replaceWithMostCommon(), 1 => Policy::skip()], [2]);
        $dataSet->prepare($mapper);
        
        will use the following Inputs/Outputs :
        
        Inputs:                                 
        [                                        
            [1, 2],                               
            [6, 7],                               
            [1, 8],                                
        ]                                      
        
        Outputs:
        [
            [3],
            [null],
            [9],
        ]

-   <b>Policy::custom(<callable>)</b> : create your own Policy

    the callable function is only called when the value is empty. The callable must :

    - Take in a first parameter by reference which corresponds to the value of the column upon each iteration
    - Take in a second parameter which corresponds to the line
    - Return true to keep the line, false to skip it
    
    Example :
        
        $data = [
            [180, 'Male'],
            [177, 'Female'],
            [170, ''],
            [183, 'Male'],
        ];
        
        $dataSet =  DataSetFactory::create($data);
        
        $genderCleaner = function(&$value, $line) {
            if ($line[0] > 175) {
                $value = 'Male' ;
            } else {
                $value = 'Female';
            }
            
            return true;
        }
        
        $mapper = new Mapper([0], [1 => Policy::custom($genderCleaner)]);
        $dataSet->prepare($mapper);
        
        will use the following Inputs/Outputs :
        
        Inputs:                                 
        [                                        
            [180],                               
            [177],                               
            [170],                                
            [183],                                
        ]                                      
        
        Outputs:
        [
            ['Male'],                               
            ['Female'],                               
            ['Female'],                                
            ['Male'],
        ]
                
                                
## Renaming keys of dataSet

You can rename the dataSet keys :

    $data = [
        ['Zac',    'Male',    180],
        ['Emily',  'Female',  177],
        ['Edward', 'Male',    175],
        ['Mark',   'Male',    183],
        ['Lesly',  'Female',  170],
    ];    
        
    $dataSet =  DataSetFactory::create($data);
        
    $mapper = new Mapper([0, 1], [2]);
    $dataSet->prepare($mapper);
        
    $dataSet->rename([0 => 'Name', 1 => 'Gender', 2 => 'Height']);
    
    and the inputs/outputs matrices used are :
    
    Inputs :
    [
        ['Name' => 'Zac',    'Gender' => 'Male'],
        ['Name' => 'Emily',  'Gender' => 'Female'],
        ['Name' => 'Edward', 'Gender' => 'Male'],
        ['Name' => 'Mark',   'Gender' => 'Male'],
        ['Name' => 'Lesly',  'Gender' => 'Female'],
    ]
    
    Outputs :
    [
        ['Height' => 180],
        ['Height' => 177],
        ['Height' => 175],
        ['Height' => 183],
        ['Height' => 170],
    ]