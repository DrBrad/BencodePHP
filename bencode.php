<?php
    class Bencode {

        private static $pos;
        private static $buf;
    
        public function decode($data){
            self::$pos = 0;
            self::$buf = array_values(unpack('C*', $data));
            return self::decodeData();
        }
    
        public function decodeData(){
            $prefix = self::$buf[self::$pos];
    
            if($prefix === 105){
                return self::integer();

            }else if($prefix === 100){
                return self::map();

            }elseif ($prefix === 108){
                return self::list();

            }else if($prefix >= 48 && $prefix <= 57){
                return self::string();

            }else{
                throw new Exception('Invalid format');
            }
        }
    
        private function integer(){
            self::$pos++;
            $val = 0;
            while(self::$buf[self::$pos] !== 101){
                $c = self::$buf[self::$pos++];
                if($c < 48 || $c > 57){
                    throw new Exception('Invalid number');
                }
                $val = $val*10+($c-48);
            }
            self::$pos++;
            return $val;
        }
    
        private function string(){
            $length = 0;
            while(self::$buf[self::$pos] != 58){
                $c = self::$buf[self::$pos++];
                if($c < 48 || $c > 57){
                    throw new Exception('Invalid number');
                }
                $length = $length*10+($c-48);
            }
            $value = pack('C*', ...array_slice(self::$buf, self::$pos+1, $length));
            self::$pos += $length+1;
            return $value;
        }
    
        private function list(){
            self::$pos++;
            $list = [];
            while(self::$buf[self::$pos] !== 101){
                $list[] = self::decodeData();
            }
            self::$pos++;
            return $list;
        }
    
        private function map(){
            self::$pos++;
            $dict = [];
            while(self::$buf[self::$pos] !== 101){
                $key = self::string();
                $value = self::decodeData();
                $dict[$key] = $value;
            }
            self::$pos++;
            return $dict;
        }

        public function encode($data){
            return self::encodeData($data);
        }

        private function encodeData($data){
            if(is_int($data)){
                return 'i'.$data.'e';

            }else if(is_string($data)){
                return strlen($data).':'.$data;

            }else if(is_object($data)){

            }else if(is_array($data)){
                if(self::isAssocArray($data)){
                    $encodedData = 'd';
                    foreach($data as $key => $value){
                        $encodedData .= self::encodeData($key);
                        $encodedData .= self::encodeData($value);
                    }
    
                    $encodedData .= 'e';
                    return $encodedData;
                }

                $encodedData = 'l';
                foreach ($data as $item){
                    $encodedData .= self::encodeData($item);
                }
                
                $encodedData .= 'e';
                return $encodedData;
            }
        }

        private function isAssocArray($array){
            return is_array($array) && array_keys($array) !== range(0, count($array)-1);
        }
    }
?>
