<?php
    class Bencode {

        private static $pos;
        private static $buf;
    
        public function __construct($buf){
            self::$pos = 0;
            self::$buf = array_values(unpack('C*', $buf));
        }
    
        public function decode(){
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
                $val = $val * 10 + ($c - 48);
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
                $length = $length * 10 + ($c - 48);
            }
            $value = pack('C*', ...array_slice(self::$buf, self::$pos + 1, $length));
            self::$pos += $length + 1;
            return $value;
        }
    
        private function list(){
            self::$pos++;
            $list = [];
            while(self::$buf[self::$pos] !== 101){
                $list[] = self::decode();
            }
            self::$pos++;
            return $list;
        }
    
        private function map(){
            self::$pos++;
            $dict = [];
            while(self::$buf[self::$pos] !== 101){
                $key = self::string();
                $value = self::decode();
                $dict[$key] = $value;
            }
            self::$pos++;
            return $dict;
        }
    }
?>
