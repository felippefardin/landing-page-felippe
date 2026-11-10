(() => {
  'use strict';
  const dialog=document.getElementById('memory-dialog');if(!dialog)return;
  const byId=id=>document.getElementById('memory-'+id);
  const symbols=['🐶','🐱','🦊','🐼','🦁','🐸','🐵','🦋','🐢','🐬','🍎','🍇','🍓','🍍','🥝','⚽','🏀','🎲','🎯','🎸','🚀','✈️','🚲','⭐','🌈'];
  const names=['cachorro','gato','raposa','panda','leão','sapo','macaco','borboleta','tartaruga','golfinho','maçã','uva','morango','abacaxi','kiwi','futebol','basquete','dado','alvo','guitarra','foguete','avião','bicicleta','estrela','arco-íris'];
  let csrf='',state=null,busy=false,ready=false,timer=null,lastFocus;
  const say=message=>{byId('status').textContent=message||'';};
  const retry=document.createElement('button');retry.id='memory-retry';retry.type='button';retry.className='memory-secondary';retry.textContent='Tentar carregar novamente';retry.hidden=true;byId('status').after(retry);
  function lock(value){busy=value;dialog.setAttribute('aria-busy',String(value));dialog.querySelectorAll('button:not(.memory-close)').forEach(b=>b.disabled=value||(!ready&&b!==retry));retry.hidden=ready||value;if(!value&&state)render();}
  async function status(){
    ready=false;
    const r=await fetch('memoria-api.php',{credentials:'same-origin',cache:'no-store'});const d=await r.json();
    if(!r.ok)throw Error(d.error||'Não foi possível carregar o jogo.');
    if(typeof d.csrf_token!=='string'||d.csrf_token.length<32||!d.state||typeof d.state.verified!=='boolean')throw Error('Não foi possível confirmar a sessão do jogo. Tente carregar novamente.');
    csrf=d.csrf_token;state=d.state;ready=true;render();
  }
  async function loadGame(){if(busy)return;clearTimeout(timer);lock(true);say('Carregando seu jogo…');try{await status();say('');}catch(e){say(e.message||'Não foi possível carregar o jogo.');}finally{lock(false);}}
  retry.addEventListener('click',loadGame);
  async function action(name,extra={}){
    if(busy)return null;clearTimeout(timer);lock(true);let data;
    try{
      if(!ready)await status();
      const post=()=>fetch('memoria-api.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:name,csrf_token:csrf,...extra})});
      let r=await post();data=await r.json();
      // Retry once only when the server explicitly rejected CSRF BEFORE any mutation.
      if(r.status===403&&data.error_code==='csrf'){await status();r=await post();data=await r.json();}
      if(!r.ok){if(r.status===409)await status();if(data.error_code==='setup_required'||data.error_code==='database')ready=false;throw Error(data.error||'Não foi possível concluir a ação.');}
      if(data.state)state=data.state;say(data.message||'');return data;
    }
    catch(e){say(e.message||'Conexão interrompida. Use Atualizar jogo para recuperar a partida.');return null;}
    finally{lock(false);}
  }
  function render(){
    if(!state||!ready)return;
    const verified=state.verified===true;
    byId('email-form').hidden=verified||!byId('code-form').hidden;
    if(verified)byId('code-form').hidden=true;
    byId('play').hidden=!verified;byId('account').replaceChildren();
    if(state.can_manage){const link=document.createElement('a');link.href='cupons-desconto.php';link.textContent='Gerenciar cupons no painel';byId('account').append(link);}
    if(!verified)return;
    const email=document.createElement('p');email.className='memory-email-label';email.textContent='Jogando com '+state.email;byId('account').prepend(email);
    const g=state.game,coupon=state.coupon,active=g?.status==='active';
    byId('pairs').textContent=(g?g.matched.length/2:0)+' / 25';byId('moves').textContent=(g?.moves||0)+' / 80';byId('attempts').textContent=state.games_used+' / 3';
    byId('start').hidden=!!coupon||active||state.games_left===0;byId('start').textContent=g?'Jogar outra partida':'Começar partida';
    byId('abandon').hidden=!active;byId('resend').hidden=!coupon||coupon.status!=='active'||coupon.mail_attempts>=5;
    const result=byId('result');result.replaceChildren();result.hidden=active||(!g&&!coupon);
    if(coupon){result.hidden=false;const title=document.createElement('h3');title.textContent=coupon.status==='active'?'Você conquistou um vale-desconto surpresa no seu primeiro projeto!':({reserved:'Seu cupom está reservado em uma compra pendente.',expired:'Seu cupom expirou.',used:'Seu cupom já foi utilizado.',cancelled:'Este cupom foi desativado.'}[coupon.status]||'Seu voucher');result.append(title);const code=document.createElement('strong');code.className='memory-voucher';code.textContent=coupon.code;result.append(code);const p=document.createElement('p');const date=new Date(coupon.expires_at.replace(' ','T'));p.textContent='Validade: '+date.toLocaleString('pt-BR',{timeZone:'America/Sao_Paulo'})+' (Brasília). '+(coupon.sent?'Voucher enviado ao seu e-mail.':'O envio do e-mail ainda não foi confirmado. Copie o código ou use Reenviar voucher.');result.append(p);}
    else if(g?.status==='lost'){const h=document.createElement('h3');h.textContent='Esta partida terminou.';const p=document.createElement('p');p.textContent=state.games_left?'Você ainda tem '+state.games_left+' partida(s). As cartas serão embaralhadas novamente.':'Você utilizou suas 3 partidas nesta campanha. Obrigado por jogar!';result.append(h,p);}
    const board=byId('board');const previous=document.activeElement?.dataset?.position;board.replaceChildren();board.hidden=!g||!!coupon||g.status==='lost';
    if(!board.hidden){const visible=new Map(g.visible.map(c=>[c.position,c.symbol]));for(let i=0;i<50;i++){const b=document.createElement('button');b.type='button';b.className='memory-card';b.dataset.position=String(i);const symbol=visible.get(i),matched=g.matched.includes(i);b.textContent=symbol===undefined?'?':symbols[symbol];b.classList.toggle('is-open',symbol!==undefined);b.classList.toggle('is-matched',matched);b.setAttribute('aria-label','Carta '+(i+1)+(symbol===undefined?', fechada':', '+names[symbol]+(matched?', par encontrado':'')));b.disabled=busy||!ready||matched||g.open.includes(i)||g.open.length===2;b.addEventListener('click',async()=>{const d=await action('flip',{position:i,revision:state.game.revision});if(d&&!d.message)say('Pares: '+state.game.matched.length/2+' de 25. Jogadas: '+state.game.moves+' de 80.');});board.append(b);}
      if(previous!==undefined){for(let offset=0;offset<50;offset++){const pos=(Number(previous)+offset)%50;const b=board.querySelector('[data-position="'+pos+'"]');if(b&&!b.disabled){b.focus({preventScroll:true});break;}}}
      if(active&&g.open.length===2&&!busy){clearTimeout(timer);timer=setTimeout(()=>action('settle',{revision:state.game.revision}),Math.max(100,g.settle_after_ms+100));}
    }
  }
  byId('launch').disabled=false;
  byId('launch').addEventListener('click',()=>{lastFocus=document.activeElement;dialog.showModal();loadGame();});
  byId('close').addEventListener('click',()=>dialog.close());dialog.addEventListener('close',()=>lastFocus?.focus());
  byId('email-form').addEventListener('submit',async e=>{e.preventDefault();if(!byId('email-form').reportValidity())return;say('Enviando seu código…');const d=await action('request_code',{email:byId('email').value,consent:byId('consent').checked});if(d){byId('code-form').hidden=false;byId('email-form').hidden=true;byId('code').focus();}});
  byId('code-form').addEventListener('submit',async e=>{e.preventDefault();if(!byId('code-form').reportValidity())return;const d=await action('verify_code',{code:byId('code').value});if(d){byId('code').value='';byId('start').focus();}});
  byId('back').addEventListener('click',()=>{byId('code-form').hidden=true;byId('email-form').hidden=false;byId('email').focus();});
  byId('start').addEventListener('click',()=>action('start'));
  byId('abandon').addEventListener('click',()=>{if(confirm('Encerrar esta partida? Ela contará como uma das suas 3 chances.'))action('abandon',{revision:state.game.revision});});
  byId('change').addEventListener('click',async()=>{if(busy)return;const d=await action('change_email');if(d){byId('code-form').hidden=true;render();byId('email').focus();}});
  byId('resend').addEventListener('click',()=>action('resend_voucher'));
  byId('refresh').addEventListener('click',async()=>{if(busy)return;lock(true);try{await status();say('Partida atualizada.');}catch(e){say(e.message);}finally{lock(false);}});
  byId('board').addEventListener('keydown',e=>{if(!['ArrowLeft','ArrowRight','ArrowUp','ArrowDown'].includes(e.key)||!e.target.dataset.position)return;const columns=getComputedStyle(byId('board')).gridTemplateColumns.split(' ').length;const step={ArrowLeft:-1,ArrowRight:1,ArrowUp:-columns,ArrowDown:columns}[e.key];let i=Number(e.target.dataset.position)+step;while(i>=0&&i<50){const b=byId('board').querySelector('[data-position="'+i+'"]');if(b&&!b.disabled){e.preventDefault();b.focus();break;}i+=step;}});
})();
