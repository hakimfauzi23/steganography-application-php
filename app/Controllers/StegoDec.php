<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\StegoModel;
class StegoDec extends BaseController
{

    public function index()
    {
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar');
        echo view('stego/decrypt');
        echo view('layouts/footer');
        
    }

    public function decrypt()
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
                
                $src = $avatar; //Start image
                
                $img = imagecreatefrompng($src); //Returns image identifier
                $real_message = ''; //Empty variable to store our message
                
                $count = 0; //Wil be used to check our last char
                $pixelX = 0; //Start pixel x coordinates
                $pixelY = 0; //start pixel y coordinates
                
                list($width, $height, $type, $attr) = getimagesize($src); //get image size
                
                for ($x = 0; $x < ($width*$height); $x++) { //Loop through pixel by pixel
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
                
                  $blue = toBin($b); //Convert our blue to binary
                
                  $real_message .= $blue[strlen($blue) - 1]; //Ad the lsb to our binary result
                
                  $count++; //Coun that a digit was added
                
                  if ($count == 8) { //Every time we hit 8 new digits, check the value
                      if (toString(substr($real_message, -8)) === '|') { //Whats the value of the last 8 digits?
                          echo ('done<br>'); //Yes we're done now
                          $real_message = toString(substr($real_message,0,-8)); //convert to string and remove /
                          echo ('Result: ');
                        //   echo $real_message; //Show
                        return redirect()->to(base_url('StegoDec'))->with('success', 'Pesan Yang Terenkripsi :'.$real_message); 
                          die;

                      }
                      $count = 0; //Reset counter
                  }
                
                  $pixelX++; //Change x coordinates to next
                }        
        
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