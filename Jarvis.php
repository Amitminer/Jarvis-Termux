<?php

namespace Jarvis;

require_once __DIR__ . '/lib/LibVoice.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/LibConfig.php';

use HaoZiTeam\ChatGPT\V1 as ChatGPTV1;
use Jarvis\lib\LibConfig;
use Jarvis\lib\LibVoice;

class Jarvis {
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
    public function runJarvis() {
        while (true) {
            // Listen for user input
            $question = LibVoice::listen();

            // If no input is detected, break the loop and stop the program
            if ($question === null) {
                break;
            }
            // Stop the program if the user says "stop"
            if (strtolower($question) == "stop") {
                break;
            }

            // Print the user's question
            print("You: \n {$question}");
            print("\nJarvis: ...");

            // Get the answer from ChatGPT
            self::getAnswer($question);
        }
    }

    /**
     * Get the API token.
     *
     * @return string|null The API token or null if an error occurs.
     */
    public static function getAPI() {
        try {
            // Load the API token from the configuration file
            $configFilePath = __DIR__ . '/data/api.yml' ?? null;
            $config = new LibConfig($configFilePath);
            $apidump = $config->get('api');
            $config->save();
            return $apidump;
        } catch (\Exception $e) {
            // Handle any errors that occur while getting the API token
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Get the answer from ChatGPT based on the user's question.
     *
     * @param string $question The user's question.
     * @return void
     */
    public static function getAnswer($question): void {
        $chatGPT = new ChatGPTV1();
        // Add the API token to the ChatGPT client
        $chatGPT->addAccount(Jarvis::getAPI());

        // Get the answers from ChatGPT
        $answers = $chatGPT->ask($question);
        foreach ($answers as $item) {
            $answer = $item['answer'];

            // Print and speak the answer
            print("\nJarvis: {$answer}");
            LibVoice::say($answer);
        }
    }
}

// Create an instance of the Jarvis class and run the Jarvis assistant
$jarvis = new Jarvis();
$jarvis->runJarvis();
