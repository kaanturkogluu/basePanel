<?php

require_once __DIR__ . '/BaseModel.php';

class LoginModel extends BaseModel
{


    protected $table = 'users';

    public function login($mail, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE mail = :mail ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'mail' => $mail
            ]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user) {
                if (password_verify($password, $user->password)) {
                    return $user;
                }
            }
            return false;
        } catch (PDOException $e) {
            new Exception("Sunucu Hatası , Daha Sonra Tekrar Deneyiniz");
        }
    }
}


?>