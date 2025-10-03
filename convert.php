<?php
// Input and output file paths
$inputFile = "molecules/eq.out.noH.xyz";
$outputFile = "molecules/outputv1.mol2";

// Build obabel command
$command = "obabel " . escapeshellarg($inputFile) . " -O " . escapeshellarg($outputFile);

// Execute the command
exec($command, $output, $return_var);

// Check result
if ($return_var === 0) {
    echo "✅ Conversion successful: $outputFile\n";
} else {
    echo "❌ Error in conversion.\n";
}
?>
