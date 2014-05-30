WScore.Validation
=================

A simple validation component with many multi-byte support.

Easy to use, enjoyable to write code,
lots of default error messages,
lots of pre-defined validation types, and
works great with multi-byte characters (Japanese that is).

Others are:

*   preset order of rules to apply. essential to handle Japanese characters.
*   multiple values combined to a single value (ex: bd_y, bd_m, bd_d to bd).
*   easy to code logic.


Simple Usage That Should Be
---------------------------

This package **almost** works like this.

```php
use \WScore\Validation\Factory;
use \WScore\Validation\Rules;

Factory::setLocale('ja');          // use Japanese rules and messages.
$input = Factory::getValidator();  // get validator.
$input->setSource( $_POST );       // validating post input.

// check if name or nickname is set
if( !$input->is( 'name', Rules::text() ) ) {
    if( !$input->is( 'nickname', Rules::text() ) ) {
        $input->isError( 'name', 'requires name or nickname' );
    }
}

// check mail with confirmation
$input->is( 'mail', Rules::mail()->sameAs( 'mail2' )->required() );

// check value of input, and...
$status = $input->is( 'status', Rules::int()->in( '1', '2', '3' )->required() );
if( $status == '1' ) { // add some message?!
    $input->setValue( 'notice', 'how do you like it?' );
}

if( $input->isValid() ) {
    $goodData = $input->get();
} else {
    $badData = $input->get();
    $errors  = $input->errors();
}
```

Validating a value works like,

```php
$name  = $input->verify( 'WScore', Rules::text()->string('lower') ); // returns 'wscore'
if( false === $input->verify( 'Bad', Rules::int() ) { // returns false
    echo $input->lastError(); // echo 'not an integer';
}
```


OLD OLD OLD
===========

Simple Usage
------------

examples for Validate object, which filters and validates a single value. 

```php
$validate = \WScore\Validation\Validate::factory();
$rule     = new \WScore\Validation\Rules();

// basic validation by type

$value = 'a text';
echo $validate->is( 'a text', $rule('text') ); // 'a text'
echo $validate->is( 'a text', $rule('int') ); // false
echo $validate->is( 'a text', $rule('text')->string('upper') ); // 'A TEXT'

// validation and getting errors

$value = '';
echo $validate->is( $value, $rule('text') ); // ''
echo $validate->is( $value, $rule('text')->required() ); // false
echo $validate->isValid(); // false
echo $validate->getMessage(); // 'required field'
```


More Example
------------

examples for Validation object, which filters and validates on array inputs,
such as $_POST. 


```php
$validation = Validation::factory();
$rule     = new \WScore\Validation\Rules();

$data = array(
    'name' => 'my name',
    'mail' => 'mail@example.com',
    'mail2'=> 'bad@example.com',
    'date_y' => '2013', 'date_m' => '11', 'date_d' => '15',
);
$validation->source( $_POST );
$validation->push( 'name', $rule('text') );
$validation->push( 'mail', $rule('mail')->sameAs('email2') ); // checks against email2 input. 
$validation->push( 'date', $rule('date') );

$values = $validation->pop();     // returns all values including invalid ones.
$goods  = $validation->popSafe(); // returns only the valid values.
$errors = $validation->popError();// get error messages.
```

Why Yet-Another Validation Component?
-------------------------------------

There are several reasons why I continued developing this Validation components. 

###Validating Array Input

validation on array is easy. so is the error message. 

```php
$inputs = array( 'list' => [ '1', '2', 'bad', '4' ] );
$validation->source( $inputs );

if( !$validation->push( 'list', 'int' ) ) {
    $values = $validation->pop();
    $goods  = $validation->popSafe();
    $errors = $validation->popError();
}
/*
 * $values = array( 'list' => [ '1', '2', 'bad', '4' ] );
 * $goods  = array( 'list' => [ '1', '2', '4' ] );
 * $errors = array( 'list' => [ 3 => 'invalid input' ] );
 */
```

###Order of filter

some filter must be applied in certain order... 

```php
$validate->is( 'ABC', $rule('text')->pattern( '[a-c]*' )->string( 'lower' );
## should lower the string first, then check for pattern...
```

###Some predefined error messages

some filter have own error messages, 
that are: required, encoding, sameAs, and sameEmpty. 
They are defined in Message class, and to be i18n ready in some future.

```php
$validate->is( '', $rule('text')->required() );
echo $validate->getMessage(); // 'required input'
```

###Multiple inputs

to treat separate input fields as one input. 

```php
$input = array( 'bd_y' => '2001', 'bd_m' => '09', 'bd_d' => '25' );
echo $validation->push( 'bd', $rule('date') ); // 2001-09-25
```

###SameWith to compare values

for password or email validation with two input fields 
to compare each other. 

```php
$input = array( 'text1' => '123ABC', 'text2' => '123abc' );
echo $validation->push( 'bd', $rule('text', 'string:lower | sameWith:text2' ) ); // 123abc
```

Predefined Types
----------------

todo: to-be-written

*   text
*   mail
*   number
*   integer
*   float
*   date
*   dateYM
*   etc.

Predefined Filters
------------------

todo: to-be-write

*   multiple
*   noNull
*   encoding
*   mbConvert
*   trim
*   etc.

Objects
-------

*   Validate object:
    filters and validates a single value.

*   Validation object:
    filters and validates against an array of inputs, such as $_POST.
    Validation has multiple filter and sameAs validator which are not present in Validate.

*   Rules object:
    has predefined filters and validators to apply for several types of input.

other objects:

*   Message object:
    handles messages for errors. 

*   Filter object:
    defines filters and validators as methods. 

*   ValueTO object:
    value-transfer-object used to carry value and associated error information, 
    such as error methods name and parameter, and messages. 

