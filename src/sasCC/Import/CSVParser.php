<?php

namespace sasCC\Import;

/**
 * Description of CSVParser
 *
 * @author drak3
 */
class CSVParser {
    
    public function parse($file) {
        $pupils = [];
        
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 0, ";")) !== false) {
                $pupils[] = $this->parseRow($data);
            }
        fclose($handle);
       }
       return $pupils;
    }
    
    
    protected function parseRow(array $row) {
        $id = $row[0];
        $name = $row[2];
        $class = $row[3];
        
        
        
        $p = new Pupil;
        $p->companyID = (int) $id;
        $p->rawClass = $class;
        $this->parseName($name, $p);
        var_dump($p);
        return $p;
    }
    
    protected function parseName($name, Pupil $p) {
        $name = \trim($name);
        $split = explode(" ", $name);
        $p->firstName = $split[0];
        $p->lastName = $split[1];
        if(count($split) > 2) {
            $p->isChief = true;
        }
    }
}

?>
