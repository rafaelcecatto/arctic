<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissoesModel extends Model
{
 
    protected $table            = 'permissoes';
    protected $returnType       = 'object';
    protected $allowedFields    = []; // Usar pelo Seeder

    
}
