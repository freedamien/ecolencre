<?php
//On étend notre ObjectModel
class InfosRevendeurClass extends ObjectModel
{
    
public static function getRevendeurDonnees($id_cms)
    {
        $result = Db::getInstance()->getRow("
                    SELECT sl.name as sname
                    FROM ps_store s  
                    inner join ps_store_lang sl on sl.id_store = s.id_store 
                    WHERE  sl.id_lang=1 and s.id_cms = ".$id_cms);
         return $result;
 
    }

}
