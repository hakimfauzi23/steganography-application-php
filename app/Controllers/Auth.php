<?php 

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\AuthModel;

class Auth extends Controller
{
    public function register()
    {
        $secret_key = "6Lc4vwsaAAAAAKZffTvtTRwhY_dVR_e-FgzxfcYb";
        $verify =file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
        $response = json_decode($verify);
        if($response->success){ // Jika proses validasi captcha berhasil
            // echo "<h2>Captcha Valid</h2>";
            // echo "Yes, you're human (Anda adalah manusia)<hr>";
            $val = $this->validate(
                [
                'username' => 'required',
                'password' => 'required'
                ]
                );
                var_dump($val);
                if (!$val) {
                $pesanvalidasi = \Config\Services::validation();
                return redirect()->to('/register')->withInput()->with('validate',
                $pesanvalidasi);
                }

            $db = \Config\Database::connect();
            


            //PROSES ENKRIPSI PASSWORD KE DALAM DATABASE
            $pass=$this->request->getPost('password');
            $key = 5;
            for ($i = 0; $i < strlen($pass); $i++) {
            $kode[$i] = ord($pass[$i]); //rubah ASCII ke desimal
            $b[$i] = ($kode[$i] + $key) % 256; //proses enkripsi                
            $c[$i] = chr($b[$i]); //rubah desimal ke ASCII
            }

            $encrypt = '';
                for ($i = 0; $i < strlen($pass); $i++) {
                echo $c[$i];
                $encrypt = $encrypt . $c[$i];
            }

        
            $data = array(
                'username' => $this->request->getPost('username'),
                'password' => $encrypt,
                'role' => $this->request->getPost('role')
            );
            $model  = new UserModel();
            $model->insert($data);
            session()->setFlashdata('pesan', 'selamat anda berhasil registrasi');
            return redirect()->to('/');
        }else
        { // Jika captcha tidak valid
            echo "<h2>Captcha Tidak Valid</h2>";
            echo "Ohh sorry, you're not human (Anda bukan manusia)<hr>";
            echo "Silahkan klik kotak I'm not robot (reCaptcha) untuk verifikasi";
        }

    }
    public function login()
    {
        $model = new AuthModel();
        $table = 'users';
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

    
        $row = $model->get_data_login($username, $table); 

        //PROSES DEKRIPSI SAAT LOGIN
        $pass = $row->password;
        $key = 5;
        for($i=0;$i<strlen($pass);$i++)
        {
        $kode[$i]=ord($pass[$i]); // rubah ASII ke desimal
        $b[$i]=($kode[$i] - $key ) % 256; // proses dekripsi Caesar
        $c[$i]=chr($b[$i]); //rubah desimal ke ASCII
        }

        $decyrpt = '';
        for ($i=0;$i<strlen($pass);$i++){
        echo $c[$i];
        $decyrpt = $decyrpt.$c[$i];
        }

        if ($row == NULL || $password!=$decyrpt) {
            session()->setFlashdata('pesan', 'Username atau Password anda salah');
            return redirect()->to('/');
        }else{
            //PROSES DEKRIPSI DI SAAT LOGIN
            $pass = $row->password;
            $key = 5;
            for($i=0;$i<strlen($pass);$i++)
            {
            $kode[$i]=ord($pass[$i]); // rubah ASII ke desimal
            $b[$i]=($kode[$i] - $key ) % 256; // proses dekripsi Caesar
            $c[$i]=chr($b[$i]); //rubah desimal ke ASCII
            }

            $decyrpt = '';
            for ($i=0;$i<strlen($pass);$i++){
            echo $c[$i];
            $decyrpt = $decyrpt.$c[$i];
            }

            if ($decyrpt==$password) {
                $data = array(
                'log' => TRUE,
                'username' => $row->username,
                'role' => $row->role,
                );
                session()->set($data);
                session()->setFlashdata('pesan', 'Anda Berhasil login');
                return redirect()->to('/backend');
            }
        }
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        session()->setFlashdata('pesan', 'Berhasil logout');
        return redirect()->to('/');
    }
}
?>