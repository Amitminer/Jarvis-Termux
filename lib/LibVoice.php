<?php

namespace Jarvis\lib;

use system;

class LibVoice{
    
    public static function say(mixed $msg): void{
      $msg = strtolower("termux-tts-speak {$msg}");
      $voicemsg = system($msg);
    //  var_dump($voicemsg);
    }
    public static function listen(){
        $listen = system("termux-speech-to-text");
        return $listen;
    }
}