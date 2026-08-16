<?php
/**
 * SYSTEM INVENTORY v5.3 - THE DIFFERENCE
 * v5.3: unset de la propia entrada vieja → rompe el bucle del title/símbolo
 * ✅/🧪/❌ salud · RUTAS CORREGIDAS · 👁️ ABRIR · backup versionado · auditoría
 * · informe descargable · AGENTE DE DIAGNÓSTICO
 */
session_start();

$baseDir = dirname(__DIR__);
$baseReal = realpath($baseDir);
$baseUrl = 'http://localhost/the-difference-php/';
$backupDir = $baseDir . '/backup-paneles';
$auditFile = $baseDir . '/data/inventario-log.json';
$saludFile = $baseDir . '/data/salud-cache.json';

function rutaSegura($baseReal, $ruta) {
    $ruta = str_replace(['..', "\0"], '', $ruta);
    $completa = realpath($baseReal . '/' . $ruta);
    if ($completa && strpos($completa, $baseReal) === 0) return $completa;
    return null;
}

function extraerError($html) {
    if (!$html) return null;
    foreach (['Fatal error','Parse error','Uncaught','Warning','Notice','Deprecated'] as $p) {
        $pos = stripos($html, $p);
        if ($pos !== false) {
            $f = trim(substr($html, $pos, 260));
            return preg_replace('/\s+/', ' ', trim(strip_tags($f)));
        }
    }
    return null;
}

function diagnosticar($detalle) {
    $d = strtolower($detalle);
    if (strpos($d,'falta incluido')!==false)
        return ['categoria'=>'DEPENDENCIA ROTA','fix'=>'Requiere un archivo que no existe. Restáuralo desde backup, elimina el require, o manda el archivo a backup si es obsoleto.'];
    if (strpos($d,'parse error')!==false || strpos($d,'syntax error')!==false)
        return ['categoria'=>'ERROR DE SINTAXIS','fix'=>'Error de código en la línea indicada. Revisa esa línea o restaura una versión previa desde backup.'];
    if (strpos($d,'fatal error')!==false || strpos($d,'uncaught')!==false)
        return ['categoria'=>'ERROR FATAL','fix'=>'El PHP explota al ejecutarse. Revisa archivo/línea del detalle o manda el archivo a backup.'];
    if (strpos($d,'warning')!==false || strpos($d,'notice')!==false || strpos($d,'deprecated')!==false)
        return ['categoria'=>'ADVERTENCIA','fix'=>'Funciona pero con avisos. Úsalo, pero conviene limpiar el aviso indicado.'];
    if (strpos($d,'json corrupto')!==false)
        return ['categoria'=>'JSON CORRUPTO','fix'=>'Error de formato (coma/comilla). Valida y corrige, o restaura desde backup.'];
    if (strpos($d,'400')!==false)
        return ['categoria'=>'REQUIERE PARÁMETROS','fix'=>'API que espera datos por POST. Comportamiento normal al llamarla vacía. No es un fallo.'];
    if (strpos($d,'405')!==false)
        return ['categoria'=>'SOLO POST','fix'=>'API que solo acepta POST. Comportamiento normal al llamarla por GET. No es un fallo.'];
    if (strpos($d,'404')!==false)
        return ['categoria'=>'NO ENCONTRADO','fix'=>'La ruta no existe o fue movida. Verifica la ruta o elimina la referencia.'];
    if (strpos($d,'500')!==false)
        return ['categoria'=>'ERROR DE SERVIDOR','fix'=>'Falla en ejecución. Revisa logs de Apache/PHP o el detalle.'];
    return ['categoria'=>'REVISAR','fix'=>'Revisa el detalle del error para decidir.'];
}

