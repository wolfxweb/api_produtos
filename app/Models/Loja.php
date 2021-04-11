<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'telefone',
        'celular',
        'descricao',
        'rua',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'complemento',
        'cnpj',
        'inscEstadual',
        'inscMunicipal',
        'email',
        'facebook',
        'instagram',
        'imgFundo',
        'corTitulo',
        'corFundo',
        'corFonte',
        'pixelFacebook',
        'pixelGoogle',
        'status',
        'slug'
    ];
}
