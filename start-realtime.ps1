$ErrorActionPreference = 'Stop'
$projectPath = Split-Path -Parent $MyInvocation.MyCommand.Path

Write-Host 'Menjalankan Global Supply Chain Risk Intelligence Platform...' -ForegroundColor Cyan
Write-Host 'Web: http://127.0.0.1:8000' -ForegroundColor Green
Write-Host 'Scheduler sinkronisasi otomatis aktif.' -ForegroundColor Green

Start-Process php -ArgumentList @('artisan', 'schedule:work') -WorkingDirectory $projectPath -WindowStyle Hidden
Set-Location $projectPath
php artisan serve --host=127.0.0.1 --port=8000
