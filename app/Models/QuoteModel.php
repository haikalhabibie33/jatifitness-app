<?php 

namespace App\Models;

use CodeIgniter\Model;

class QuoteModel extends Model {
    protected $table = 'quote';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['author', 'role','text'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}