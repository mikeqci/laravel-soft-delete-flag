# laravel-soft-delete-flag
A Laravel package that can use Boolean column ​​for soft deletes.

## Installation
You can install this package into your Laravel application using composer.

The recommended way to install composer package is

```
composer require tsyama/laravel-soft-delete-flag
```

## Usage

### 1. Use SoftDeleteFlagTrait in your model class

```
<?php
namespace App;

use Tsyama\LaravelSoftDeleteFlag\Traits\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use SoftDeleteFlagTrait;
...
```

### 2. Define a constant and set the column name of the deletion flag

```
class User extends Model
{
    use SoftDeleteFlagTrait;
    
    const DELETED_AT = 'delete_flag';
...
```