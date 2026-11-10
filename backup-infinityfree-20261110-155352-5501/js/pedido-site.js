(() => {
 'use strict';const el=document.getElementById('order-config');if(!el)return;
 const config=JSON.parse(el.textContent);let busy=false,cycles=0,interval;
 const $=id=>document.getElementById(id);
 const labels={draft:'Aguardando aceite',signed:'Termo aceito',pending:'Aguardando pagamento',paid:'Pagamento aprovado',refund_requested:'Cancelamento solicitado — devolução em andamento',revoked:'Entrega cancelada / pagamento alterado',needs_review:'Pagamento em conferência'};
 async function api(action,extra={}){const r=await fetch('builder-api.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({action,...config,...extra})});const d=await r.json();if(!r.ok)throw Error(d.error||'Não foi possível consultar o pedido.');return d;}
 function render(d){
  $('order-state').textContent=labels[d.status]||d.status;
  const link=$('order-download');link.hidden=!d.download;link.href=d.download||'#';
  $('order-message').textContent=d.status==='paid'?'Pagamento confirmado pelo provedor.':d.status==='refund_requested'?'Cancelamento registrado. Novos downloads bloqueados. A devolução depende de confirmação do provedor.':'Situação conferida com o provedor.';
  $('order-delivery').textContent=d.status==='paid'?(d.download?(d.sent?'Entrega enviada ao seu e-mail.':'Entrega na fila; você já pode baixar abaixo.')+' Prazo do download: '+d.until_display+'.':'O prazo do download terminou. O atendimento continua disponível.'):'';
  $('refund-section').hidden=!['paid','refund_requested','revoked'].includes(d.status)&&!d.refund;
  $('refund-form').hidden=!d.refund_eligible;
  $('refund-status').textContent=d.refund?(d.refund.status==='refunded'?'O provedor confirmou o reembolso integral. O prazo do crédito depende do meio de pagamento.':'Solicitação registrada em '+d.refund.requested_display+'. Download cancelado; devolução aguardando confirmação.'):(d.refund_eligible?'Você pode solicitar arrependimento até '+d.refund_until_display+'. O motivo é opcional.':'A janela de arrependimento terminou ou o pagamento foi alterado. Para falhas e outros direitos, use o atendimento abaixo.');
  if(['paid','refund_requested','revoked','needs_review'].includes(d.status))$('order-pay')?.remove();
 }
 async function run(action,extra={}){if(busy)return;busy=true;$('order-check').disabled=true;try{const d=await api(action,extra);if(d.checkout){window.location.assign(d.checkout);return;}if(action==='resend'){$('order-message').textContent=d.sent?'Novo código enviado.':'O envio falhou. Tente novamente em 1 minuto.';return;}render(action==='refund_request'?await api('status'):d);}catch(e){$('order-message').textContent=e.message;}finally{busy=false;$('order-check').disabled=false;}}
 $('refund-form').addEventListener('submit',e=>{e.preventDefault();if($('refund-form').reportValidity())run('refund_request',{reason:$('refund-form').elements.reason.value});});
 $('order-check').addEventListener('click',()=>run('status'));$('order-pay')?.addEventListener('click',()=>run('checkout'));
 const sign=$('resume-sign');sign?.addEventListener('submit',e=>{e.preventDefault();if(sign.reportValidity())run('sign',{code:sign.elements.code.value,agree:sign.elements.agree.checked});});$('resume-resend')?.addEventListener('click',()=>run('resend'));
 interval=setInterval(()=>{if(++cycles>=20)clearInterval(interval);if(!document.hidden)run('status');},30000);run('status');
})();