function chequearIncluidos($completa, $baseReal) {
    $src = @file_get_contents($completa);
    if ($src === false) return 'No se pudo leer el archivo';
    if (preg_match_all('/(?:require|include)(?:_once)?\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $src, $m)) {
        $dir = dirname($completa);
        foreach ($m[1] as $rel) {
            if (strpos($rel,'$')!==false) continue;
            if (!realpath($dir.'/'.$rel) && !realpath($baseReal.'/'.$rel)) return 'Falta incluido: '.$rel;
        }
    }
    return null;
}

function probarArchivo($baseUrl, $baseReal, $ruta, $completa) {
    $ext = strtolower(pathinfo($completa, PATHINFO_EXTENSION));
    if ($ext==='json') { $d=json_decode(@file_get_contents($completa),true); return ['funciona'=>$d!==null,'detalle'=>$d!==null?'JSON válido':'JSON corrupto']; }
    if ($ext!=='php') return ['funciona'=>filesize($completa)>0,'detalle'=>number_format(filesize($completa)/1024,1).' KB'];
    $falta = chequearIncluidos($completa,$baseReal);
    if ($falta) return ['funciona'=>false,'detalle'=>$falta];
    if (function_exists('curl_init')) {
        $ch=curl_init($baseUrl.$ruta); curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); curl_setopt($ch,CURLOPT_TIMEOUT,5);
        $body=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); $err=curl_error($ch); curl_close($ch);
        if ($err) return ['funciona'=>false,'detalle'=>'CURL: '.$err];
        $e=extraerError($body); if ($e) return ['funciona'=>false,'detalle'=>$e];
        return ['funciona'=>($code>=200&&$code<400),'detalle'=>'HTTP '.$code];
    }
    return ['funciona'=>true,'detalle'=>'Chequeo estático OK'];
}

function simboloSalud($salud,$ruta){
    if(!isset($salud[$ruta])) return '<span title="Sin probar" style="font-size:0.9rem;">🧪</span>';
    if($salud[$ruta]['funciona']) return '<span title="✅ '.htmlspecialchars($salud[$ruta]['detalle']).'" style="font-size:0.9rem;">✅</span>';
    return '<span title="❌ '.htmlspecialchars($salud[$ruta]['detalle']).'" style="font-size:0.9rem;">❌</span>';
}

