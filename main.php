<?php

$elementValue = [
    (object)['symbol' => 'A', 'price' => 10],
    (object)['symbol' => 'K', 'price' => 8],
    (object)['symbol' => 'Q', 'price' => 6],
    (object)['symbol' => 'J', 'price' => 4],
    (object)['symbol' => '10', 'price' => 2]
];

$winPosition = [
    [
        (object)["x" => 0, "y" => 0],
        (object)["x" => 0, "y" => 1],
        (object)["x" => 0, "y" => 2],
        (object)["x" => 0, "y" => 3]
    ],//Horizontal
    [
        (object)["x" => 1, "y" => 0],
        (object)["x" => 1, "y" => 1],
        (object)["x" => 1, "y" => 2],
        (object)["x" => 1, "y" => 3]
    ],//Horizontal
    [
        (object)["x" => 2, "y" => 0],
        (object)["x" => 2, "y" => 1],
        (object)["x" => 2, "y" => 2],
        (object)["x" => 2, "y" => 3]
    ],//Horizontal
    [
        (object)["x" => 2, "y" => 0],
        (object)["x" => 1, "y" => 1],
        (object)["x" => 1, "y" => 2],
        (object)["x" => 2, "y" => 3]
    ],//Table-top line
    [
        (object)["x" => 0, "y" => 0],
        (object)["x" => 1, "y" => 1],
        (object)["x" => 1, "y" => 2],
        (object)["x" => 0, "y" => 3]
    ],//Table-top line
    [
        (object)["x" => 0, "y" => 1],
        (object)["x" => 1, "y" => 1],
        (object)["x" => 2, "y" => 1],
        (object)["x" => 2, "y" => 2]
    ] //L shape line

];


$wallet = readline("Enter amount of $ to play with: \n");
$baseBet = readline('Enter base bet amount: ');
$betAmount=$baseBet;

$keepPlaying = true;
while ($keepPlaying) {

    if($wallet<=0){
        echo "You have run out of money!".PHP_EOL;
        echo "Thank you for playing!".PHP_EOL;
        $keepPlaying = false;
        break;
    }
    if($betAmount>$wallet){
        $inEqualSum=true;
        while($inEqualSum){
            echo"Bet amount cannot exceed your available money : $$wallet".PHP_EOL;
            $baseBet=readline("Enter valid bet amount: \n");
            $betAmount=$baseBet;
            if($betAmount<=$wallet){
                $inEqualSum=false;
            }
        }
    }
    //Game rows, columns and elements
    $row = 3;
    $column = 4;
    $elements = ["A", "K", "K", "Q", "Q", "Q", "J", "J", "J", "J", "10", "10", "10", "10", "10"];

    echo 'You have $'.$wallet.' to play with'.PHP_EOL;
    echo 'Base bet: $'.$baseBet.' | ';
    echo 'Bet amount $'.$betAmount.PHP_EOL;
    echo PHP_EOL;
    echo("1. Spin\n2. Increase bet by 1x\n3. Edit base bet\n4. Quit\n\n");

    $choice =readline("Select a choice: \n");
    echo PHP_EOL;
    switch ($choice) {

        case 1:
            $wallet -= $betAmount;

            $board=[];
            for ($i = 0; $i < $row; $i++) {
                for ($j = 0; $j < $column; $j++) {
                    $board[$i][$j] = $elements[array_rand($elements)];
                }
            }

            foreach ($board as $row) {
                echo "\t";
                foreach ($row as $element) {
                    echo $element . " ";
                }
                echo PHP_EOL;
            }

            $organizedLineValues = [];
            foreach ($winPosition as $winSet) {
                $tempLineValues = [];
                foreach ($winSet as $element) {
                    $tempLineValues[] = $board[$element->x][$element->y];
                }
                $organizedLineValues[] = $tempLineValues;
            }

            $matchFound = [];

            foreach ($organizedLineValues as $lineValues) {
                if ((count(array_unique($lineValues)) === 1)) {
                    $matchedSymbol = $lineValues[0];
                    $matchFound[] = $matchedSymbol;
                    echo 'You got a line!'.PHP_EOL;

                }
            }
            foreach ($matchFound as &$symbol) {
                foreach ($elementValue as $element) {
                    if ($symbol == $element->symbol) {
                        $symbol = [
                            'symbol' => $symbol,
                            'price' => $element->price
                        ];

                    }
                }
            }
            if(!count($matchFound)==0)
            {
                $totalPayout=0;
                foreach ($matchFound as $matchPrice) {
                    $totalPayout += $matchPrice['price'] * $betAmount;
                    $wallet+=$totalPayout;
                }
                echo 'Total Payout: $'.$totalPayout.PHP_EOL;
            }
            break;

        case 2:
            $betAmount = $betAmount + $baseBet;
            break;
        case 3:
            $newBetAmount =readline('Enter new bet amount: ');
            $baseBet=$newBetAmount;
            $betAmount=$baseBet;
            echo $baseBet.PHP_EOL;

            break;
        case 4:
            $keepPlaying = false;
            echo "Thank you for playing!" . PHP_EOL;
            break;
        case 5:
            $betAmount=$baseBet;
            echo "Bet amount is reset!";
            break;
        default:
            echo 'Select valid choice 1-3!'.PHP_EOL;
            break;
    }
}
