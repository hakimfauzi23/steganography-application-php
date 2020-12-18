<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\StegoModel;
class Stego extends BaseController
{

    public function __construct() {
 
        // Mendeklarasikan class ProductModel menggunakan $this->product
        $this->stego = new StegoModel();
        $session = session();
        /* Catatan:
        Apa yang ada di dalam function construct ini nantinya bisa digunakan
        pada function di dalam class Product 
        */
    }
    public function index()
    {
        $session = session();
        $data['stego'] = $this->stego->getProduct();
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar');
        echo view('stego/index',$data);
        echo view('layouts/footer');
        
    }

    public function create()
    {
        $data['stego'] = $this->stego->getProduct();
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar');
        echo view('stego/create',$data);
        echo view('layouts/footer');
    }

    public function encyrpt()
    {

        // Mengambil value dari form dengan method POST
        
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('login'));
        }
        
        $validated = $this->validate([
            'file_upload' => 'uploaded[file_upload]|mime_in[file_upload,image/jpg,image/jpeg,image/gif,image/png]|max_size[file_upload,4096]'
            ]);
            
            if ($validated == FALSE) {
                
                // Kembali ke function index supaya membawa data uploads dan validasi
                return $this->index();
                
            } else {
                
                $pesan = $this->request->getPost('pesan');
                $cipher = md5($pesan);
                $avatar = $this->request->getFile('file_upload');
                // $avatar->move(ROOTPATH . 'public/uploads');

                
                
                
                
                //PROSES ENKRIPSI STEGONOGRAPHY
                function toBin($str){
                    $str = (string)$str;
                    $l = strlen($str);
                $result = '';
                while($l--){
                    $result = str_pad(decbin(ord($str[$l])),8,"0",STR_PAD_LEFT).$result;
                }
                return $result;
              }
              
              //Convert binary to string
              function toString($str) {
                  $text_array = explode("\r\n", chunk_split($str, 8));
                  $newstring = '';
                  for ($n = 0; $n < count($text_array) - 1; $n++) {
                      $newstring .= chr(base_convert($text_array[$n], 2, 10));
                    }
                    return $newstring;
                }
                
                $msg = $pesan; //To encrypt
                $src = $avatar; //Start image
                
                $msg .='|'; //EOF sign, decided to use the pipe symbol to show our decrypter the end of the message
                $msgBin = toBin($msg); //Convert our message to binary
                $msgLength = strlen($msgBin); //Get message length
                $img = imagecreatefromjpeg($src); //returns an image identifier
                list($width, $height, $type, $attr) = getimagesize($src); //get image size
                
                if($msgLength>($width*$height)){ //The image has more bits than there are pixels in our image
                    echo('Message too long. This is not supported as of now.');
                    die();
                }
                
                $pixelX=0; //Coordinates of our pixel that we want to edit
                $pixelY=0; //^
                
                for($x=0;$x<$msgLength;$x++){ //Encrypt message bit by bit (literally)
                    
            if($pixelX === $width+1){ //If this is true, we've reached the end of the row of pixels, start on next row
                $pixelY++;
                $pixelX=0;
            }
            
            if($pixelY===$height && $pixelX===$width){ //Check if we reached the end of our file
                echo('Max Reached');
                die();
            }
            
            $rgb = imagecolorat($img,$pixelX,$pixelY); //Color of the pixel at the x and y positions
            $r = ($rgb >>16) & 0xFF; //returns red value for example int(119)
            $g = ($rgb >>8) & 0xFF; //^^ but green
            $b = $rgb & 0xFF;//^^ but blue
            
            $newR = $r; //we dont change the red or green color, only the lsb of blue
            $newG = $g; //^
            $newB = toBin($b); //Convert our blue to binary
            $newB[strlen($newB)-1] = $msgBin[$x]; //Change least significant bit with the bit from out message
            $newB = toString($newB); //Convert our blue back to an integer value (even though its called tostring its actually toHex)
            
            $new_color = imagecolorallocate($img,$newR,$newG,$newB); //swap pixel with new pixel that has its blue lsb changed (looks the same)
            imagesetpixel($img,$pixelX,$pixelY,$new_color); //Set the color at the x and y positions
            $pixelX++; //next pixel (horizontally)
            
        }
        $name = $cipher.'.png'; //Random digit for our filename
        imagepng($img, '../public/uploads/'.$cipher . '.png'); //Create image
        // imagepng($img,'/public/uploads/'.$avatar->getName()); //Create image
        
        imagedestroy($img); //get rid of it
        
        $data = [
            'pesan' =>$pesan,
            'gambar' => $name,
            'cipher' => $cipher
        ];
        
        $this->stego->insert_gambar($data);
        
        return redirect()->to(base_url('stego'))->with('success', 'Upload successfully'); 
        
        }
    
    } 

    public function downloads($gambar)
    {
        
        return $this->response->download('uploads/'.$gambar.'', NULL);
    }

    public function delete($id)
{
    // Memanggil function delete_product() dengan parameter $id di dalam ProductModel dan menampungnya di variabel hapus
    $hapus = $this->stego->delete_gambar($id);
 
    // Jika berhasil melakukan hapus
    if($hapus)
    {
            // Deklarasikan session flashdata dengan tipe warning
        session()->setFlashdata('warning', 'Deleted product successfully');
        // Redirect ke halaman product
        return redirect()->to(base_url('stego'));
    }
} 

}