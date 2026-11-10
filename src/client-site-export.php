<?php
declare(strict_types=1);
function sb_server_files(array $p): array {
    $root=dirname(__DIR__).'/templates/client-site';$files=[];
    foreach(['index.php','contato.php','instalar.php','.htaccess','INSTALACAO.md','app/bootstrap.php','app/config.example.php','app/.htaccess','admin/login.php','admin/logout.php','admin/index.php','admin/editar.php','admin/admin.css'] as $name){
        $bytes=file_get_contents($root.'/'.$name);if($bytes===false)throw new RuntimeException('Modelo de exportação incompleto.');$files[$name]=$bytes;
    }
    $files['app/config.example.php']=str_replace(['__APP_KEY__','__SETUP_TOKEN__'],[bin2hex(random_bytes(32)),bin2hex(random_bytes(32))],$files['app/config.example.php']);
    $render="<?php\ndeclare(strict_types=1);\n// Renderização do site do cliente. Nenhuma credencial do criador é incluída.\n";
    foreach(['sb_escape','sb_styles','sb_html'] as $function){
        $reflection=new ReflectionFunction($function);$source=file($reflection->getFileName());if($source===false)throw new RuntimeException('Renderizador indisponível.');
        $render.=implode('',array_slice($source,$reflection->getStartLine()-1,$reflection->getEndLine()-$reflection->getStartLine()+1))."\n";
    }
    $files['app/render.php']=$render;
    $data=$p;foreach(['logo','photo'] as $key)$data[$key]=$p[$key]?['ext'=>$p[$key]['ext']]:null;
    $json=json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
    $files['banco.sql']=<<<'SQL'
-- Importe em um banco vazio já criado na SUA hospedagem.
-- Não há senha administrativa predefinida. Use instalar.php após configurar o banco.
CREATE TABLE IF NOT EXISTS site_settings (
 id TINYINT UNSIGNED PRIMARY KEY,
 data LONGTEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS site_admins (
 id INT UNSIGNED PRIMARY KEY,
 username VARCHAR(80) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS site_messages (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL,
 email VARCHAR(254) NOT NULL,
 mensagem TEXT NOT NULL,
 status VARCHAR(16) NOT NULL DEFAULT 'new',
 created_at DATETIME NOT NULL,
 KEY message_state(status,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS site_rate_limits (
 bucket_key CHAR(64) NOT NULL,
 time_bucket BIGINT UNSIGNED NOT NULL,
 hits INT UNSIGNED NOT NULL DEFAULT 0,
 PRIMARY KEY(bucket_key,time_bucket)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SQL;
    $files['banco.sql'].="INSERT IGNORE INTO site_settings (id,data) VALUES (1,CONVERT(0x".bin2hex($json)." USING utf8mb4));\n";
    return $files;
}
