<?php
function xyzToMol2($xyzFile, $mol2File) {
    $xyz = file($xyzFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $numAtoms = intval(trim($xyz[0]));
    $comment = $xyz[1] ?? "";

    $atoms = [];
    for ($i = 2; $i < 2 + $numAtoms; $i++) {
        $parts = preg_split('/\s+/', trim($xyz[$i]));
        if (count($parts) >= 4) {
            $atoms[] = [
                'symbol' => $parts[0],
                'x' => $parts[1],
                'y' => $parts[2],
                'z' => $parts[3],
            ];
        }
    }

    $mol2 = [];
    $mol2[] = "@<TRIPOS>MOLECULE";
    $mol2[] = "Converted_From_XYZ";
    $mol2[] = count($atoms) . " 0 0 0 0";  // atoms, bonds, substructures, features, sets
    $mol2[] = "SMALL";
    $mol2[] = "NO_CHARGES";
    $mol2[] = "";
    $mol2[] = "@<TRIPOS>ATOM";

    $id = 1;
    foreach ($atoms as $atom) {
        $mol2[] = sprintf(
            "%-7d %-4s %10.4f %10.4f %10.4f %-4s 1 <0> 0.0000",
            $id,
            $atom['symbol'] . $id,
            $atom['x'],
            $atom['y'],
            $atom['z'],
            $atom['symbol']
        );
        $id++;
    }

    file_put_contents($mol2File, implode(PHP_EOL, $mol2) . PHP_EOL);
}

// Example usage
xyzToMol2("molecules/icm.occ.noH.xyz", "icm.output.mol2");
echo "Conversion complete!\n";
?>
