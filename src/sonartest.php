<?php
function testSonarQube() {
    $unusedVar = "This variable is unused"; // This will be flagged
    echo "Hello, world" // Syntax error: missing semicolon
}
?>
