<?php 
require_once __DIR__ . '/BaseModel.php';

class Users  extends BaseModel{
    protected $table = 'users';
    protected $primaryKey = 'id';
 

  
    public function loginUser($username, $password) {
        $user = $this->get(['*'], ['mail' => $username]);
      
        if (!$user) {
            return false;
        }
    
        if (!password_verify($password, $user['password'])) {
            return false;
        }
     
        return $user;
        
    }
}


?>