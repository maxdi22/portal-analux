
import React, { useState } from 'react';
import { useUser } from '../context/UserContext';
import { Gift, Award, Lock, ArrowRight, Zap, Crown } from 'lucide-react';

const REWARDS_CATALOG = [
    {
        id: 'r1',
        name: 'Guia de Estilo 2026 (Ebook)',
        points: 500,
        image: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=1000',
        type: 'digital',
        description: 'Descubra as tendências que vão brilhar este ano.'
    },
    {
        id: 'r2',
        name: 'Frete Grátis (Prox. Pedido)',
        points: 1500,
        image: 'https://images.unsplash.com/photo-1629904853023-535697c555fb?auto=format&fit=crop&q=80&w=1000',
        type: 'coupon',
        description: 'Válido para qualquer compra acima de R$ 100.'
    },
    {
        id: 'r3',
        name: 'Porta-Semijoias de Veludo',
        points: 3000,
        image: 'https://images.unsplash.com/photo-1589128777090-f963ac56930d?auto=format&fit=crop&q=80&w=1000',
        type: 'physical',
        description: 'Mantenha suas peças organizadas e seguras.'
    },
    {
        id: 'r4',
        name: 'Colar Ponto de Luz (Ouro 18k)',
        points: 10000,
        image: 'https://images.unsplash.com/photo-1599643478518-17488fbbcd75?auto=format&fit=crop&q=80&w=1000',
        type: 'physical',
        description: 'Uma semijoia eterna para celebrar sua jornada.'
    }
];

const LoyaltyClub: React.FC = () => {
    const { user } = useUser();
    const [selectedReward, setSelectedReward] = useState<string | null>(null);

    const calculateProgress = () => {
        // Simple mock progress logic: Level up every 5000 points
        const currentPoints = user.lifetimePoints || user.points || 0;
        const nextLevel = Math.ceil((currentPoints + 1) / 5000) * 5000;
        const progress = (currentPoints % 5000) / 5000 * 100;
        return { progress, nextLevel, remaining: nextLevel - currentPoints };
    };

    const { progress, nextLevel, remaining } = calculateProgress();

    const handleRedeem = (rewardId: string) => {
        // MOCK REDEMPTION
        alert(`Redemption logic would trigger here for ${rewardId}. (Requires backend)`);
    };

    return (
        <div className="animate-fadeIn space-y-8">
            {/* Header / Stats */}
            <div className="relative overflow-hidden rounded-[40px] bg-gradient-to-br from-analux-secondary to-[#cba68b] p-8 text-white shadow-xl">
                <div className="absolute top-0 right-0 p-12 opacity-10">
                    <Crown size={180} className="rotate-12" />
                </div>

                <div className="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div className="text-center md:text-left">
                        <p className="text-analux-contrast/80 font-bold uppercase tracking-widest text-xs mb-2">Clube AnaLux Rewards</p>
                        <div className="flex items-baseline gap-2 justify-center md:justify-start">
                            <h1 className="text-6xl font-serif">{user.points}</h1>
                            <span className="text-lg font-medium text-analux-contrast">pts</span>
                        </div>
                        <p className="mt-2 text-sm text-white/90">Saldo disponível para troca</p>
                    </div>

                    <div className="w-full md:w-1/2 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20">
                        <div className="flex justify-between items-end mb-2">
                            <span className="text-xs font-bold uppercase tracking-wider">Próximo Nível</span>
                            <span className="text-xs opacity-80">{user.lifetimePoints || user.points} / {nextLevel} pts</span>
                        </div>
                        <div className="w-full h-2 bg-black/20 rounded-full overflow-hidden mb-3">
                            <div
                                className="h-full bg-white rounded-full transition-all duration-1000 ease-out"
                                style={{ width: `${progress}%` }}
                            />
                        </div>
                        <p className="text-xs italic opacity-90">
                            Faltam apenas <b>{remaining} pontos</b> para você subir de nível e desbloquear benefícios exclusivos.
                        </p>
                    </div>
                </div>
            </div>

            {/* Catalog */}
            <div>
                <h3 className="text-2xl font-serif text-analux-primary mb-6 flex items-center gap-2">
                    <Gift size={24} className="text-analux-secondary" />
                    Catálogo de Recompensas
                </h3>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {REWARDS_CATALOG.map(reward => {
                        const canAfford = user.points >= reward.points;
                        return (
                            <div key={reward.id} className={`group bg-white rounded-[32px] overflow-hidden border transition-all duration-300 flex flex-col ${canAfford ? 'border-gray-100 hover:shadow-xl' : 'border-gray-100 opacity-70 grayscale-[0.5] hover:grayscale-0'}`}>
                                <div className="aspect-square relative overflow-hidden">
                                    <img src={reward.image} alt={reward.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                    <div className="absolute top-3 right-3 bg-black/50 backdrop-blur text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                        {reward.type}
                                    </div>
                                </div>
                                <div className="p-5 flex-1 flex flex-col">
                                    <h4 className="font-bold text-analux-primary mb-1 line-clamp-1">{reward.name}</h4>
                                    <p className="text-xs text-gray-500 mb-4 line-clamp-2 flex-1">{reward.description}</p>

                                    <div className="flex items-center justify-between mt-auto">
                                        <span className={`text-sm font-bold ${canAfford ? 'text-analux-secondary' : 'text-gray-400'}`}>
                                            {reward.points} pts
                                        </span>
                                        <button
                                            onClick={() => canAfford && handleRedeem(reward.id)}
                                            disabled={!canAfford}
                                            className={`w-10 h-10 rounded-full flex items-center justify-center transition-all ${canAfford
                                                    ? 'bg-analux-primary text-white hover:bg-analux-secondary hover:scale-105'
                                                    : 'bg-gray-100 text-gray-300 cursor-not-allowed'
                                                }`}
                                        >
                                            {canAfford ? <ArrowRight size={18} /> : <Lock size={16} />}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
};

export default LoyaltyClub;
