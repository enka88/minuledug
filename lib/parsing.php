<?php
   function Parse($template_file,$tags) {

     if(!file_exists($template_file))
     {
        echo "File Template <b>$template_file</b> nggak ada salah kali.......";
        return 0;
     }

     $contents = implode("",file($template_file));

     while (list($key,$value) = each($tags)) {
         $tag = "{".$key."}";
         /*
         if(!strstr($contents,$tag))
         {
              echo "nggak ngetemuin tag <b> $tag </b> di file template <b>".$template_file.".</b> coba cek lagi.....";
              return 0;
         }
         */
         $contents = str_replace($tag,$value,$contents);
     }

     print($contents);

 }
?>