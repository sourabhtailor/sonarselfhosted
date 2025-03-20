<?php
function testFunction() {
    $unusedVar = "This is not used again"; // SonarQube should flag this as an issue
}
?>
