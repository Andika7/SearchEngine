<?php  
 class Main_model extends CI_Model  
 {  
      function is_email_available($email)  
      {  
           $this->db->where('email', $email);  
           $query = $this->db->get("team");  
           if($query->num_rows() > 0)  
           {  
                return false;  
           }  
           else  
           {  
                return true;  
           }  
      }  

      function is_team_available($nama_tim)  
      {  
           $this->db->where('nama_tim', $nama_tim);  
           $query = $this->db->get("team");  
           if($query->num_rows() > 0)  
           {  
                return false;  
           }  
           else  
           {  
                return true;  
           }  
      }  
 }  
 ?>