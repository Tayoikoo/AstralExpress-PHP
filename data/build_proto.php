<?php
if (PHP_OS_FAMILY === 'Windows') {
    system('cls');
} else {
    system('clear');
}

// Paths
$protoFile = 'StarRail.proto';
$outputDir = 'protos/';
$cmdIdFile = 'cmd_id.php';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

echo "Compiling {$protoFile} to {$outputDir}...\n";

$command = "protoc --php_out={$outputDir} {$protoFile}";
exec($command, $output, $exitCode);

if ($exitCode !== 0) {
    echo "[ERROR] Failed to compile {$protoFile}.\n";
    echo "Output:\n" . implode("\n", $output) . "\n";
    exit(1);
}

echo "[SUCCESS] Compiled {$protoFile} to {$outputDir}\n";

$lines = file($protoFile);
$cmdPattern = '/Cmd\w*(CsReq|ScRsp|Notify)\s*=\s*(\d+)/';
$constants = [];

foreach ($lines as $line) {
    if (preg_match($cmdPattern, $line, $matches)) {
        $constDecl = trim(explode('=', $matches[0])[0]);
        $constValue = (int)$matches[2];

        $phpConstName = 'CMD_' . strtoupper(preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', substr($constDecl, 3)));

        $constants[] = [$phpConstName, $constValue];
    }
}

usort($constants, fn($a, $b) => $a[1] <=> $b[1]);

file_put_contents($cmdIdFile, "<?php\n");
file_put_contents($cmdIdFile, "// Auto-generated cmd_id file\n\n", FILE_APPEND);
file_put_contents($cmdIdFile, "class cmd_id {\n", FILE_APPEND);

foreach ($constants as [$name, $value]) {
    file_put_contents($cmdIdFile, "    const {$name} = {$value};\n", FILE_APPEND);
}

file_put_contents($cmdIdFile, "}\n", FILE_APPEND);

echo "[SUCCESS] Generated {$cmdIdFile}\n";

$packetIdMap = [];

foreach ($lines as $line) {
    if (preg_match($cmdPattern, $line, $matches)) {
        $msgName = trim(explode('=', $matches[0])[0]);
        $id = (int)$matches[2];

        $msgName = substr($msgName, 3);

        $packetIdMap[$id] = $msgName;
    }
}

ksort($packetIdMap);

file_put_contents('packetids.json', json_encode($packetIdMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "[SUCCESS] Generated packetids.json\n";