function registrarAuditoria($auditFile,$accion,$detalle){
    $log=file_exists($auditFile)?json_decode(file_get_contents($auditFile),true):[];
    if(!is_array($log))$log=[];
    array_unshift($log,['fecha'=>date('Y-m-d H:i:s'),'accion'=>$accion,'detalle'=>$detalle]);
    @file_put_contents($auditFile,json_encode(array_slice($log,0,100),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
function asegurarBackup($backupDir){ if(!is_dir($backupDir)){@mkdir($backupDir,0777,true);} if(is_dir($backupDir)){@chmod($backupDir,0777);return is_writable($backupDir);} return false; }

$mensaje='';$tipoMensaje='';$resultadoPrueba=null;$resultadosGlobales=null;
$salud=file_exists($saludFile)?json_decode(file_get_contents($saludFile),true):[];
if(!is_array($salud))$salud=[];
// v5.3: el inventario nunca pinta su propia falla antigua (rompe el bucle del title/símbolo)
unset($salud['admin/inventario.php']);

// PROBAR UNO
if(isset($_GET['probar'])){
    $ruta=$_GET['probar']; $completa=rutaSegura($baseReal,$ruta);
    if($completa&&file_exists($completa)){
        $r=probarArchivo($baseUrl,$baseReal,$ruta,$completa);
        $salud[$ruta]=['funciona'=>$r['funciona'],'detalle'=>$r['detalle'],'fecha'=>date('Y-m-d H:i')];
        @file_put_contents($saludFile,json_encode($salud,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        $resultadoPrueba=['ruta'=>$ruta,'funciona'=>$r['funciona'],'detalle'=>$r['detalle']];
    }
}

// ELIMINAR (POST)
if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['eliminar'])){
    $ruta=$_POST['eliminar']; $completa=rutaSegura($baseReal,$ruta);
    if($completa&&file_exists($completa)){
        if(asegurarBackup($backupDir)){
            $destino=$backupDir.'/'.str_replace('/','--',$ruta).'--'.date('Ymd-His').'.bak';
            if(@rename($completa,$destino)){ registrarAuditoria($auditFile,'ELIMINAR→BACKUP',$ruta); $mensaje="🗑️ Movido a backup: $ruta"; $tipoMensaje='success'; }
            else { $err=error_get_last(); $mensaje="❌ Error al mover: ".($err?$err['message']:'?'); $tipoMensaje='error'; }
        } else { $mensaje="❌ backup sin permisos."; $tipoMensaje='error'; }
    } else { $mensaje="❌ No encontrado: $ruta"; $tipoMensaje='error'; }
}

// RESTAURAR (POST)
if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['restaurar'])){
    $archivo=basename($_POST['restaurar']); $origen=$backupDir.'/'.$archivo;
    if(file_exists($origen)){
        $sin=preg_replace('/--\d{8}-\d{6}\.bak$/','',$archivo); if($sin===$archivo)$sin=preg_replace('/\.bak$/','',$archivo);
        $rutaOriginal=str_replace('--','/',$sin); $destino=rutaSegura($baseReal,$rutaOriginal);
        if($destino&&@rename($origen,$destino)){ registrarAuditoria($auditFile,'RESTAURAR',$rutaOriginal); $mensaje="✅ Restaurado: $rutaOriginal"; $tipoMensaje='success'; }
        else { $mensaje="❌ Error al restaurar"; $tipoMensaje='error'; }
    }
}

// ESCANEO
function escanear($dir,$prefijo=''){ $r=[]; if(!is_dir($dir))return $r;
    foreach(array_diff(scandir($dir),['.','..']) as $item){ $c=$dir.'/'.$item; $ruta=$prefijo.$item;
        if(is_dir($c))$r=array_merge($r,escanear($c,$ruta.'/'));
        else $r[]=['nombre'=>$item,'ruta'=>$ruta,'completa'=>$c,'ext'=>strtolower(pathinfo($item,PATHINFO_EXTENSION)),'tamano'=>filesize($c),'modificado'=>filemtime($c)]; }
    return $r; }

$categorias=[
 'paginas'=>['nombre'=>'🌐 Páginas','color'=>'#00bcd4','archivos'=>[]],
 'paneles'=>['nombre'=>'🎛️ Paneles','color'=>'#ff69b4','archivos'=>[]],
 'apis'=>['nombre'=>'🔌 APIs','color'=>'#10b981','archivos'=>[]],
 'agentes'=>['nombre'=>'🤖 Agentes','color'=>'#f59e0b','archivos'=>[]],
 'config'=>['nombre'=>'⚙️ Config','color'=>'#9c27b0','archivos'=>[]],
 'datos'=>['nombre'=>'💾 Datos','color'=>'#fffc34','archivos'=>[]],
 'media'=>['nombre'=>'🖼️ Media','color'=>'#ea4335','archivos'=>[]],
 'backup'=>['nombre'=>'📦 Backup','color'=>'#666666','archivos'=>[]]
];
foreach(escanear($baseDir) as $f) if($f['ext']==='php'&&strpos($f['ruta'],'/')===false)$categorias['paginas']['archivos'][]=$f;
foreach(escanear($baseDir.'/admin') as $f) if($f['ext']==='php'&&strpos($f['ruta'],'/')===false){ $f['ruta']='admin/'.$f['nombre']; $categorias['paneles']['archivos'][]=$f; }
foreach(escanear($baseDir.'/admin/api') as $f) if($f['ext']==='php'&&strpos($f['ruta'],'agentes/')===false){ $f['ruta']='admin/api/'.$f['nombre']; $categorias['apis']['archivos'][]=$f; }
foreach(escanear($baseDir.'/admin/api/agentes') as $f) if($f['ext']==='php'){ $f['ruta']='admin/api/agentes/'.$f['nombre']; $categorias['agentes']['archivos'][]=$f; }
foreach(escanear($baseDir.'/config') as $f){$f['ruta']='config/'.$f['nombre'];$categorias['config']['archivos'][]=$f;}
foreach(escanear($baseDir.'/data') as $f){$f['ruta']='data/'.$f['ruta'];$categorias['datos']['archivos'][]=$f;}
foreach(escanear($baseDir.'/images') as $f){$f['ruta']='images/'.$f['nombre'];$categorias['media']['archivos'][]=$f;}
foreach(escanear($baseDir.'/videos') as $f){$f['ruta']='videos/'.$f['nombre'];$categorias['media']['archivos'][]=$f;}
foreach(escanear($backupDir) as $f)$categorias['backup']['archivos'][]=$f;

function clasificar($archivo){
 $nuevos=['dashboards.php','dashboard.php','log-personal.php','multi-cuentas.php','cuadernos.php','configurar-cuentas.php','guardianes.php','inventario.php','panel-general.php','recopilatorio.php'];
 $esenciales=['configuracion.php','index.php','login.php','logout.php','bitacora.php','registro-bitacora.php','portada.php','menu.php'];
 $redundantes=['panel-ars-tekne.php','panel-conceptos.php','panel-estadisticas.php','panel-evolucion.php','panel-grafo.php','panel-le-tematik.php','panel-log.php','panel-menu.php','panel-nodo-template.php','panel-pensamiento-autista.php','panel-portada.php','panel-project-nada-brahma.php','panel-quantumlab.php','panel-quiron-theatre.php','panel-saiayin-do.php','panel-texvn.php','panel.php','PANELDECONTROLLAMP.php','paginas.php'];
 if(in_array($archivo,$nuevos))return 'nuevo'; if(in_array($archivo,$esenciales))return 'esencial'; if(in_array($archivo,$redundantes))return 'redundante'; return 'activo';
}

// CHEQUEO GLOBAL
if(isset($_GET['probar_todo'])){
 $ok=0;$fail=0;$fallos=[];
 foreach(['paginas','paneles','apis','agentes'] as $ck){ foreach($categorias[$ck]['archivos'] as $f){
    $r=probarArchivo($baseUrl,$baseReal,$f['ruta'],$f['completa']);
    $salud[$f['ruta']]=['funciona'=>$r['funciona'],'detalle'=>$r['detalle'],'fecha'=>date('Y-m-d H:i')];
    if($r['funciona'])$ok++; else{$fail++;$fallos[]=$f['ruta'].' → '.$r['detalle'];}
 }}
 @file_put_contents($saludFile,json_encode($salud,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
 registrarAuditoria($auditFile,'SALUD GLOBAL',"ok=$ok fail=$fail");
 $resultadosGlobales=['ok'=>$ok,'fail'=>$fail,'fallos'=>$fallos];
}

// DESCARGAR INFORME
if(isset($_GET['descargar_informe'])){
 $L=[]; $L[]='INFORME DE SALUD · THE DIFFERENCE'; $L[]='Generado: '.date('Y-m-d H:i:s'); $L[]=str_repeat('=',70);
 foreach($categorias as $ck=>$cat){ $L[]=''; $L[]='## '.$cat['nombre']; $L[]=str_repeat('-',70);
   foreach($cat['archivos'] as $f){
     if($ck==='backup'){$L[]='  [EN BACKUP] '.$f['ruta'];continue;}
     $r=probarArchivo($baseUrl,$baseReal,$f['ruta'],$f['completa']);
     $sim=$r['funciona']?'[  OK  ]':'[FALLA ]';
     $L[]='  '.$sim.'  '.$f['ruta'].'  ->  '.$r['detalle'];
     if(!$r['funciona']){ $dg=diagnosticar($r['detalle']); $L[]='           AGENTE: ['.$dg['categoria'].'] '.$dg['fix']; }
   }}
 $L[]=''; $L[]=str_repeat('=',70);
 header('Content-Type: text/plain; charset=utf-8');
 header('Content-Disposition: attachment; filename="informe-salud-'.date('Ymd-His').'.txt"');
 echo implode("\n",$L); exit;
}

// AGENTE: lista de fallas (se excluye a sí mismo)
$diagnosticos=[];
foreach($salud as $ruta=>$s){
    if(!$s['funciona'] && $ruta!=='admin/inventario.php'){
        $dg=diagnosticar($s['detalle']);
        $diagnosticos[]=['ruta'=>$ruta,'detalle'=>$s['detalle'],'categoria'=>$dg['categoria'],'fix'=>$dg['fix']];
    }
}

$viñetaActiva=isset($_GET['vineta'])?$_GET['vineta']:(isset($_POST['vineta'])?$_POST['vineta']:'paginas');
$totalArchivos=array_sum(array_map(fn($c)=>count($c['archivos']),$categorias));
$totalSize=0; foreach($categorias as $c) foreach($c['archivos'] as $f)$totalSize+=$f['tamano'];
$backupCount=count($categorias['backup']['archivos']);
$audit=file_exists($auditFile)?json_decode(file_get_contents($auditFile),true):[]; if(!is_array($audit))$audit=[];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SYSTEM INVENTORY v5.3 | The Difference</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Courier New',monospace;background:#0a0a0a;color:#e0e0e0;min-height:100vh;padding:2rem;}
.container{max-width:1400px;margin:0 auto;}
.header{border-bottom:2px solid #fffc34;padding-bottom:1rem;margin-bottom:1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;}
.header h1{color:#fffc34;font-size:1.8rem;letter-spacing:3px;} .header p{color:#a0a0a0;margin-top:0.5rem;}
.stats{display:flex;gap:0.75rem;flex-wrap:wrap;}
.stat-chip{background:#151515;border:1px solid #333;padding:0.5rem 1rem;border-radius:20px;font-size:0.8rem;} .stat-chip strong{color:#fffc34;}
.leyenda-colores{display:flex;gap:1.25rem;align-items:center;flex-wrap:wrap;margin-bottom:1.5rem;padding:0.5rem 1rem;background:#151515;border:1px solid #333;border-radius:20px;width:fit-content;}
.leyenda-titulo{font-size:0.65rem;color:#666;text-transform:uppercase;letter-spacing:1px;}
.leyenda-item{display:flex;align-items:center;gap:0.35rem;font-size:0.7rem;color:#a0a0a0;}
.dot{display:inline-block;width:9px;height:9px;border-radius:50%;}
.dot-nuevo{background:#10b981;box-shadow:0 0 6px #10b981;} .dot-esencial{background:#00bcd4;box-shadow:0 0 6px #00bcd4;}
.dot-redundante{background:#ef4444;box-shadow:0 0 6px #ef4444;} .dot-activo{background:#f59e0b;box-shadow:0 0 6px #f59e0b;}
.alert{padding:1rem;border-radius:6px;margin-bottom:1.5rem;font-size:0.9rem;}
.alert.success{background:rgba(16,185,129,0.15);border:1px solid #10b981;color:#10b981;}
.alert.error{background:rgba(239,68,68,0.15);border:1px solid #ef4444;color:#ef4444;}
.agente{background:#151515;border:1px solid #f59e0b;border-radius:8px;padding:1.5rem;margin-bottom:2rem;}
.agente h3{color:#f59e0b;margin-bottom:1rem;}
.diag-item{border-left:3px solid #ef4444;padding:0.6rem 1rem;margin-bottom:0.75rem;background:#1a1a1a;border-radius:4px;}
.diag-ruta{color:#fff;font-weight:bold;font-size:0.85rem;} .diag-cat{color:#ef4444;font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;}
.diag-det{color:#a0a0a0;font-size:0.75rem;margin:0.3rem 0;} .diag-fix{color:#10b981;font-size:0.8rem;}
.vinetas{display:flex;gap:0.5rem;margin-bottom:2rem;flex-wrap:wrap;border-bottom:2px solid #333;padding-bottom:1rem;}
.vineta{padding:0.75rem 1.25rem;background:#151515;border:2px solid #333;border-radius:8px;color:#a0a0a0;text-decoration:none;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;}
.vineta:hover{border-color:#ff69b4;color:#fff;} .vineta.active{color:#fff;background:rgba(255,105,180,0.1);}
.vineta-count{background:#252525;padding:0.15rem 0.5rem;border-radius:10px;font-size:0.7rem;font-weight:bold;}
.tabla-container{background:#151515;border:1px solid #333;border-radius:8px;overflow:hidden;margin-bottom:2rem;}
table{width:100%;border-collapse:collapse;} th,td{padding:0.85rem 1.25rem;text-align:left;border-bottom:1px solid #252525;font-size:0.85rem;}
th{background:#1a1a1a;color:#fffc34;text-transform:uppercase;font-size:0.7rem;letter-spacing:1px;} tr:hover{background:#1a1a1a;}
.badge{padding:0.2rem 0.6rem;border-radius:12px;font-size:0.65rem;font-weight:bold;}
.badge-nuevo{background:rgba(16,185,129,0.2);color:#10b981;border:1px solid #10b981;} .badge-esencial{background:rgba(0,188,212,0.2);color:#00bcd4;border:1px solid #00bcd4;}
.badge-redundante{background:rgba(239,68,68,0.2);color:#ef4444;border:1px solid #ef4444;} .badge-activo{background:rgba(245,158,11,0.2);color:#f59e0b;border:1px solid #f59e0b;}
.ruta{color:#666;font-size:0.7rem;display:block;} .fecha{color:#666;}
.btn-mini{padding:0.3rem 0.7rem;background:#252525;border:1px solid #333;border-radius:4px;color:#e0e0e0;text-decoration:none;font-size:0.7rem;margin-right:0.25rem;display:inline-block;cursor:pointer;}
.btn-mini:hover{border-color:#ff69b4;} .btn-mini.danger{border-color:#ef4444;color:#ef4444;} .btn-mini.danger:hover{background:#ef4444;color:#fff;}
.btn-mini.test{border-color:#10b981;color:#10b981;} .btn-mini.test:hover{background:#10b981;color:#000;}
.barra-inferior{margin-top:1.5rem;display:flex;gap:1rem;align-items:center;flex-wrap:wrap;}
.buscador{flex:1;min-width:250px;padding:0.75rem 1rem;background:#151515;border:1px solid #333;border-radius:6px;color:#e0e0e0;font-family:inherit;font-size:0.9rem;}
.buscador:focus{outline:none;border-color:#fffc34;} .btn-bar{padding:0.75rem 1.5rem;font-size:0.85rem;}
.busqueda-info{margin-top:1rem;color:#fffc34;font-size:0.85rem;}
</style>
</head>
<body>
<div class="container">
<div class="header">
 <div><h1>🗂️ SYSTEM INVENTORY v5.3</h1><p>👁️ Abrir · 🧪 Probar · 🗑️ Backup · ♻️ Restaurar · 🤖 Agente</p></div>
 <div class="stats">
  <span class="stat-chip">Archivos: <strong><?= $totalArchivos ?></strong></span>
  <span class="stat-chip">Tamaño: <strong><?= number_format($totalSize/1024/1024,2) ?> MB</strong></span>
  <span class="stat-chip">Backup: <strong><?= $backupCount ?></strong></span>
  <span class="stat-chip">Fallas: <strong style="color:#ef4444;"><?= count($diagnosticos) ?></strong></span>
 </div>
</div>

<div class="leyenda-colores">
 <span class="leyenda-titulo">Salud:</span><span class="leyenda-item">✅ Funciona</span><span class="leyenda-item">🧪 Sin probar</span><span class="leyenda-item">❌ Fallando</span>
 <span class="leyenda-titulo" style="margin-left:1rem;">Tipo:</span>
 <span class="leyenda-item"><span class="dot dot-nuevo"></span> NUEVO</span><span class="leyenda-item"><span class="dot dot-esencial"></span> ESENCIAL</span>
 <span class="leyenda-item"><span class="dot dot-redundante"></span> REDUNDANTE</span><span class="leyenda-item"><span class="dot dot-activo"></span> ACTIVO</span>
</div>

<?php if($mensaje):?><div class="alert <?= $tipoMensaje ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif;?>
<?php if($resultadoPrueba):?><div class="alert <?= $resultadoPrueba['funciona']?'success':'error' ?>">🧪 <strong><?= htmlspecialchars($resultadoPrueba['ruta']) ?></strong> → <?= $resultadoPrueba['funciona']?'✅ FUNCIONA':'❌ FALLA' ?><br><span style="font-size:0.8rem;">📋 <?= htmlspecialchars($resultadoPrueba['detalle']) ?></span></div><?php endif;?>
<?php if($resultadosGlobales):?><div class="alert <?= $resultadosGlobales['fail']==0?'success':'error' ?>">🩺 SALUD GLOBAL: <strong><?= $resultadosGlobales['ok'] ?> ✅</strong> · <strong><?= $resultadosGlobales['fail'] ?> ❌</strong></div><?php endif;?>

<?php if(!empty($diagnosticos)): ?>
<div class="agente">
 <h3>🤖 Agente de Diagnóstico (<?= count($diagnosticos) ?> fallas)</h3>
 <?php foreach($diagnosticos as $d): ?>
  <div class="diag-item">
    <div class="diag-ruta"><?= htmlspecialchars($d['ruta']) ?> <span class="diag-cat">· <?= $d['categoria'] ?></span></div>
    <div class="diag-det">📋 <?= htmlspecialchars($d['detalle']) ?></div>
    <div class="diag-fix">🔧 <?= htmlspecialchars($d['fix']) ?></div>
  </div>
 <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="vinetas">
 <?php foreach($categorias as $key=>$cat):?><a href="?vineta=<?= $key ?>" class="vineta <?= $key===$viñetaActiva?'active':'' ?>" style="<?= $key===$viñetaActiva?'border-color:'.$cat['color']:'' ?>"><?= $cat['nombre'] ?><span class="vineta-count"><?= count($cat['archivos']) ?></span></a><?php endforeach;?>
</div>

<div class="tabla-container" id="tabla-vineta">
<table><thead><tr><th>Archivo</th><th>Salud</th><th>Estado</th><th>Tamaño</th><th>Acciones</th></tr></thead><tbody>
<?php $archivos=isset($categorias[$viñetaActiva])?$categorias[$viñetaActiva]['archivos']:[]; usort($archivos,fn($a,$b)=>$b['modificado']<=>$a['modificado']);
if(empty($archivos)):?><tr><td colspan="5" style="text-align:center;color:#666;padding:2rem;">No hay archivos</td></tr>
<?php else: foreach($archivos as $f): $estado=clasificar($f['nombre']); $esBackup=($viñetaActiva==='backup');?>
<tr>
 <td><strong><?= htmlspecialchars($f['nombre']) ?></strong><span class="ruta"><?= htmlspecialchars($f['ruta']) ?></span></td>
 <td><?php if($esBackup){echo '📦';}else{echo simboloSalud($salud,$f['ruta']);}?></td>
 <td><?php if($esBackup):?><span class="badge badge-redundante">EN BACKUP</span><?php else:?><span class="badge badge-<?= $estado ?>"><?= strtoupper($estado) ?></span><?php endif;?></td>
 <td><?= number_format($f['tamano']/1024,1) ?> KB</td>
 <td>
  <?php if($esBackup):?>
   <form method="POST" style="display:inline" onsubmit="return confirm('¿Restaurar?');"><input type="hidden" name="restaurar" value="<?= htmlspecialchars($f['nombre']) ?>"><input type="hidden" name="vineta" value="backup"><button class="btn-mini">♻️ Restaurar</button></form>
  <?php else:?>
   <?php if($f['ext']==='php'):?>
     <a href="<?= htmlspecialchars($f['ruta']) ?>" target="_blank" class="btn-mini">👁️ Abrir</a>
     <a href="?probar=<?= urlencode($f['ruta']) ?>&vineta=<?= $viñetaActiva ?>" class="btn-mini test">🧪 Probar</a>
   <?php elseif($f['ext']==='json'):?>
     <a href="?probar=<?= urlencode($f['ruta']) ?>&vineta=<?= $viñetaActiva ?>" class="btn-mini test">🧪 Validar</a>
   <?php else:?>
     <a href="<?= htmlspecialchars($f['ruta']) ?>" target="_blank" class="btn-mini">👁️ Ver</a>
   <?php endif;?>
   <?php if($estado!=='esencial'&&$f['nombre']!=='inventario.php'):?>
     <?= simboloSalud($salud,$f['ruta']) ?>
     <form method="POST" style="display:inline" onsubmit="return confirm('¿Mover a backup?');"><input type="hidden" name="eliminar" value="<?= htmlspecialchars($f['ruta']) ?>"><input type="hidden" name="vineta" value="<?= $viñetaActiva ?>"><button class="btn-mini danger">🗑️ Eliminar</button></form>
   <?php endif;?>
  <?php endif;?>
 </td>
</tr>
<?php endforeach; endif;?>
</tbody></table>
</div>

<div class="tabla-container" id="tabla-busqueda" style="display:none;">
<table><thead><tr><th>Archivo</th><th>Salud</th><th>Categoría</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($categorias as $catKey=>$cat): foreach($cat['archivos'] as $f): $estado=clasificar($f['nombre']); $esBackup=($catKey==='backup'); $searchAttr=strtolower($f['nombre'].' '.$f['ruta'].' '.$cat['nombre']);?>
<tr data-search="<?= htmlspecialchars($searchAttr) ?>">
 <td><strong><?= htmlspecialchars($f['nombre']) ?></strong><span class="ruta"><?= htmlspecialchars($f['ruta']) ?></span></td>
 <td><?php if($esBackup){echo '📦';}else{echo simboloSalud($salud,$f['ruta']);}?></td>
 <td><?= $cat['nombre'] ?></td>
 <td>
  <?php if($esBackup):?>
   <form method="POST" style="display:inline"><input type="hidden" name="restaurar" value="<?= htmlspecialchars($f['nombre']) ?>"><input type="hidden" name="vineta" value="backup"><button class="btn-mini">♻️</button></form>
  <?php else:?>
   <a href="<?= htmlspecialchars($f['ruta']) ?>" target="_blank" class="btn-mini">👁️</a>
   <?php if($f['ext']==='php'):?><a href="?probar=<?= urlencode($f['ruta']) ?>&vineta=<?= $catKey ?>" class="btn-mini test">🧪</a><?php endif;?>
   <?php if($estado!=='esencial'&&$f['nombre']!=='inventario.php'):?><form method="POST" style="display:inline" onsubmit="return confirm('¿Mover a backup?');"><input type="hidden" name="eliminar" value="<?= htmlspecialchars($f['ruta']) ?>"><input type="hidden" name="vineta" value="<?= $catKey ?>"><button class="btn-mini danger">🗑️</button></form><?php endif;?>
  <?php endif;?>
 </td>
</tr>
<?php endforeach; endforeach;?>
</tbody></table>
</div>
<div class="busqueda-info" id="busqueda-info" style="display:none;">🔍 Resultados: <span id="busqueda-count">0</span></div>

<div class="tabla-container">
<table><thead><tr><th>🕘 Fecha</th><th>Acción</th><th>Detalle</th></tr></thead><tbody>
<?php if(empty($audit)):?><tr><td colspan="3" style="text-align:center;color:#666;">Sin operaciones aún</td></tr>
<?php else: foreach(array_slice($audit,0,8) as $a):?><tr><td class="fecha"><?= htmlspecialchars($a['fecha']) ?></td><td><?= htmlspecialchars($a['accion']) ?></td><td><?= htmlspecialchars($a['detalle']) ?></td></tr><?php endforeach; endif;?>
</tbody></table>
</div>

<div class="barra-inferior">
 <input type="text" id="buscador" class="buscador" placeholder="🔍 Buscar archivo...">
 <a href="?probar_todo=1&vineta=<?= $viñetaActiva ?>" class="btn-mini btn-bar test">🩺 Chequeo de salud</a>
 <a href="?descargar_informe=1" class="btn-mini btn-bar">📥 Descargar informe</a>
 <a href="dashboard.php" class="btn-mini btn-bar">📊 Dashboard</a>
 <a href="?vineta=backup" class="btn-mini btn-bar">📦 Ver Backup</a>
</div>
</div>
<script>
const buscador=document.getElementById('buscador');
const tablaVineta=document.getElementById('tabla-vineta');
const tablaBusqueda=document.getElementById('tabla-busqueda');
const info=document.getElementById('busqueda-info');
buscador.addEventListener('input',function(){
 const q=this.value.trim().toLowerCase();
 if(q.length===0){tablaBusqueda.style.display='none';info.style.display='none';tablaVineta.style.display='';}
 else{tablaVineta.style.display='none';tablaBusqueda.style.display='';info.style.display='';let c=0;
  document.querySelectorAll('#tabla-busqueda tbody tr').forEach(tr=>{const m=tr.dataset.search.includes(q);tr.style.display=m?'':'none';if(m)c++;});
  document.getElementById('busqueda-count').textContent=c;}
});
</script>
</body>
</html>