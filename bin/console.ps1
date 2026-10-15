param(
    [Parameter(Position=0, Mandatory=$true)][ValidateSet('init','admin')][string]$Task,
    [Parameter(Position=1)][string]$Email,
    [ValidateSet('mysql','sqlite')][string]$Database
)
$ErrorActionPreference = 'Stop'
$phpExecutable = (Get-Command php -ErrorAction Stop).Source
$extensionDirectory = Join-Path (Split-Path $phpExecutable) 'ext'
$phpOptions = @()
foreach ($extension in @('pdo_sqlite','pdo_mysql')) {
    if (Test-Path -LiteralPath (Join-Path $extensionDirectory "php_$extension.dll")) {
        $phpOptions += @('-d', "extension_dir=$extensionDirectory", '-d', "extension=$extension")
    }
}
$previousDatabase = [Environment]::GetEnvironmentVariable('DB_CONNECTION', 'Process')
if ($Database) { [Environment]::SetEnvironmentVariable('DB_CONNECTION', $Database, 'Process') }
try {
    if ($Task -eq 'admin') {
        & $phpExecutable @phpOptions (Join-Path $PSScriptRoot 'create-admin.php') $Email
    } else {
        & $phpExecutable @phpOptions (Join-Path $PSScriptRoot 'init.php')
    }
    $commandExitCode = $LASTEXITCODE
} finally {
    if ($Database) { [Environment]::SetEnvironmentVariable('DB_CONNECTION', $previousDatabase, 'Process') }
}
exit $commandExitCode
