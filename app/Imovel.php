<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $fillable = [
        "descricao","logradouroEndereco", "bairroEndereco", "numeroEndereco", "cepEndereco",
        "cidadeEndereco", "cepEndereco", "preco", "qtdQuartos", "tipo", "finalidade"
    ];
    protected $table = "imoveis";
}
