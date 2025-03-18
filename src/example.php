<?php

// Unused variable (SonarQube will flag this)
$unusedVariable = "I am not used";

// Function complexity issue (too many nested conditions)
function badFunction($input) {
    if ($input > 0) {
        if ($input > 10) {
            if ($input > 20) {
                if ($input > 30) {
                    echo "Very deep nesting!";
                }
            }
        }
    }
}

echo "Code with issues";
?>

