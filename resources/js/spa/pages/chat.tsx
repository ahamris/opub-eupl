import { useState, useEffect, useRef, useCallback } from "react";
import { Link, useSearchParams } from "react-router";
import {
  Send01, Plus, Trash01, MessageChatSquare, ChevronRight, Shield01,
  SearchLg, File06, Building01, Clock, Download01, Mail01, BookmarkCheck,
  Copy01, ArrowRight, Check, GitBranch01, Menu01, XClose,
} from "@untitledui/icons";
import { api, type Conversation, type ChatMessage, type Source } from "../lib/api";

type Msg = ChatMessage & { loading?: boolean; thinkingStep?: string };

/* ── Markdown-achtige opmaak ── */
function renderMd(text: string) {
  return text
    .replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>")
    .replace(/\*(.+?)\*/g, "<em>$1</em>")
    .replace(/^### (.+)$/gm, '<h4 class="text-sm font-semibold text-gray-900 mt-3 mb-1">$1</h4>')
    .replace(/^## (.+)$/gm, '<h3 class="text-base font-semibold text-gray-900 mt-4 mb-1.5">$1</h3>')
    .replace(/^- (.+)$/gm, '<li class="ml-4 list-disc text-sm text-gray-700">$1</li>')
    .replace(/^(\d+)\. (.+)$/gm, '<li class="ml-4 list-decimal text-sm text-gray-700">$2</li>')
    .replace(/\n/g, "<br/>");
}

export function ChatPage() {
  const [messages, setMessages] = useState<Msg[]>([]);
  const [input, setInput] = useState("");
  const [busy, setBusy] = useState(false);
  const [convos, setConvos] = useState<Conversation[]>([]);
  const [curConvo, setCurConvo] = useState<string | null>(null);
  const [copied, setCopied] = useState<number | null>(null);
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 1024);
  const msgsRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLTextAreaElement>(null);
  const [params] = useSearchParams();
  const autoSent = useRef(false);

  useEffect(() => {
    api.chatConversations().then(setConvos).catch(() => {});
    const q = params.get("q");
    if (q && !autoSent.current) { autoSent.current = true; setTimeout(() => sendMessage(q), 100); }
    else inputRef.current?.focus();
  }, []);

  useEffect(() => {
    msgsRef.current?.scrollTo({ top: msgsRef.current.scrollHeight, behavior: "smooth" });
  }, [messages]);

  const sendMessage = useCallback(async (text?: string) => {
    const msg = (text || input).trim();
    if (!msg || busy) return;
    if (!text) setInput("");
    if (inputRef.current) inputRef.current.style.height = "36px";

    setMessages((m) => [...m, { type: "user", text: msg }, { type: "ai", text: "", loading: true, thinkingStep: "Documenten doorzoeken..." }]);
    setBusy(true);

    const t = setTimeout(() => setMessages((m) => { const n = [...m]; const l = n[n.length-1]; if(l?.loading) n[n.length-1]={...l,thinkingStep:"Antwoord genereren via Geitje..."}; return n; }), 2000);

    try {
      const data = await api.chatSend(msg, curConvo);
      clearTimeout(t);
      setMessages((m) => { const n=[...m]; n[n.length-1]={type:"ai",text:"",answer:data.answer,sources:data.sources}; return n; });
      if (data.conversation_id) { setCurConvo(data.conversation_id); api.chatConversations().then(setConvos).catch(()=>{}); }
    } catch { clearTimeout(t); setMessages((m) => { const n=[...m]; n[n.length-1]={type:"ai",text:"Er ging iets mis. Probeer het opnieuw."}; return n; }); }
    finally { setBusy(false); setTimeout(()=>inputRef.current?.focus(),100); }
  }, [input, busy, curConvo]);

  const loadConvo = async (id: string) => { const msgs = await api.chatMessages(id).catch(()=>[]as ChatMessage[]); setMessages(msgs); setCurConvo(id); };
  const newChat = () => { setMessages([]); setCurConvo(null); setTimeout(()=>inputRef.current?.focus(),50); };
  const delConvo = async (id: string) => { if(!confirm("Verwijderen?"))return; await api.chatDelete(id).catch(()=>{}); setConvos(c=>c.filter(x=>x.id!==id)); if(curConvo===id) newChat(); };

  const copyText = (text: string, idx: number) => { navigator.clipboard.writeText(text).catch(()=>{}); setCopied(idx); setTimeout(()=>setCopied(null),2000); };

  const emailDossier = (answer: string, sources?: Source[]) => {
    const body = `${answer}\n\n---\nBronnen:\n${(sources||[]).map(s=>`${s.num}. ${s.title} — ${window.location.origin}${s.url}`).join("\n")}\n\nVia oPub.nl — Sovereign AI`;
    window.location.href = `mailto:?subject=oPub dossier&body=${encodeURIComponent(body)}`;
  };

  const exportAs = (format: "pdf" | "json" | "xml" | "md", answer: string, sources?: Source[]) => {
    const srcs = sources || [];
    let content = "", mime = "text/plain", ext = format;

    if (format === "json") {
      content = JSON.stringify({ platform: "oPub.nl", generated: new Date().toISOString(), ai: "Ollama + Geitje (Sovereign AI)", answer, sources: srcs.map(s => ({ nr: s.num, title: s.title, organisation: s.organisation, date: s.date, url: `${window.location.origin}${s.url}` })) }, null, 2);
      mime = "application/json";
    } else if (format === "xml") {
      content = `<?xml version="1.0" encoding="UTF-8"?>\n<dossier platform="oPub.nl" generated="${new Date().toISOString()}" ai="Ollama + Geitje">\n  <antwoord><![CDATA[${answer}]]></antwoord>\n  <bronnen>\n${srcs.map(s => `    <bron nr="${s.num}">\n      <titel>${s.title}</titel>\n      <organisatie>${s.organisation||""}</organisatie>\n      <datum>${s.date||""}</datum>\n      <url>${window.location.origin}${s.url}</url>\n    </bron>`).join("\n")}\n  </bronnen>\n</dossier>`;
      mime = "application/xml";
    } else if (format === "md") {
      content = `# oPub Dossier\n\n> Gegenereerd op ${new Date().toLocaleString("nl-NL")} via Sovereign AI (Ollama + Geitje)\n\n## Antwoord\n\n${answer}\n\n## Bronnen\n\n${srcs.map(s => `${s.num}. **${s.title}**\\\n   ${s.organisation||""} · ${s.date||""}\\\n   [Bekijk document](${window.location.origin}${s.url})`).join("\n\n")}\n\n---\n*oPub.nl — Open source Woo-zoekplatform (EUPL 1.2)*`;
      mime = "text/markdown";
    } else {
      // PDF = print-friendly HTML
      const html = `<!DOCTYPE html><html><head><meta charset="utf-8"><title>oPub Dossier</title><style>body{font-family:Inter,sans-serif;max-width:700px;margin:40px auto;color:#1f2937;line-height:1.6;font-size:14px}h1{font-size:20px;color:#155EEF}h2{font-size:16px;margin-top:24px;border-bottom:1px solid #e5e7eb;padding-bottom:6px}.bron{padding:10px 14px;margin:8px 0;border:1px solid #e5e7eb;border-radius:8px}.bron-title{font-weight:600;color:#111827}.bron-meta{font-size:12px;color:#6b7280;margin-top:2px}.footer{margin-top:32px;padding-top:16px;border-top:1px solid #e5e7eb;font-size:11px;color:#9ca3af}</style></head><body><h1>oPub — Interactief Dossier</h1><p style="color:#6b7280;font-size:12px">Gegenereerd op ${new Date().toLocaleString("nl-NL")} · Sovereign AI (Ollama + Geitje)</p><h2>Antwoord</h2><p>${answer.replace(/\n/g,"<br>")}</p><h2>Bronnen (${srcs.length})</h2>${srcs.map(s=>`<div class="bron"><div class="bron-title">${s.num}. ${s.title}</div><div class="bron-meta">${[s.organisation,s.date].filter(Boolean).join(" · ")}<br><a href="${window.location.origin}${s.url}">${window.location.origin}${s.url}</a></div></div>`).join("")}<div class="footer">oPub.nl — Open source Woo-zoekplatform · EUPL 1.2 · Gemaakt door CodeLabs B.V.</div></body></html>`;
      const w = window.open("", "_blank");
      if (w) { w.document.write(html); w.document.close(); setTimeout(() => w.print(), 300); }
      return;
    }

    const blob = new Blob([content], { type: `${mime};charset=utf-8` });
    const a = document.createElement("a"); a.href = URL.createObjectURL(blob); a.download = `opub-dossier-${Date.now()}.${ext}`; a.click(); URL.revokeObjectURL(a.href);
  };

  const formatDate = (d: string) => { const date=new Date(d), diff=Math.floor((Date.now()-date.getTime())/86400000); return diff===0?"Vandaag":diff===1?"Gisteren":diff<7?`${diff}d geleden`:date.toLocaleDateString("nl-NL",{day:"numeric",month:"short"}); };

  const suggestions = [
    { q: "Wat houdt de Wet Open Overheid in?", icon: File06 },
    { q: "Welke besluiten zijn er over duurzaamheid?", icon: SearchLg },
    { q: "Hoe dien ik een Woo-verzoek in?", icon: File06 },
    { q: "Recente kamerstukken over woningbouw", icon: Building01 },
  ];

  // Verzamel alle bronnen uit het gesprek
  const allSources = messages.filter(m => m.sources?.length).flatMap(m => m.sources!);

  return (
    <div className="mx-auto max-w-[1187px] px-4 sm:px-6 flex" style={{ height: "calc(100vh - 57px)" }}>

      {/* ── Sidebar links ── */}
      <div className={`${sidebarOpen ? "w-[260px]" : "w-0 overflow-hidden"} border-r border-gray-100 flex flex-col shrink-0 transition-all duration-200`}>
        {/* Nieuw gesprek */}
        <div className="p-3 border-b border-gray-100">
          <button onClick={newChat}
            className="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
            <Plus className="w-4 h-4" /> Nieuw gesprek
          </button>
        </div>

        {/* Acties */}
        <div className="p-2 border-b border-gray-100 space-y-0.5">
          <p className="px-2 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Acties</p>
          {[
            { label: "Samenvatten", icon: BookmarkCheck, color: "text-violet-600", action: () => sendMessage("Vat het vorige antwoord samen in markdown met koppen, opsommingen en belangrijkste punten.") },
            { label: "Tijdslijn maken", icon: GitBranch01, color: "text-blue-600", action: () => sendMessage("Maak een chronologische tijdslijn van alle genoemde documenten en gebeurtenissen in markdown.") },
            { label: "Download", icon: Download01, color: "text-emerald-600", action: null },
            { label: "E-mail dossier", icon: Mail01, color: "text-amber-600", action: () => { const last=messages.filter(m=>m.answer).pop(); if(last) emailDossier(last.answer!,last.sources); } },
            { label: "Kopieer antwoord", icon: Copy01, color: "text-gray-500", action: () => { const last=messages.filter(m=>m.answer).pop(); if(last) copyText(last.answer!,-1); } },
          ].map(({ label, icon: Icon, color, action }) => (
            label === "Download" ? (
              <div key={label} className="group relative">
                <button className={`w-full flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium ${color} hover:bg-gray-50 transition-colors`}>
                  <Icon className="w-3.5 h-3.5" /> {label}
                  <ChevronRight className="w-3 h-3 ml-auto text-gray-300" />
                </button>
                <div className="hidden group-hover:block absolute left-full top-0 ml-1 bg-white border border-gray-200 rounded-lg shadow-lg py-1 w-36 z-50">
                  {(["pdf", "md", "json", "xml"] as const).map(fmt => (
                    <button key={fmt} onClick={() => { const last=messages.filter(m=>m.answer).pop(); if(last) exportAs(fmt,last.answer!,last.sources); }}
                      className="w-full text-left px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center justify-between">
                      <span>{fmt === "pdf" ? "PDF (afdruk)" : fmt === "md" ? "Markdown (.md)" : fmt === "json" ? "JSON" : "XML"}</span>
                      <span className="text-[10px] text-gray-400 uppercase">.{fmt}</span>
                    </button>
                  ))}
                </div>
              </div>
            ) : (
              <button key={label} onClick={() => action?.()} disabled={!messages.some(m=>m.answer)}
                className={`w-full flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium disabled:opacity-30 ${color} hover:bg-gray-50 transition-colors`}>
                <Icon className="w-3.5 h-3.5" /> {label}
              </button>
            )
          ))}
        </div>

        {/* Gesprekkenlijst */}
        <div className="flex-1 overflow-y-auto p-2 space-y-0.5">
          <p className="px-2 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Geschiedenis</p>
          {convos.length === 0 && (
            <div className="text-center py-8 px-3">
              <MessageChatSquare className="w-7 h-7 text-gray-200 mx-auto mb-2" />
              <p className="text-[11px] text-gray-400">Nog geen gesprekken</p>
            </div>
          )}
          {convos.map((c) => (
            <div key={c.id}
              className={`group flex items-start gap-2 px-2.5 py-2 rounded-lg cursor-pointer text-xs transition-colors ${
                curConvo === c.id ? "bg-blue-50 text-blue-700" : "text-gray-600 hover:bg-white"
              }`}
              onClick={() => loadConvo(c.id)}>
              <MessageChatSquare className="w-3.5 h-3.5 shrink-0 opacity-40 mt-0.5" />
              <div className="flex-1 min-w-0">
                <p className="truncate leading-snug">{c.title}</p>
                <p className="text-[10px] text-gray-400 mt-0.5">{formatDate(c.updated_at)}</p>
              </div>
              <button onClick={(e) => { e.stopPropagation(); delConvo(c.id); }}
                className="opacity-0 group-hover:opacity-100 p-0.5 text-gray-400 hover:text-red-500 transition-all shrink-0">
                <Trash01 className="w-3 h-3" />
              </button>
            </div>
          ))}
        </div>

        {/* Sovereign AI footer */}
        <div className="p-3 border-t border-gray-100 flex items-center gap-1.5 text-[10px] text-gray-400">
          <Shield01 className="w-3 h-3 text-emerald-500" /> Sovereign AI · EUPL 1.2
        </div>
      </div>

      {/* ── Chat main ── */}
      <div className="flex flex-col flex-1 min-w-0">
        {/* Mobile toggle */}
        <div className="flex items-center h-10 px-3 border-b border-gray-100 shrink-0 md:hidden">
          <button onClick={() => setSidebarOpen(!sidebarOpen)} className="p-1.5 text-gray-400 hover:text-gray-700">
            {sidebarOpen ? <XClose className="w-4 h-4" /> : <Menu01 className="w-4 h-4" />}
          </button>
        </div>

        {/* Messages */}
        <div ref={msgsRef} className="flex-1 overflow-y-auto px-4 sm:px-6 py-6">
          <div className="max-w-[680px] mx-auto min-h-full flex flex-col" style={messages.length === 0 ? { justifyContent: "center" } : {}}>

            {/* Welcome */}
            {messages.length === 0 && (
              <div className="py-6">
                <div className="text-center mb-8">
                  <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                    <span className="text-white font-bold text-sm">oP</span>
                  </div>
                  <h2 className="text-xl font-semibold text-gray-900 mb-1.5">Stel een vraag in gewone taal</h2>
                  <p className="text-sm text-gray-500 max-w-md mx-auto">
                    De oPub AI-assistent doorzoekt 641.000+ Woo-documenten en maakt een interactief dossier met bronverwijzingen.
                  </p>
                  <div className="inline-flex items-center gap-1.5 mt-3 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-medium border border-emerald-100">
                    <Shield01 className="w-3 h-3" /> Lokale verwerking — geen externe AI-providers
                  </div>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 max-w-lg mx-auto">
                  {suggestions.map(({ q, icon: Icon }) => (
                    <button key={q} onClick={() => sendMessage(q)}
                      className="flex items-start gap-2.5 text-left p-3.5 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 text-sm text-gray-600 hover:text-gray-900 transition-all group">
                      <Icon className="w-4 h-4 text-gray-400 group-hover:text-blue-500 shrink-0 mt-0.5 transition-colors" />
                      <span>{q}</span>
                    </button>
                  ))}
                </div>
              </div>
            )}

            {/* Messages */}
            <div className="space-y-6">
              {messages.map((m, i) => (
                <div key={i}>
                  {m.type === "user" ? (
                    <div className="flex justify-end">
                      <div className="max-w-[80%] px-4 py-3 rounded-2xl rounded-tr-sm bg-blue-600 text-white text-sm leading-relaxed">{m.text}</div>
                    </div>
                  ) : (
                    <div className="flex gap-3">
                      <div className="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-600 to-violet-600 flex items-center justify-center shrink-0 mt-0.5">
                        <span className="text-white text-[9px] font-bold">oP</span>
                      </div>
                      <div className="flex-1 min-w-0">
                        {m.loading ? (
                          <div className="flex items-center gap-3 py-2">
                            <div className="flex gap-1">{[0,1,2].map(d=><span key={d} className="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce" style={{animationDelay:`${d*.15}s`}}/>)}</div>
                            <span className="text-xs text-gray-400 animate-pulse">{m.thinkingStep}</span>
                          </div>
                        ) : (
                          <>
                            {/* Antwoord met markdown rendering */}
                            <div className="prose-sm text-sm text-gray-700 leading-relaxed" dangerouslySetInnerHTML={{ __html: renderMd(m.answer || m.text || "") }} />

                            {/* Bronnen als tijdslijn */}
                            {m.sources && m.sources.length > 0 && (
                              <div className="mt-4 rounded-xl border border-gray-100 overflow-hidden">
                                <div className="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                                  <GitBranch01 className="w-3.5 h-3.5 text-blue-600" />
                                  <span className="text-xs font-semibold text-gray-700">Bronnen ({m.sources.length})</span>
                                </div>
                                <div className="divide-y divide-gray-50">
                                  {m.sources.map((s: Source) => (
                                    <Link key={s.id} to={s.url}
                                      className="flex items-start gap-3 px-4 py-2.5 hover:bg-blue-50/30 transition-colors group">
                                      <span className="w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">{s.num}</span>
                                      <div className="flex-1 min-w-0">
                                        <p className="text-[13px] font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">{s.title}</p>
                                        <div className="flex items-center gap-2 mt-0.5">
                                          {s.organisation && <span className="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-medium"><Building01 className="w-2.5 h-2.5"/>{s.organisation.length>30?s.organisation.slice(0,30)+"…":s.organisation}</span>}
                                          {s.date && <span className="text-[10px] text-gray-400 flex items-center gap-1"><Clock className="w-2.5 h-2.5"/>{s.date}</span>}
                                        </div>
                                      </div>
                                      <ArrowRight className="w-3 h-3 text-gray-300 group-hover:text-blue-500 shrink-0 mt-1.5 transition-colors" />
                                    </Link>
                                  ))}
                                </div>
                              </div>
                            )}
                          </>
                        )}
                      </div>
                    </div>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Input */}
        <div className="border-t border-gray-100 p-4 shrink-0 bg-white">
          <div className="max-w-[680px] mx-auto">
            <div className="flex items-end gap-2 border border-gray-200 rounded-xl px-4 py-2 focus-within:border-blue-300 focus-within:ring-4 focus-within:ring-blue-50 transition-all bg-white">
              <textarea ref={inputRef} value={input}
                onChange={(e) => { setInput(e.target.value); e.target.style.height="auto"; e.target.style.height=Math.min(e.target.scrollHeight,120)+"px"; }}
                onKeyDown={(e) => { if(e.key==="Enter"&&!e.shiftKey){e.preventDefault();sendMessage();} }}
                rows={1} placeholder="Stel uw vraag over Woo-documenten..."
                className="flex-1 bg-transparent border-0 outline-none resize-none text-sm text-gray-900 placeholder:text-gray-400 max-h-28 py-1.5" style={{minHeight:36}} />
              <button onClick={()=>sendMessage()} disabled={!input.trim()||busy}
                className="p-2 rounded-lg bg-blue-600 text-white disabled:opacity-40 hover:bg-blue-700 transition-colors shrink-0">
                <Send01 className="w-4 h-4" />
              </button>
            </div>
            <p className="text-center text-[10px] text-gray-400 mt-2">Uw data verlaat nooit onze infrastructuur · Sovereign AI via Ollama + Geitje · EUPL 1.2</p>
          </div>
        </div>
      </div>
    </div>
  );
}
