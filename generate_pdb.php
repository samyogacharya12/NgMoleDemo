<?php

// Define file paths
$xyzPath = __DIR__ . '/molecules/eq.out.noH.xyz';
$pdbPath = __DIR__ . '/molecules/eq.out.pdb';

// Check if input file exists
if (!file_exists($xyzPath)) {
    echo "Error: circDNA.xyz not found.";
    exit;
}

// Construct the Open Babel command as an array (no shell involved)
$cmd = ['obabel', '-ixyz', $xyzPath, '-opdb', '-O', $pdbPath];

// Set up descriptor spec
$descriptorspec = [
    0 => ["pipe", "r"],  // stdin
    1 => ["pipe", "w"],  // stdout
    2 => ["pipe", "w"]   // stderr
];

// Open the process
$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Read stdout and stderr
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    // Close pipes
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    // Get exit code
    $exitCode = proc_close($process);

    // Check exit status
    if ($exitCode === 0) {
        echo "✅ Success: output.pdb was created.";
        // Optional: redirect to viewer
        header("Location: index.html");
        exit;
    } else {
        echo "❌ Error while converting file:<br><pre>$stderr</pre>";
    }
} else {
    echo "❌ Failed to start Open Babel process.";
}
