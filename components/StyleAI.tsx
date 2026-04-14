
import React, { useState } from 'react';
import { Sparkles, Send, Loader2, Wand2 } from 'lucide-react';
import { getStyleAdvice } from '../services/gemini';

const StyleAI: React.FC = () => {
  const [occasion, setOccasion] = useState('');
  const [prefs, setPrefs] = useState('');
  const [advice, setAdvice] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleConsult = async () => {
    if (!occasion) return;
    setLoading(true);
    const result = await getStyleAdvice(occasion, prefs);
    setAdvice(result || "Não consegui gerar uma dica agora.");
    setLoading(false);
  };

  return (
    <div className="max-w-2xl mx-auto space-y-8 animate-fadeIn">
      <div className="text-center space-y-3">
        <div className="inline-flex p-3 bg-analux-secondary/20 text-analux-secondary rounded-full mb-4">
          <Sparkles size={32} />
        </div>
        <h1 className="text-4xl font-serif text-analux-primary">Consultora de Estilo AI</h1>
        <p className="text-gray-500">Dúvida sobre o que usar? Nossa inteligência artificial ajuda você a escolher as semijoias perfeitas da Analux para qualquer ocasião.</p>
      </div>

      <div className="bg-white p-8 rounded-[40px] shadow-2xl border border-analux-secondary/10 relative">
        <div className="space-y-6">
          <div className="space-y-2">
            <label className="text-xs font-bold text-analux-primary uppercase tracking-widest px-1">Para qual ocasião?</label>
            <input 
              type="text" 
              value={occasion}
              onChange={(e) => setOccasion(e.target.value)}
              placeholder="Ex: Jantar romântico, Reunião de negócios, Casamento no campo..."
              className="w-full bg-analux-contrast border-0 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-analux-secondary/30 transition-all"
            />
          </div>

          <div className="space-y-2">
            <label className="text-xs font-bold text-analux-primary uppercase tracking-widest px-1">Alguma preferência?</label>
            <input 
              type="text" 
              value={prefs}
              onChange={(e) => setPrefs(e.target.value)}
              placeholder="Ex: Gosto de dourado, pérolas, acessórios discretos..."
              className="w-full bg-analux-contrast border-0 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-analux-secondary/30 transition-all"
            />
          </div>

          <button 
            onClick={handleConsult}
            disabled={loading || !occasion}
            className="w-full bg-analux-primary text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-analux-dark transition-all shadow-xl shadow-analux-primary/20 disabled:opacity-50"
          >
            {loading ? <Loader2 className="animate-spin" size={20} /> : <Wand2 size={20} />}
            Obter Dica Exclusiva
          </button>
        </div>

        {advice && (
          <div className="mt-8 p-6 bg-analux-secondary/10 rounded-3xl border border-analux-secondary/20 animate-slideUp">
            <h4 className="font-serif text-analux-primary text-lg mb-2 flex items-center gap-2">
              <Sparkles size={18} className="text-analux-secondary" /> Dica da Analux:
            </h4>
            <p className="text-sm text-gray-700 leading-relaxed italic">{advice}</p>
          </div>
        )}
      </div>

      <p className="text-center text-[10px] text-gray-400 uppercase tracking-widest">
        Powered by Analux Smart Style AI
      </p>
    </div>
  );
};

export default StyleAI;
