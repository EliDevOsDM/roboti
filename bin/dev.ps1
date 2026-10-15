param(
    [int]$Port = 8080,
    [ValidateSet('mysql','sqlite')][string]$Database
)
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$phpExecutable = (Get-Command php -ErrorAction Stop).Source
$extensionDirectory = Join-Path (Split-Path $phpExecutable) 'ext'
$phpOptions = @()
if (Test-Path -LiteralPath (Join-Path $extensionDirectory 'php_pdo_sqlite.dll')) {
    $phpOptions += @('-d', "extension_dir=$extensionDirectory", '-d', 'extension=pdo_sqlite')
}
if (Test-Path -LiteralPath (Join-Path $extensionDirectory 'php_pdo_mysql.dll')) {
    $phpOptions += @('-d', 'extension=pdo_mysql')
}
$previousDatabase = [Environment]::GetEnvironmentVariable('DB_CONNECTION', 'Process')
if ($Database) { [Environment]::SetEnvironmentVariable('DB_CONNECTION', $Database, 'Process') }
Push-Location $projectRoot
try {
    & $phpExecutable @phpOptions (Join-Path $PSScriptRoot 'init.php')
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo inicializar la base de datos.' }
    Write-Host "Roboti Academy: http://127.0.0.1:$Port"
    & $phpExecutable @phpOptions -S "127.0.0.1:$Port" -t (Join-Path $projectRoot 'public')
} finally {
    Pop-Location
    if ($Database) { [Environment]::SetEnvironmentVariable('DB_CONNECTION', $previousDatabase, 'Process') }
}
