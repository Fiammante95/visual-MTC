<?php

// Questo serve a definire il tipo di risposta che la pagina php deve fornire.
// in questo caso, voglio salvare un file txt
header('Content-Disposition: attachment;filename=myfile.txt');

// non uso le funzioni built-in perchè non mi ricostruisce una struttura corrispondente
// e inoltre i dati più nestati sono nascosti in forma di cdata
function xmlToArray($xml,$ns=null){
 $a = array();
 for($xml->rewind(); $xml->valid(); $xml->next()) {
  $key = $xml->key();
  if(!isset($a[$key])) { $a[$key] = array(); $i=0; }
  else $i = count($a[$key]);
  $simple = true;
  foreach($xml->current()->attributes() as $k=>$v) {
   $a[$key][$i][$k]=(string)$v;
   $simple = false;
  }
  if($ns) foreach($ns as $nid=>$name) {
   foreach($xml->current()->attributes($name) as $k=>$v) {
    $a[$key][$i][$nid.':'.$k]=(string)$v;
    $simple = false;
   }
  }
  if($xml->hasChildren()) {
   if($simple) $a[$key][$i] = xmlToArray($xml->current(), $ns);
   else $a[$key][$i]['content'] = xmlToArray($xml->current(), $ns);
  } else {
   if($simple) $a[$key][$i] = strval($xml->current());
   else $a[$key][$i]['content'] = strval($xml->current());
  }
  $i++;
 }
 return $a;
}

function array_to_xml($array, &$xml) {

    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml->addChild($key);

                array_to_xml($value, $subnode);
            } else {
                array_to_xml($value, $subnode);
            }
        } else {
            $xml->addChild($key, $value);
        }
    }

}

$q = $_GET['url']; // url

$xml = new SimpleXmlIterator($q, null, true);
$namespaces = $xml->getNamespaces(true);
// echo '<pre>'; print_r($namespaces); die;
$arr = xmlToArray($xml,$namespaces);

// Questo permette di stampare l'array in pretty print
//echo '<pre>'; print_r($arr);


$program = $arr["Streams"][0]["DeviceStream"][0]["content"]["ComponentStream"][0]["content"]["Events"][0]["Program"][0];
//print_r($program);
// CREATING XML OBJECT

$xml = new SimpleXMLElement('<Program/>');
array_to_xml($program, $xml);

// TO PRETTY PRINT OUTPUT
$domxml = new DOMDocument('1.0');
$domxml->preserveWhiteSpace = false;
$domxml->formatOutput = true;
$domxml->loadXML($xml->asXML());

echo $domxml->saveXML();



?>
