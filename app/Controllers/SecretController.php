<?php
namespace App\Controllers;

use App\Models\SecretModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class SecretController extends ResourceController
{
    use ResponseTrait;
    protected $db;

    public function create()
    {
        $this->db = \Config\Database::connect();
        $secretModel = new SecretModel();

        $post = $this->request->getPost();

        if (empty($post['secret'])) {
            return $this->failValidtionErrors("This text will be saved as a secret");
        }
        if(empty($post['expireAfterViews']) && $post['expireAfterViews'] < 0) {
            return $this->failValidationErrors("The secret won't be available after the given number of views. It must be greater than 0.");
        }
        if(empty($post['expireAfter']) && $post['expireAfter'] < 0) {
            return $this->failValidationErrors("The secret won't be available after the given time. The value is provided in minutes. 0 means never expires");
        }

        $hash = bin2hex(random_bytes(16));
        $expiresAt = ($post['expireAfter'] > 0) ? date('Y-m-d H:i:s', strtotime("+".$post['expireAfter']." minutes")) : null;

        // Tranzakció indítása
        $this->db->transStart();

        $data = [
            'hash' => $hash,
            'bodytext' => $post['secret'],
            'expires_at' => $expiresAt,
            'remaining_views' => ((!empty($secret['expireAfterViews']))?$post['expireAfterViews']:null),
            "created_at" => date("Y-m-d H:i:s")
        ];

        $secretModel->insert($data);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return $this->respondCreated(["description" => "Invalid input"]);
        } else {
            $this->db->transCommit();
            return $this->respondCreated(["description" => "successful operation",'hash' => $hash]);
        }
    }

    public function show($hash = null)
    {
        if(!empty($hash)) {
            $secretModel = new SecretModel();
            $secret = $secretModel->where('hash', $hash)->first();
            if (!$secret) {
                return $this->failNotFound('Secret not found');
            }

            // Check if the secret is expired or has no remaining views
            if ((!empty($secret['expires_at']) && strtotime($secret['expires_at']) < time()) || (!empty($secret['remaining_views']) && $secret['remaining_views'] <= 0)) {
                return $this->failNotFound('Secret not found');
            }

            if(!is_null($secret['remaining_views'])) {
                // Decrease the remaining views
                $secret['remaining_views']--;
                $secretModel->update($secret['id'], ['remaining_views' => $secret['remaining_views']]);
            }

            return $this->respond($secret);
        } else {
            return $this->failNotFound("Unique hash to identify the secret");
        }
    }
}