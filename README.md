![build](https://travis-ci.org/Zeeml/Dataset.svg?branch=master)

# Dataset
A multi-purpose dataset for Machine Learning algorithms training.

## Create a DataSet

### Create a dataSet from a csv file

    $dataSet =  DataSetFactory::create('/path/to/csv');


### Create a dataSet from an Array

    $dataSet =  DataSetFactory::create(
        [
            [1, 2, 3, ...],
            [1, 2, 3, ...],
            [1, 2, 3, ...],
            [1, 2, 3, ...],
        ]
    );

Any other array format will throw an exception

## Specify inputs and outputs

    $mapper = new Mapper([0, 1], [2, 3]);
    $dataSet->prepare($mapper);

where [0, 1] are the index keys of the input in the csv file or the array (keys start at 0) 
and [2] are the index keys of the output.and

There is no limit to the number of inputs and outputs to pick from the entry (at least one though)
If a key does not exist it will throw an exception.
 
# Important

The <b>prepare</b> method is mandatory, if not called the dataSet can not be used.    
 