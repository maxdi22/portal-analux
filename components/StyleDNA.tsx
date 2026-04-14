import React, { useState } from 'react';
import { useUser } from '../context/UserContext';
import { Sparkles, Edit2, Gem, Heart, Droplet, Ruler } from 'lucide-react';
import OnboardingModal from './OnboardingModal';

const StyleDNA: React.FC = () => {
    const { user, refreshData } = useUser();
    const [isEditing, setIsEditing] = useState(false);

    // Fallback if no profile
    if (!user.styleProfile) {
        return (
            <div className="flex flex-col items-center justify-center h-[60vh] text-center space-y-6 animate-fadeIn">
                <div className="w-20 h-20 bg-analux-secondary/10 rounded-full flex items-center justify-center text-analux-secondary">
                    <Sparkles size={40} />
                </div>
                <div>
                    <h2 className="text-2xl font-serif text-analux-primary">Seu DNA ainda não foi definido</h2>
                    <p className="text-gray-500 mt-2 max-w-md">Para que nossa curadoria acerte em cheio, precisamos conhecer seu estilo.</p>
                </div>
                <button
                    onClick={() => setIsEditing(true)}
                    className="bg-analux-primary text-white px-8 py-3 rounded-2xl font-bold uppercase text-xs tracking-widest hover:bg-analux-dark transition-all shadow-lg"
                >
                    Criar DNA de Estilo
                </button>
                {isEditing && (
                    <OnboardingModal
                        onClose={() => setIsEditing(false)}
                        onComplete={() => {
                            setIsEditing(false);
                            refreshData();
                        }}
                    />
                )}
            </div>
        );
    }

    const { styleProfile } = user;

    return (
        <div className="space-y-8 animate-fadeIn pb-20">
            {/* Header */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 className="text-3xl font-serif text-analux-primary flex items-center gap-3">
                        <Sparkles className="text-analux-secondary" size={28} />
                        Seu DNA de Estilo
                    </h1>
                    <p className="text-gray-500 text-sm mt-1">Sua identidade única que guia nossa curadoria.</p>
                </div>
                <button
                    onClick={() => setIsEditing(true)}
                    className="flex items-center gap-2 px-6 py-2.5 bg-white border border-analux-secondary/30 text-analux-primary rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-analux-primary hover:text-white transition-all shadow-sm group"
                >
                    <Edit2 size={14} className="group-hover:scale-110 transition-transform" />
                    Editar Perfil
                </button>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Card - Identity */}
                <div className="lg:col-span-1 bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 flex flex-col items-center text-center relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-32 h-32 bg-analux-secondary/5 rounded-full blur-2xl -mr-16 -mt-16"></div>

                    <div className="w-32 h-32 rounded-full p-1 bg-gradient-to-tr from-analux-secondary to-analux-primary mb-6">
                        <img
                            src={user.avatar}
                            alt={user.name}
                            className="w-full h-full rounded-full object-cover border-4 border-white"
                        />
                    </div>

                    <h2 className="text-2xl font-serif text-analux-primary font-bold">{styleProfile.fullName}</h2>
                    <p className="text-xs text-gray-400 uppercase tracking-widest mt-1 mb-6">Musa {user.level}</p>

                    <div className="w-full space-y-4">
                        <div className="bg-analux-contrast p-4 rounded-2xl border border-analux-secondary/10">
                            <span className="text-[10px] text-analux-secondary font-bold uppercase tracking-widest block mb-1">Estilo Principal</span>
                            <p className="text-lg font-serif text-analux-primary">{styleProfile.styles[0] || 'Não definido'}</p>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                <span className="text-[9px] text-gray-400 font-bold uppercase block mb-1">Signo</span>
                                <p className="text-sm font-bold text-gray-700">{styleProfile.sign || '-'}</p>
                            </div>
                            <div className="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                <span className="text-[9px] text-gray-400 font-bold uppercase block mb-1">Banho</span>
                                <p className="text-sm font-bold text-gray-700">{styleProfile.plating}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Detailed Preferences */}
                <div className="lg:col-span-2 space-y-6">

                    {/* Quick Stats Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-3 group hover:border-analux-secondary/30 transition-colors">
                            <div className="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center">
                                <Droplet size={20} />
                            </div>
                            <div>
                                <span className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Cores Favoritas</span>
                                <p className="text-sm font-bold text-gray-700 mt-1 line-clamp-2">{styleProfile.favoriteColors || '-'}</p>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-3 group hover:border-analux-secondary/30 transition-colors">
                            <div className="w-10 h-10 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center">
                                <Heart size={20} />
                            </div>
                            <div>
                                <span className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Elementos</span>
                                <p className="text-sm font-bold text-gray-700 mt-1 line-clamp-2">{styleProfile.elements.slice(0, 3).join(', ')}{styleProfile.elements.length > 3 ? '...' : ''}</p>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-3 group hover:border-analux-secondary/30 transition-colors">
                            <div className="w-10 h-10 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center">
                                <Ruler size={20} />
                            </div>
                            <div>
                                <span className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Tamanhos</span>
                                <p className="text-[11px] font-bold text-gray-700 mt-1">
                                    Anel: {styleProfile.ringSize || '-'} • Tornoz.: {styleProfile.ankletSize || '-'}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Detailed Lists */}
                    <div className="bg-white rounded-[40px] p-8 border border-gray-100 shadow-sm">
                        <h3 className="text-lg font-serif text-analux-primary mb-6 flex items-center gap-2">
                            <Gem size={20} className="text-analux-secondary" /> Preferências de Peças
                        </h3>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Colares</h4>
                                <div className="flex flex-wrap gap-2">
                                    {styleProfile.necklaces.map(item => (
                                        <span key={item} className="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium border border-gray-100">{item}</span>
                                    ))}
                                </div>
                            </div>

                            <div>
                                <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Brincos</h4>
                                <div className="flex flex-wrap gap-2">
                                    {styleProfile.earrings.map(item => (
                                        <span key={item} className="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium border border-gray-100">{item}</span>
                                    ))}
                                </div>
                            </div>

                            <div>
                                <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Pulseiras & Anéis</h4>
                                <div className="flex flex-wrap gap-2">
                                    {[...styleProfile.bracelets, ...styleProfile.rings].map(item => (
                                        <span key={item} className="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium border border-gray-100">{item}</span>
                                    ))}
                                </div>
                            </div>

                            <div>
                                <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Outros Detalhes</h4>
                                <ul className="space-y-2 text-sm text-gray-600">
                                    <li className="flex justify-between"><span className="text-gray-400">Religião:</span> <span className="font-medium">{styleProfile.religious.join(', ') || '-'}</span></li>
                                    <li className="flex justify-between"><span className="text-gray-400">Zircônia Colorida:</span> <span className="font-medium">{styleProfile.coloredZirconia ? styleProfile.coloredZirconiaColor : 'Não'}</span></li>
                                    <li className="flex justify-between"><span className="text-gray-400">Furos Orelha:</span> <span className="font-medium">{styleProfile.earringHoles}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {/* Observations */}
                    {styleProfile.observations && (
                        <div className="bg-analux-contrast/50 rounded-3xl p-6 border border-dashed border-analux-secondary/20">
                            <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-widest mb-2 block">Observações Especiais</span>
                            <p className="text-sm text-gray-600 italic">"{styleProfile.observations}"</p>
                        </div>
                    )}

                </div>
            </div>

            {isEditing && (
                <OnboardingModal
                    isEditing={true}
                    onClose={() => setIsEditing(false)}
                    onComplete={() => {
                        setIsEditing(false);
                        refreshData();
                        // Optional: Show success toast
                    }}
                />
            )}
        </div>
    );
};

export default StyleDNA;
