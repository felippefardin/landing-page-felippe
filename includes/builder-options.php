<details open><summary>Aparência, fontes e tema</summary>
<div class="builder-field-row"><label>Cor de fundo<input type="color" name="background" value="#f7f9fc"></label><label>Cor do texto<input type="color" name="text_color" value="#172033"></label></div>
<label>Fonte<select name="font"><option value="system">Arial — moderna</option><option value="serif">Georgia — clássica</option><option value="humanist">Trebuchet — acolhedora</option><option value="mono">Courier — tecnológica</option></select></label>
<label>Tema inicial<select name="theme"><option value="light">Claro</option><option value="dark">Escuro</option><option value="auto">Acompanhar o dispositivo</option></select></label>
<p class="builder-hint">O visitante poderá alternar o tema e ajustar a fonte. Escolha cores de texto e fundo com contraste legível.</p></details>
<?php
$groups=['benefits'=>'Benefícios','portfolio'=>'Portfólio','team'=>'Equipe','testimonials'=>'Depoimentos','faq'=>'Perguntas frequentes','seo'=>'Busca, compartilhamento e localização'];
$spec=sb_extra_fields();
foreach($groups as $group=>$title):?>
<details><summary><?=sb_escape($title)?></summary>
<?php if($group==='testimonials'):?><p class="builder-hint">Inclua somente relatos reais, com autorização dos clientes. Nenhum depoimento é preenchido automaticamente.</p><?php endif;?>
<?php foreach($spec as $key=>[$label,$max]):
$matches= match($group){'benefits'=>str_starts_with($key,'benefit'),'portfolio'=>str_starts_with($key,'portfolio'),'team'=>str_starts_with($key,'team'),'testimonials'=>str_starts_with($key,'testimonial'),'faq'=>str_starts_with($key,'faq'),default=>!preg_match('/^(benefit|portfolio|team|testimonial|faq)/',$key)};
if(!$matches)continue;?>
<label><?=sb_escape($label)?><?php if($max>300):?><textarea name="<?=$key?>" maxlength="<?=$max?>" rows="3"></textarea><?php else:?><input name="<?=$key?>" maxlength="<?=$max?>" <?=str_contains($key,'url')||in_array($key,['facebook','linkedin','youtube'],true)?'type="url"':''?>><?php endif;?></label>
<?php endforeach;?>
<?php if(in_array($group,['portfolio','team','seo'],true)):
$keys=$group==='seo'?['share_image']:array_map(fn($n)=>$group.'_image'.$n,range(1,3));
foreach($keys as $key):?>
<label><?=sb_escape($key==='share_image'?'Imagem para compartilhar':($group==='team'?'Foto da pessoa ':'Imagem do projeto ').substr($key,-1))?><input type="file" name="<?=$key?>" accept="image/png,image/jpeg,image/webp"><button type="button" class="builder-text-button" data-clear-image="<?=$key?>">Remover imagem</button></label>
<?php endforeach;endif;?>
</details>
<?php endforeach;?>
<fieldset class="builder-sections"><legend>Seções e ordem da página</legend><p>Marque as seções desejadas e organize com as setas. As seções desmarcadas não aparecem no site.</p>
<div id="section-order"><?php foreach(sb_section_labels() as $key=>$label):?><div data-section="<?=$key?>"><label><input type="checkbox" <?=in_array($key,['about','services','contact'],true)?'checked':''?>> <?=sb_escape($label)?></label><button type="button" data-direction="up" aria-label="Mover <?=sb_escape($label)?> para cima">↑</button><button type="button" data-direction="down" aria-label="Mover <?=sb_escape($label)?> para baixo">↓</button></div><?php endforeach;?></div></fieldset>
