<?php

function clear() {

    if (PHP_OS === "WINNT") {
        system("cls"); // en el caso de que no se limpie la consola con system("cls"), uso popen("cls", "w")
        popen("cls", "w");
    } else
        system("clear");
}

function check_letters($word, $letter, $discovered_letters) {

    $offset = 0;
    while (($letter_position = strpos($word, $letter, $offset)) !== false) {

        $discovered_letters[$letter_position] = $letter;
        $offset = $letter_position + 1;
    }

    return $discovered_letters;
}

function remaining_attempts ($max, $current) {
    return $max - $current;
}

function print_wrong_letter() {

    clear();
    $GLOBALS["attempts"]++;
    echo "Letra incorrecta :( Te quedan " . remaining_attempts(MAX_ATTEMPTS, $GLOBALS["attempts"]) . " intentos.";
    sleep(2); // detengo 2 segundos la ejecucción del programa para que el usuario pueda leer que la letra es incorrecta
}

function print_man() {

    global $attempts;

    switch ($attempts) {

        case 0:
            echo "
            +---+
            |   |
                |
                |
                |
                |
            =========
            ";
            break;

        case 1:
            echo "
            +---+
            |   |
            O   |
                |
                |
                |
            =========
            ";
            break;

        case 2:
            echo "
            +---+
            |   |
            O   |
            |   |
                |
                |
            =========
            ";
            break;

        case 3:
            echo "
            +---+
            |   |
            O   |
           /|   |
                |
                |
            =========
            ";
            break;

        case 4:
            echo "
            +---+
            |   |
            O   |
           /|\  |
                |
                |
            =========
            ";
            break;

        case 5:
            echo "
            +---+
            |   |
            O   |
           /|\  |
           /    |
                |
            =========
            ";
            break;

        case 6:
            echo "
            ¡La persona ha sido ahorcada! D:
            +---+
            |   |
            O   |
           /|\  |
           / \  |
                |
            =========
            ";
            break;
    }

    echo "\n\n";
}

function print_game() {

    global $word_length, $discovered_letters;

    print_man();

    echo "Palabra de $word_length letras: \n\n";
    echo $discovered_letters;
    echo "\n\n";
}

function start_game () {

    clear();

    global $attempts, $choosen_word, $discovered_letters;

    echo "¡Vamos a jugar al ahorcado! \n\n";

    do {

        // Damos la bienvenida al jugador
        print_game();

        // Pedimos que escriba
        $player_letter = readline("Escribe una letra: ");
        $player_letter = strtolower($player_letter);

        // Empezamos a validar
        if (str_contains($choosen_word, $player_letter)) {
            $discovered_letters = check_letters($choosen_word, $player_letter, $discovered_letters);
        } else {
            print_wrong_letter();
        }

        clear();
    } while ($attempts < MAX_ATTEMPTS && $discovered_letters != $choosen_word);

}

function end_game() {

    global $attempts, $choosen_word, $discovered_letters;

    $palabra_inicial = "La palabra es: $choosen_word\n";
    $palabra_descubierta = "Tú descubriste: $discovered_letters\n\n";

    clear();

    if ($attempts < MAX_ATTEMPTS) {

        echo "¡Felicidades! !Has adivinado la palabra secreta! \n\n";
        echo "Te han sobrado " . remaining_attempts(MAX_ATTEMPTS, $attempts) . " intentos :)\n\n";
        echo $palabra_inicial;
        echo $palabra_descubierta;
        sleep(8);

        another_try("win");
    } else {

        echo "Suerte para la próxima. \n\n";
        print_man();
        echo $palabra_inicial;
        echo $palabra_descubierta;
        sleep(10);

        another_try("loose");
    }

}

function another_try ($win_or_loose) {

    global $choosen_word, $word_length, $possible_words, $discovered_letters, $attempts;

    clear();

    $reintentar = 0;

    if ($win_or_loose === "win") {
        $reintentar = readline("¡Eres increíble! ¿Te gustaría jugar de nuevo?\n(pulsa enter si deseas jugar de nuevo o escribe el número 1 si quieres finalizar el juego): ");
    } else {
        $reintentar = readline("¡Te animo a volver a intentarlo!\n(pulsa enter si deseas jugar de nuevo o escribe el número 1 si quieres finalizar el juego): ");
    }

    switch ($reintentar) {
        case 1:
            clear();
            echo "Juego finalizado.";
            sleep(3);
            clear();
            break;
        default:
            // Inicializamos el juego de nuevo
            $choosen_word = $possible_words[rand(0, 9)];
            $choosen_word = strtolower($choosen_word);
            $word_length = strlen($choosen_word);
            $discovered_letters = str_pad("", $word_length, "_");
            $attempts = 0;

            start_game();

            end_game();
            break;
    }

}

$possible_words = ["Toni", "Bebida", "Prisma", "Ala", "Dolor", "Piloto", "Baldosa", "Terremoto", "Asteroide", "Gallo", "Platzi", "Astronauta", "Universo", "Cohete"];
define("MAX_ATTEMPTS", 6);

// Inicializamos el juego
$choosen_word = $possible_words[rand(0, count($possible_words, 1) - 1)];
$choosen_word = strtolower($choosen_word);
$word_length = strlen($choosen_word);
$discovered_letters = str_pad("", $word_length, "_");
$attempts = 0;

start_game();

end_game();

echo "\n";

/*
Juego mejorado (retos):
- Que al acabar el juego ganado, diga cuantos intentos le han quedado.
- Que al acabar pregunte si quiere jugar de nuevo y reinicie el juego.
- Que el número aleatorio esté adaptado a la cantidad de palabras existentes (en el caso de que se añadan palabras a la lista no habrá que tocar nada más)
*/
?>