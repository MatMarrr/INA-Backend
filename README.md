# INA-Backend
## Live version: https://ina-backend-production.up.railway.app/api
## Used technologies
[<img align="left" alt="Laravel" width="36px" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-plain.svg" style="padding-right:10px;"/>][laravel]
[<img align="left" alt="PHP" width="36px" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" style="padding-right:10px;"/>][php]

<br>

## Project Description
This is the backend part of a project made for the university subject INA.

[laravel]: https://en.wikipedia.org/wiki/Laravel
[php]: https://en.wikipedia.org/wiki/PHP

# API Documentation

## Endpoints

### 1. **`GET /genetic-algorithm/random-float/generate`**
   - **Description**: Generates a random floating-point number.
   - **Parameters**: 
     - `a` (int): Lower boundary of the range.
     - `b` (int): Upper boundary of the range.
     - `decimalPlaces` (int): Number of decimal places.
   - **Response**: A random floating-point number.

### 2. **`GET /genetic-algorithm/decimal-places/force`**
   - **Description**: Forces a specified number of decimal places.
   - **Parameters**: 
     - `number`: Number to be processed.
     - `decimalPlaces`: Number of decimal places to force.
   - **Response**: Number with a specified number of decimal places.

### 3. **`GET /genetic-algorithm/decimal-places/count`**
   - **Description**: Counts the decimal places.
   - **Parameters**: 
     - `d` (float): Number whose decimal places are to be counted.
   - **Response**: Number of decimal places.

### 4. **`GET /genetic-algorithm/l/count`**
   - **Description**: Calculates the value of "L".
   - **Parameters**: 
     - `a` (int), `b` (int), `d` (float): Parameters for calculating the value of "L".
   - **Response**: Value of "L".

### 5. **`GET /genetic-algorithm/function-value/count`**
   - **Description**: Calculates the function value.
   - **Parameters**: 
     - `realNumber` (float): Number for which the function value is calculated.
   - **Response**: Function value.

### 6. **`GET /genetic-algorithm/convert/real-to-int`**
   - **Description**: Converts a real number to an integer representation.
   - **Parameters**: 
     - `realNumber` (float), `a` (int), `b` (int), `l` (int): Parameters for the conversion.
   - **Response**: Integer representation of the real number.

### 7. **`GET /genetic-algorithm/convert/int-to-bin`**
   - **Description**: Converts an integer to binary representation.
   - **Parameters**: 
     - `intNumber` (int), `l` (int): Parameters for the conversion.
   - **Response**: Binary representation of the integer.

### 8. **`GET /genetic-algorithm/convert/bin-to-int`**
   - **Description**: Converts a binary number to an integer.
   - **Parameters**: 
     - `binNumber` (string): Binary number for conversion.
   - **Response**: Integer representation of the binary number.

### 9. **`GET /genetic-algorithm/convert/int-to-real`**
   - **Description**: Converts an integer to a real number representation.
   - **Parameters**: 
     - `intNumber` (int), `a` (int), `b` (int), `l` (int), `decimalPlaces` (int): Parameters for the conversion.
   - **Response**: Real number representation of the integer.

### 10. **`GET /genetic-algorithm/all-conversions-table`**
   - **Description**: Generates a table of all conversions.
   - **Parameters**: 
     - `a` (int)
     - `b` (int)
     - `d` (float)
     - `n` (int): Parameters for generating the table.
   - **Response**: Conversion table containing various representations and values of the number.

## Security

The API is secured. To make requests, the appropriate access key must be passed in the request header.

