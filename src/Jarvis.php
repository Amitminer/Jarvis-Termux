<?php

declare(strict_types = 1);

namespace AmitxD\Jarvis;

require_once __DIR__ . '/lib/LibVoice.php';
require_once __DIR__ . '/../vendor/autoload.php';

use HaoZiTeam\ChatGPT\V1 as ChatGPTV1;
use AmitxD\LibConfig\LibConfig;
use AmitxD\Jarvis\lib\LibVoice;
use Exception;
use function readline;

class Jarvis {

    private const MODE_VOICE = 0;
    private const MODE_TEXT = 1;
    private const MODE_TYPE_VOICE = 'voice';
    private const MODE_TYPE_TEXT = 'text';

    /**
    * Jarvis constructor.
    * This is a constructor method for the Jarvis class.
    */
    public function __construct() {
        // NOOP (No Operation)
    }

    /**
    * Run the Jarvis assistant.
    * This method listens for user input, interacts with ChatGPT, and provides responses.
    */
    public function runJarvis(): void {
        $question = '';
        // Listen for user input
        $mode = readline("Welcome to Jarvis AI. Please select your preferred mode: \n[0] Voice \n[1] Text\n: ");
        if ($mode != Jarvis::MODE_VOICE && $mode != Jarvis::MODE_TEXT) {
            echo "Invalid mode choice. Please enter 0 for Voice or 1 for Text.\n";
            return;
        }
        while (true) {

            if ($mode == Jarvis::MODE_VOICE) {
                $question = LibVoice::listen();
                print("\nYou: \n {$question}");
                Jarvis::getAnswer($question, Jarvis::MODE_TYPE_VOICE);
            } elseif ($mode == Jarvis::MODE_TEXT) {
                $question = readline("You: ");
                Jarvis::getAnswer($question, Jarvis::MODE_TYPE_TEXT);
            }

            if (strtolower((string)$question) === 'exit') {
                break;
            }
        }
    }

    /**
    * Get the API token.
    *
    * @return string|null The API token or null if an error occurs.
    */
    private static function getAPI(): ?string {
        try {
            // Load the API token from the configuration file
            $configFilePath = __DIR__ . '/../config.yml';
            $config = new LibConfig($configFilePath);
            $apidump = $config->get('api-token');
            $config->save();
            if ($apidump) {
                return $apidump;
            } else {
                throw new Exception("API token not found or invalid.");
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    /**
    * Get the answer from ChatGPT based on the user's question.
    *
    * @param string $question The user's question.
    * @param string $mode The mode (voice or text) for the response.
    * @return void
    */
    private static function getAnswer(string|null $question, string $mode): void {
        if ($question === null) {
            echo "An Unknown Error Occurred..";
            return;
        }
        $chatGPT = new ChatGPTV1();
        // Add the API token to the ChatGPT client
        $chatGPT->addAccount(Jarvis::getAPI());

        // Get the answers from ChatGPT
        $answers = $chatGPT->ask($question);

        foreach ($answers as $result) {
            $answer = $result['answer'];
            // Print and speak the answer
            if ($mode === Jarvis::MODE_TYPE_VOICE) {
                LibVoice::say($answer);
            }
            print("\nJarvis: {$answer}\n");
        }
    }
}

$jarvis = new Jarvis();
$jarvis->runJarvis();