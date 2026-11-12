(() => {
  'use strict';
  const editor=document.getElementById('builder-editor'),purchase=document.getElementById('builder-purchase'),sign=document.getElementById('builder-sign');
  if(!editor)return;
  const csrf=document.body.dataset.csrf,images=Object.fromEntries([...editor.querySelectorAll('input[type=file]')].map(e=>[e.name,'']));let current=null,timer,previewRevision=0,quoteRevision=0,locked=false,testedSnapshot='',previewController=null;
  const money=n=>(n/100).toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
  async function api(action,data={},signal) {const response=await fetch('builder-api.php',{method:'POST',credentials:'same-origin',cache:'no-store',signal,headers:{'Content-Type':'application/json'},body:JSON.stringify({action,csrf,...data})});let value;try{value=await response.json();}catch{throw Error('Não foi possível ler a resposta. Atualize a página ou fale com o atendimento.');}if(!response.ok)throw Error(value.error||'Não foi possível concluir.');return value;}
  async function readImage(file){
    const url=URL.createObjectURL(file);
    try{const image=await new Promise((resolve,reject)=>{const img=new Image();img.onload=()=>resolve(img);img.onerror=()=>reject(Error('Não foi possível abrir a imagem.'));img.src=url;});
      const scale=Math.min(1,1200/image.width,1200/image.height);const canvas=document.createElement('canvas');canvas.width=Math.round(image.width*scale);canvas.height=Math.round(image.height*scale);const ctx=canvas.getContext('2d');ctx.drawImage(image,0,0,canvas.width,canvas.height);
      let value=canvas.toDataURL('image/webp',.82);if(value.length>520000)value=canvas.toDataURL('image/webp',.55);if(value.length>650000)throw Error('Reduza esta imagem antes de enviar.');return value;
    }finally{URL.revokeObjectURL(url);}
  }
  function project(){const p=Object.fromEntries(new FormData(editor));for(const key of Object.keys(images)){delete p[key];p[key]=images[key];}p.sections=Array.from(document.getElementById('section-order').children).filter(row=>row.querySelector('input').checked).map(row=>row.dataset.section);return p;}
  function summary(q){document.getElementById('price-discount').textContent=money(-q.discount_cents);document.getElementById('price-support').textContent=money(q.support_cents);document.getElementById('price-total').textContent=money(q.total_cents);}
  async function testProject(){if(locked)return;const button=document.getElementById('test-project');button.disabled=true;try{clearTimeout(timer);previewController?.abort();++previewRevision;const snapshot=project();const d=await api('test_preview',{project:snapshot});if(JSON.stringify(project())!==JSON.stringify(snapshot))throw Error('O projeto mudou. Teste novamente.');testedSnapshot=JSON.stringify(snapshot);document.getElementById('builder-preview').srcdoc=d.html;document.getElementById('test-status').textContent='Teste aberto na prévia. Experimente os links e o formulário e marque a confirmação antes de comprar.';document.querySelector('.builder-preview').scrollIntoView({behavior:'smooth',block:'start'});}catch(e){document.getElementById('test-status').textContent=e.message;}finally{button.disabled=false;}}
  document.getElementById('test-project').addEventListener('click',testProject);
  function invalidatePreview(){
    testedSnapshot='';purchase.elements.tested.checked=false;++previewRevision;
    previewController?.abort();clearTimeout(timer);
  }
  function schedulePreview(){
    invalidatePreview();
    document.getElementById('preview-status').textContent='Alterações pendentes. Atualizando prévia…';
    timer=setTimeout(preview,300);
  }
  async function preview(){
    invalidatePreview();const revision=previewRevision;
    previewController=new AbortController();
    const status=document.getElementById('preview-status');
    const frame=document.getElementById('builder-preview');frame.setAttribute('aria-busy','true');
    status.textContent='Atualizando prévia…';
    try{
      const d=await api('preview',{project:project()},previewController.signal);
      if(revision!==previewRevision)return;
      if(typeof d.html!=='string'||!d.html.trim())throw Error('A prévia retornou vazia. Tente atualizar novamente.');
      frame.srcdoc=d.html;status.textContent='Prévia atualizada. Seções sem conteúdo aparecem enquanto você preenche os campos.';
    }catch(e){if(e.name!=='AbortError'&&revision===previewRevision)status.textContent='A prévia não foi atualizada: '+e.message;}
    finally{if(revision===previewRevision)frame.setAttribute('aria-busy','false');}
  }
  async function quote(){const revision=++quoteRevision;if(!purchase.elements.cpf.value.trim()){document.getElementById('terms-preview').textContent='Informe nome, CPF e e-mail para visualizar o termo.';return;}try{const d=await api('terms',{name:purchase.elements.name.value,cpf:purchase.elements.cpf.value,email:purchase.elements.email.value,coupon:purchase.elements.coupon.value.trim(),support:purchase.elements.support.checked,title:editor.elements.brand.value});if(revision!==quoteRevision)return;summary(d.quote);document.getElementById('terms-preview').textContent=d.terms;document.getElementById('coupon-status').textContent=d.quote.discount_cents?'Cupom conferido. Desconto apenas na página.':'';}catch(e){if(revision!==quoteRevision)return;document.getElementById('coupon-status').textContent=e.message;summary({discount_cents:0,support_cents:purchase.elements.support.checked?15000:0,total_cents:35000+(purchase.elements.support.checked?15000:0)});document.getElementById('terms-preview').textContent='Confira nome, CPF, e-mail e cupom para visualizar o termo com o valor correto.';}}
  function freeze(){editor.querySelectorAll('input,textarea,select,button').forEach(e=>e.disabled=true);purchase.hidden=true;sign.hidden=false;document.getElementById('signature-terms').textContent=current.terms;document.getElementById('signature-info').textContent='Pedido '+current.id+'. O código foi solicitado para '+purchase.elements.email.value+'. Confira o termo abaixo antes de confirmar.';summary(current.quote);const link=document.getElementById('resume-order');link.href='pedido-site.php?id='+encodeURIComponent(current.id)+'&token='+encodeURIComponent(current.token);link.hidden=false;}
  editor.addEventListener('submit',e=>e.preventDefault());editor.addEventListener('input',e=>{if(e.target.type==='file')return;schedulePreview();});
  for(const key of Object.keys(images))editor.elements[key].addEventListener('change',async e=>{const file=e.target.files[0];if(!file)return;if(!['image/png','image/jpeg','image/webp'].includes(file.type)||file.size>2*1024*1024){e.target.value='';document.getElementById('preview-status').textContent='Use PNG, JPG ou WebP de até 2 MB.';return;}try{images[key]=await readImage(file);preview();}catch(error){e.target.value='';document.getElementById('preview-status').textContent=error.message;}});
  document.querySelectorAll('[data-clear-image]').forEach(button=>button.addEventListener('click',()=>{const key=button.dataset.clearImage;images[key]='';editor.elements[key].value='';preview();}));
  document.querySelectorAll('[data-direction]').forEach(button=>button.addEventListener('click',()=>{const row=button.closest('[data-section]');if(button.dataset.direction==='up'&&row.previousElementSibling)row.previousElementSibling.before(row);else if(button.dataset.direction==='down'&&row.nextElementSibling)row.nextElementSibling.after(row);preview();}));
  document.getElementById('update-preview').addEventListener('click',preview);
  for(const device of ['desktop','mobile'])document.getElementById('preview-'+device).addEventListener('click',()=>{document.querySelector('.builder-preview-frame').classList.toggle('is-mobile',device==='mobile');for(const value of ['desktop','mobile'])document.getElementById('preview-'+value).setAttribute('aria-pressed',String(device===value));});
  purchase.elements.cpf.addEventListener('input',e=>{let value=e.target.value.replace(/\D/g,'').slice(0,11);value=value.replace(/^(\d{3})(\d)/,'$1.$2').replace(/^(\d{3})\.(\d{3})(\d)/,'$1.$2.$3').replace(/(\d{3})(\d{1,2})$/,'$1-$2');e.target.value=value;});
  document.getElementById('apply-coupon').addEventListener('click',quote);purchase.addEventListener('change',quote);purchase.addEventListener('input',e=>{if(e.target.name!=='accept')purchase.elements.accept.checked=false;});
  purchase.addEventListener('submit',async e=>{e.preventDefault();if(locked||!purchase.reportValidity()||!editor.reportValidity())return;if(testedSnapshot!==JSON.stringify(project())){document.getElementById('test-status').textContent='Teste a versão atual do projeto antes de continuar.';return;}locked=true;const button=document.getElementById('begin-checkout');button.disabled=true;const status=document.getElementById('checkout-status');status.textContent='Preparando seu termo e enviando o código…';try{current=await api('create',{project:project(),name:purchase.elements.name.value,cpf:purchase.elements.cpf.value,email:purchase.elements.email.value,coupon:purchase.elements.coupon.value.trim(),support:purchase.elements.support.checked,tested:purchase.elements.tested.checked,accept:purchase.elements.accept.checked});freeze();status.textContent=current.sent?'Código enviado. Confira também a pasta de spam.':'O pedido foi criado, mas o código não foi enviado. Aguarde 1 minuto e use Reenviar código.';sign.scrollIntoView({behavior:'smooth',block:'start'});}catch(e){status.textContent=e.message;}finally{locked=false;button.disabled=false;}});
  sign.addEventListener('submit',async e=>{e.preventDefault();if(locked||!sign.reportValidity())return;locked=true;sign.querySelectorAll('button').forEach(b=>b.disabled=true);const status=document.getElementById('checkout-status');status.textContent='Confirmando seu aceite…';try{const d=await api('sign',{id:current.id,token:current.token,terms_hash:current.terms_hash,code:sign.elements.code.value,agree:sign.elements.agree.checked});window.location.assign(d.checkout);}catch(e){status.textContent=e.message+' Você também pode abrir o pedido para continuar.';}finally{locked=false;sign.querySelectorAll('button').forEach(b=>b.disabled=false);}});
  document.getElementById('resend-signature').addEventListener('click',async()=>{if(locked||!current)return;locked=true;try{const d=await api('resend',{id:current.id,token:current.token});document.getElementById('checkout-status').textContent=d.sent?'Novo código enviado.':'Não foi possível enviar. Tente novamente em 1 minuto.';}catch(e){document.getElementById('checkout-status').textContent=e.message;}finally{locked=false;}});
  editor.addEventListener('change',e=>{if(e.target.type==='file')return;schedulePreview();});
  preview();quote();
})();
