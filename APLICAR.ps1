param([string]$Destino = 'C:\Users\felippe.andreata\Documents\landing-page-felippe')
$ErrorActionPreference = 'Stop'
$raiz = (Resolve-Path -LiteralPath $Destino).Path
if (-not (Test-Path -LiteralPath (Join-Path $raiz 'src\builder-commerce.php'))) {
    throw 'Escolha a raiz do projeto landing-page-felippe que possui o criador.'
}
$backup = Join-Path $raiz ('backup-criador-' + (Get-Date -Format 'yyyyMMdd-HHmmss'))
foreach ($arquivo in (Get-ChildItem -LiteralPath $PSScriptRoot -Recurse -File)) {
    if ($arquivo.Name -in @('APLICAR.ps1','ATUALIZACAO-CRIADOR.md')) { continue }
    $relativo = $arquivo.FullName.Substring($PSScriptRoot.Length).TrimStart('\')
    $saida = Join-Path $raiz $relativo
    if (Test-Path -LiteralPath $saida) {
        $copia = Join-Path $backup $relativo
        New-Item -ItemType Directory -Path (Split-Path -Parent $copia) -Force | Out-Null
        Copy-Item -LiteralPath $saida -Destination $copia
    }
    New-Item -ItemType Directory -Path (Split-Path -Parent $saida) -Force | Out-Null
    Copy-Item -LiteralPath $arquivo.FullName -Destination $saida -Force
}
Write-Host "Criador atualizado. Copia anterior em: $backup"
