<?php
function testFunction() {
    $unusedVar = "This is not used"; // SonarQube should flag this as an issue
}
?>
