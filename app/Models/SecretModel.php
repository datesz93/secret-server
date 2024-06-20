<?php
namespace App\Models;

use CodeIgniter\Model;

class SecretModel extends Model
{
    protected $table = 'secrets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'hash', 'bodytext', 'created_at', 'expires_at', 'remaining_views'];
    protected $useTimestamps = false;
}