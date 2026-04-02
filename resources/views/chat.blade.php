@extends('layouts.chat')

@section('title', 'Chat met Open Overheid')

@push('styles')
<style>
    :root { --chat-blue: #2563eb; --chat-blue-dark: #1d4ed8; }
    .chat-wrap { display:flex; flex:1; min-height:0; height:100%; }
    .chat-main { display:flex; flex-direction:column; flex:1; min-width:0; background:#ffffff; }
    .chat-msgs { flex:1; overflow-y:auto; padding:24px 16px; }
    .chat-msgs-inner { max-width:768px; margin:0 auto; }

    /* Sidebar */
    .chat-side { width:260px; background:#f8fafc; border-left:1px solid #e2e8f0; display:flex; flex-direction:column; flex-shrink:0; }
    .chat-side.is-hidden { display:none; }
    .chat-side-head { padding:14px 16px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
    .chat-side-list { flex:1; overflow-y:auto; padding:8px; }
    .conv-btn { display:flex; align-items:center; gap:8px; width:100%; padding:8px 10px; border-radius:8px; border:none; background:none; cursor:pointer; font-size:13px; color:#334155; text-align:left; transition:background .1s; }
    .conv-btn:hover { background:#e2e8f0; }
    .conv-btn.is-active { background:#dbeafe; color:#1d4ed8; }
    .conv-del { opacity:0; margin-left:auto; padding:4px; border:none; background:none; cursor:pointer; color:#94a3b8; border-radius:4px; flex-shrink:0; }
    .conv-btn:hover .conv-del { opacity:1; }
    .conv-del:hover { color:#ef4444; background:#fee2e2; }

    /* Messages */
    .msg-row { display:flex; gap:12px; margin-bottom:20px; }
    .msg-row.is-user { flex-direction:row-reverse; }
    .msg-avatar { width:32px; height:32px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:14px; }
    .msg-avatar.ai { background:linear-gradient(135deg,#2563eb,#7c3aed); color:#fff; }
    .msg-avatar.user { background:#e2e8f0; color:#475569; }
    .msg-bubble { max-width:75%; padding:12px 16px; font-size:14px; line-height:1.65; }
    .msg-bubble.ai { background:#f1f5f9; color:#1e293b; border-radius:2px 16px 16px 16px; }
    .msg-bubble.user { background:#2563eb; color:#fff; border-radius:16px 2px 16px 16px; }
    .msg-bubble.ai p { margin:0 0 8px; } .msg-bubble.ai p:last-child { margin:0; }
    .msg-actions { display:flex; gap:2px; margin-top:6px; }
    .msg-actions button { padding:4px; border:none; background:none; cursor:pointer; color:#94a3b8; border-radius:4px; }
    .msg-actions button:hover { color:#475569; background:#e2e8f0; }

    /* Loading dots */
    .dots { display:flex; gap:4px; padding:8px 0; }
    .dots span { width:7px; height:7px; background:#2563eb; border-radius:50%; animation:dotbounce 1.2s infinite; }
    .dots span:nth-child(2) { animation-delay:.15s; }
    .dots span:nth-child(3) { animation-delay:.3s; }
    @keyframes dotbounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-6px)} }

    /* Input */
    .chat-input-wrap { border-top:1px solid #e2e8f0; padding:12px 16px; background:#fff; }
    .chat-input-inner { max-width:768px; margin:0 auto; }
    .input-box { display:flex; align-items:flex-end; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:6px 6px 6px 16px; transition:border-color .15s; }
    .input-box:focus-within { border-color:#2563eb; background:#fff; }
    .input-box textarea { flex:1; border:none; outline:none; resize:none; background:transparent; font-size:14px; line-height:1.5; max-height:120px; padding:6px 0; font-family:inherit; color:#1e293b; }
    .input-box textarea::placeholder { color:#94a3b8; }
    .send-btn { width:36px; height:36px; border-radius:8px; border:none; background:#2563eb; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .15s; }
    .send-btn:hover { background:#1d4ed8; }
    .send-btn:disabled { opacity:.35; cursor:not-allowed; }
    .chat-input-hint { text-align:center; font-size:11px; color:#94a3b8; margin-top:6px; }

    /* Welcome */
    .welcome { display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; padding:40px 20px; }
    .welcome-icon { width:52px; height:52px; background:linear-gradient(135deg,#2563eb,#7c3aed); border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
    .welcome h2 { font-size:22px; font-weight:600; color:#0f172a; margin:0 0 6px; }
    .welcome p { font-size:14px; color:#64748b; margin:0 0 28px; }
    .suggestions { display:grid; grid-template-columns:1fr 1fr; gap:8px; width:100%; max-width:460px; }
    .sug-btn { text-align:left; padding:14px 16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff; cursor:pointer; transition:all .15s; }
    .sug-btn:hover { border-color:#2563eb; background:#eff6ff; }
    .sug-btn strong { display:block; font-size:13px; font-weight:500; color:#0f172a; }
    .sug-btn span { display:block; font-size:11px; color:#94a3b8; margin-top:2px; }

    /* Top bar */
    .chat-topbar { display:flex; align-items:center; justify-content:space-between; padding:8px 16px; border-bottom:1px solid #e2e8f0; background:#fff; }
    .topbar-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 10px; font-size:12px; font-weight:500; border:1px solid #e2e8f0; border-radius:8px; background:#fff; color:#2563eb; cursor:pointer; transition:all .1s; }
    .topbar-btn:hover { background:#eff6ff; border-color:#bfdbfe; }
    .topbar-icon { padding:6px; border:none; background:none; cursor:pointer; color:#64748b; border-radius:6px; }
    .topbar-icon:hover { background:#f1f5f9; }

    @keyframes spin { to { transform:rotate(360deg); } }
    /* Sources */
    .src-list { margin-top:10px; }
    .src-label { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; }
    .src-card { display:flex; align-items:center; gap:10px; padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:4px; text-decoration:none; color:inherit; transition:all .15s; }
    .src-card:hover { border-color:#2563eb; background:#eff6ff; }
    .src-num { width:22px; height:22px; border-radius:50%; background:#2563eb; color:#fff; font-size:11px; font-weight:600; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .src-info { flex:1; min-width:0; }
    .src-title { display:block; font-size:13px; font-weight:500; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .src-card:hover .src-title { color:#1d4ed8; }
    .src-meta { display:block; font-size:11px; color:#94a3b8; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .src-arrow { flex-shrink:0; color:#cbd5e1; }
    .src-card:hover .src-arrow { color:#2563eb; }

    @media(max-width:768px) {
        .chat-side { position:fixed; right:0; top:0; bottom:0; z-index:50; box-shadow:-4px 0 24px rgba(0,0,0,.08); }
        .suggestions { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="chat-wrap" x-data="chatApp()">

    <div class="chat-main">
        <!-- Top bar -->
        <div class="chat-topbar">
            <button class="topbar-btn" @click="startNewChat()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nieuw gesprek
            </button>
            <button class="topbar-icon" @click="sidebarOpen = !sidebarOpen" title="Gesprekken">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/></svg>
            </button>
        </div>

        <!-- Messages area -->
        <div class="chat-msgs" x-ref="msgs">
            <div class="chat-msgs-inner" style="min-height:100%; display:flex; flex-direction:column;" :style="messages.length === 0 ? 'justify-content:center' : ''">

                <!-- Welcome -->
                <template x-if="messages.length === 0">
                    <div class="welcome">
                        <div class="welcome-icon">
                            <svg width="26" height="26" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h2>Vraag het Open Overheid</h2>
                        <p>Doorzoek 645.000+ overheidsdocumenten met AI</p>
                        <div class="suggestions">
                            <template x-for="q in suggestions" :key="q.t">
                                <button class="sug-btn" @click="ask(q.q)">
                                    <strong x-text="q.t"></strong>
                                    <span x-text="q.s"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Messages -->
                <template x-for="(m, i) in messages" :key="i">
                    <div>
                        <template x-if="m.type === 'user'">
                            <div class="msg-row is-user">
                                <div class="msg-avatar user">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="msg-bubble user" x-text="m.text"></div>
                            </div>
                        </template>
                        <template x-if="m.type === 'ai'">
                            <div class="msg-row">
                                <div class="msg-avatar ai">
                                    <svg width="16" height="16" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <template x-if="m.loading">
                                        <div class="dots"><span></span><span></span><span></span></div>
                                    </template>
                                    <template x-if="!m.loading">
                                        <div>
                                            <div class="msg-bubble ai" x-text="m.answer || m.text"></div>
                                            <!-- Sources -->
                                            <template x-if="m.sources && m.sources.length > 0">
                                                <div class="src-list">
                                                    <div class="src-label">Bronnen</div>
                                                    <template x-for="s in m.sources" :key="s.id">
                                                        <a :href="s.url" class="src-card">
                                                            <span class="src-num" x-text="s.num"></span>
                                                            <div class="src-info">
                                                                <span class="src-title" x-text="s.title"></span>
                                                                <span class="src-meta">
                                                                    <span x-show="s.organisation" x-text="s.organisation"></span>
                                                                    <span x-show="s.organisation && s.date"> · </span>
                                                                    <span x-show="s.date" x-text="s.date"></span>
                                                                </span>
                                                            </div>
                                                            <svg class="src-arrow" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>
                                            <div class="msg-actions" x-show="m.answer">
                                                <button @click="copy(m.answer)" title="Kopieer">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

            </div>
        </div>

        <!-- Input -->
        <div class="chat-input-wrap">
            <div class="chat-input-inner">
                <div class="input-box">
                    <textarea x-ref="inp" x-model="text"
                        @keydown.enter.prevent="if(!$event.shiftKey) send()"
                        @input="$el.style.height='auto'; $el.style.height=Math.min($el.scrollHeight,120)+'px'"
                        rows="1" placeholder="Stel je vraag over overheidsdocumenten..."
                        style="min-height:36px;"></textarea>
                    <button class="send-btn" @click="send()" :disabled="!text.trim() || busy">
                        <svg x-show="!busy" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                        <div x-show="busy" style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;"></div>
                    </button>
                </div>
                <div class="chat-input-hint">AI kan fouten maken &middot; Controleer belangrijke informatie</div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="chat-side" :class="{ 'is-hidden': !sidebarOpen }">
        <div class="chat-side-head">
            <span style="font-size:13px; font-weight:600; color:#0f172a;">Gesprekken</span>
            <button @click="sidebarOpen=false" style="padding:4px; border:none; background:none; cursor:pointer; color:#94a3b8;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="chat-side-list">
            <template x-if="convos.length === 0">
                <div style="text-align:center; padding:32px 12px; color:#94a3b8; font-size:12px;">Geen opgeslagen gesprekken</div>
            </template>
            <template x-for="c in convos" :key="c.id">
                <button class="conv-btn" :class="{ 'is-active': curConvo === c.id }" @click="loadConvo(c.id)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0; opacity:.4;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" x-text="c.title"></span>
                    <button class="conv-del" @click.stop="delConvo(c.id)" title="Verwijder">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </button>
            </template>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function chatApp() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    return {
        messages: [],
        text: '',
        busy: false,
        sidebarOpen: window.innerWidth >= 1024,
        convos: [],
        curConvo: null,
        suggestions: [
            { t:'Parkeervergunningen', s:'Wat zijn de regels?', q:'Wat zijn de regels voor parkeervergunningen?' },
            { t:'Duurzaamheid', s:'Recente besluiten', q:'Wat zijn recente besluiten over duurzaamheid?' },
            { t:'Uitkering aanvragen', s:'Hoe werkt het proces?', q:'Hoe vraag ik een uitkering aan?' },
            { t:'WOO-verzoeken', s:'Hoe dien ik er een in?', q:'Hoe dien ik een WOO-verzoek in?' },
        ],

        init() {
            this.$watch('messages', () => this.$nextTick(() => {
                const el = this.$refs.msgs; if(el) el.scrollTop = el.scrollHeight;
            }));
            this.fetchConvos();
        },

        async fetchConvos() {
            try { const r = await fetch('/chat/conversations'); if(r.ok) this.convos = await r.json(); } catch(e){}
        },
        async loadConvo(id) {
            try { const r = await fetch('/chat/conversations/'+id+'/messages'); if(r.ok) { this.messages = await r.json(); this.curConvo = id; } } catch(e){}
        },
        async delConvo(id) {
            if(!confirm('Gesprek verwijderen?')) return;
            try { await fetch('/chat/conversations/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf}}); this.convos=this.convos.filter(c=>c.id!==id); if(this.curConvo===id) this.startNewChat(); } catch(e){}
        },
        startNewChat() { this.messages=[]; this.curConvo=null; this.$nextTick(()=>this.$refs.inp?.focus()); },
        ask(q) { this.text=q; this.send(); },
        copy(t) { if(t) navigator.clipboard.writeText(t).catch(()=>{}); },

        async send() {
            if(!this.text.trim()||this.busy) return;
            const msg = this.text.trim();
            this.text = '';
            if(this.$refs.inp) this.$refs.inp.style.height='36px';

            this.messages.push({type:'user', text:msg});
            const idx = this.messages.length;
            this.messages.push({type:'ai', text:'', answer:null, loading:true});
            this.busy = true;

            try {
                const r = await fetch('/chat/send', {
                    method:'POST',
                    headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},
                    body: JSON.stringify({message:msg, conversation_id:this.curConvo}),
                });
                const d = await r.json();
                this.messages[idx] = {type:'ai', text:'', answer:d.answer||'Geen antwoord ontvangen.', loading:false, sources:d.sources||[]};
                if(d.conversation_id) { this.curConvo=d.conversation_id; this.fetchConvos(); }
            } catch(e) {
                this.messages[idx] = {type:'ai', text:'Er ging iets mis. Probeer opnieuw.', answer:null, loading:false};
            } finally { this.busy=false; }
        },
    };
}
</script>
@endpush
