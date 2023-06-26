<?php

namespace Jarvis\lib;

use system;

class LibVoice{
    /**
     * Convert the given message to speech using Termux TTS.
     *
     * @param mixed $msg The message to be spoken.
     *
     * @return void
     */
    public static function say(mixed $msg): void{
        $msg = strtolower("termux-tts-speak {$msg}");
        $voicemsg = system($msg);
        // Uncomment the line below to debug the voice message output.
        // var_dump($voicemsg);
    }

    /**
     * Listen for speech input using Termux speech-to-text.
     *
     * @return string|null The captured speech input, or null if no input is detected.
     */
    public static function listen(){
        $listen = system("termux-speech-to-text");
        return $listen;
    }
}
