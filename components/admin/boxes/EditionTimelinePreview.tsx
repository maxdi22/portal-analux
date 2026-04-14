
import React from 'react';
import { Sparkles, Calendar, Gift, Lock, Play } from 'lucide-react';
import { BoxEdition } from '../../../types';

interface PreviewProps {
    data: {
        name: string;
        theme: string;
        month: string;
        manifesto: string;
        archetype: string;
        coverImage?: string;
    }
}

const EditionTimelinePreview: React.FC<PreviewProps> = ({ data }) => {
    return (
        <div className="bg-gray-50 rounded-2xl p-6 border border-gray-100">
            <div className="flex items-center gap-2 mb-4">
                <Sparkles size={16} className="text-analux-secondary" />
                <h4 className="text-xs font-bold uppercase tracking-widest text-gray-500">Sua Timeline (Visão da Cliente)</h4>
            </div>

            {/* Timeline Card Simulation */}
            <div className="bg-white rounded-[32px] overflow-hidden shadow-lg max-w-sm mx-auto transform transition-all hover:scale-[1.02]">
                {/* Cover Video/Image Area */}
                <div className="h-64 bg-gray-200 relative group">
                    {data.coverImage ? (
                        <img src={data.coverImage} className="w-full h-full object-cover" alt="Cover" />
                    ) : (
                        <div className="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                            <Play size={32} className="mb-2 opacity-50" />
                            <span className="text-[10px] uppercase tracking-widest">Teaser do Mês</span>
                        </div>
                    )}

                    {/* Overlay Gradient */}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>

                    {/* Floating Badge */}
                    <div className="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/30 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                        Em Breve
                    </div>

                    {/* Content */}
                    <div className="absolute bottom-0 left-0 p-6 text-white w-full">
                        <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-analux-secondary mb-2 block animate-pulse">
                            {data.month || 'Mês de Referência'}
                        </span>
                        <h3 className="text-3xl font-serif leading-tight mb-2">
                            {data.theme || 'Tema da Edição'}
                        </h3>
                        <p className="text-xs text-white/80 line-clamp-2 font-light leading-relaxed">
                            {data.manifesto || 'O manifesto da edição aparecerá aqui, conectando o arquétipo ao momento da cliente.'}
                        </p>
                    </div>
                </div>

                {/* Action Area */}
                <div className="p-5 bg-white">
                    <div className="flex justify-between items-center mb-4">
                        <div className="flex items-center gap-2">
                            <div className="w-8 h-8 rounded-full bg-analux-primary/5 flex items-center justify-center text-analux-primary">
                                <Calendar size={14} />
                            </div>
                            <div className="flex flex-col">
                                <span className="text-[9px] text-gray-400 uppercase font-bold">Envio</span>
                                <span className="text-xs font-bold text-gray-700">15/Dez</span>
                            </div>
                        </div>
                        <div className="h-8 w-px bg-gray-100"></div>
                        <div className="flex items-center gap-2">
                            <div className="w-8 h-8 rounded-full bg-analux-secondary/10 flex items-center justify-center text-analux-secondary">
                                <Gift size={14} />
                            </div>
                            <div className="flex flex-col">
                                <span className="text-[9px] text-gray-400 uppercase font-bold">Spoiler</span>
                                <span className="text-xs font-bold text-gray-700">Liberado</span>
                            </div>
                        </div>
                    </div>

                    <button className="w-full py-3 bg-gray-900 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 opacity-90">
                        <Lock size={12} />
                        Aguardando Lançamento
                    </button>
                </div>
            </div>

            <p className="text-center text-[10px] text-gray-400 mt-4 max-w-xs mx-auto">
                *Esta é uma prévia de como o card aparecerá no dashboard das assinantes durante a fase de teaser.
            </p>
        </div>
    );
};

export default EditionTimelinePreview;
