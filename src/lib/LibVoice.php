<?php

namespace AmitxD\Jarvis\lib;

class LibVoice {
    /**
     * Convert the given message to speech using Termux TTS.
     *
     * @param string $msg The message to be spoken.
     *
     * @return void
     */
    public static function say(string $msg): void {
        $msg = strtolower("termux-tts-speak {$msg}");
        system($msg);
        // Uncomment the line below to debug the voice message output.
        // var_dump($voicemsg);
    }

    /**
     * Listen for speech input using Termux speech-to-text.
     *
     * @return string|null The captured speech input, or null if no input is detected.
     */
    public static function listen(): ?string {
        $listen = system("termux-speech-to-text");
        
        // Handle potential errors from the system call
        if ($listen === false) {
            return null;
        }
        
        return $listen;
    }
}