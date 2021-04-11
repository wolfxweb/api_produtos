<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descricao',
        'status',
        'user_id',
    ];


}
