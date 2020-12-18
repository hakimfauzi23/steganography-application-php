<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class StegoModel extends Model
{
    protected $table = "stego";
 
    public function getProduct($id = false)
    {
        if($id === false){
            return $this->table('stego')
                        ->get()
                        ->getResultArray();
        } else {
            return $this->table('stego')
                        ->where('id', $id)
                        ->get()
                        ->getRowArray();
        }   
    }
    
    public function insert_gambar($data)
    {
        return $this->db->table('stego')->insert($data);
    } 

    public function delete_gambar($id)
    {
        return $this->db->table('stego')->delete(['id' => $id]);
    }
}