<?php

namespace Jarvis;

require_once __DIR__ . '/lib/LibVoice.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/LibConfig.php';

use HaoZiTeam\ChatGPT\V1 as ChatGPTV1;
use Jarvis\lib\LibConfig;
use Jarvis\lib\LibVoice;

class Jarvis {
    public function __construct() {
        // NOOP
    }

    public function runJarvis() {
        while (true) {
            $question = LibVoice::listen();
            if ($question === null) {
                break;
            }

            if (strtolower($question) == "hello") {
                $msg = LibVoice::say("hello");
            }
            if (strtolower($question) == "stop") {
                break;
            }
            print("You: \n {$question}");
            print("...");
            self::getAnswer($question);
        }
    }

    public static function getAPI() {
        try {
            $configFilePath = __DIR__ . '/data/api.yml' ?? null;
            $config = new LibConfig($configFilePath);
            $apidump = $config->get('api');
            $config->save();
            return $apidump;
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    public static function getAnswer($question): void {
        $chatGPT = new ChatGPTV1();
        $chatGPT->addAccount(Jarvis::getAPI());
        $answers = $chatGPT->ask($question);
        foreach ($answers as $item) {
            $answer = $item['answer'];
            print("Jarvis: {$answer}");
            LibVoice::say($answer);
        }
    }
}

$jarvis = new Jarvis();
$jarvis->runJarvis();