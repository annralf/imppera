<?php
# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Translate\TranslateClient;

# Your Google Cloud Platform project ID
$projectId = 'axial-acrobat-207817';

# Instantiates a client
$translate = new TranslateClient([
    'projectId' => $projectId
]);

# The text to translate
$text = 'Hello, world!';
# The target language
$target = 'es';

# Translates some text into Russian
$translation = $translate->translate($text, [
    'target' => $target
]);

echo 'Text: ' . $text . '
Translation: ' . $translation['text'];
?>
