<?php

// placeholders by chadgpt
$searchPlaceholders = array(
    "What will you search for today?",
    "Looking for something specific?",
    "What's on your mind?",
    "Type your query here...",
    "Find what you're looking for...",
    "Enter your search terms...",
    "Discover new information...",
    "Need help finding something?",
    "Search for articles, news, and more...",
    "Explore the depths of the internet...",
    "Looking for answers?",
    "Unleash the power of search...",
    "Ready to find what you need?",
    "Search high and low...",
    "What's the magic word?",
    "Seek and you shall find...",
    "In search of knowledge...",
    "Type away...",
    "What's the scoop?",
    "Start your search journey...",
    "Where will your curiosity take you?",
    "Hunting for information...",
    "What's the latest buzz?",
    "Ready, set, search!",
    "Enter the realm of search...",
    "What sparks your interest?"
);

function pickRand() {
    $randomNumber = mt_rand(0, 20);
    return $randomNumber;
}

function returnArray($number, $placeholders) {
    $value = $placeholders[$number];
    echo $value;
}

?>