import React, { useState, useEffect } from 'react';
import { Play } from 'lucide-react';
import MoodPlayer, { Mood } from './MoodPlayer';
import { supabase } from '../services/supabase';

// Mock Data for "Daily" rotation (Fallback)
const MOODS: Mood[] = [
    {
        id: 'morning_glow',
        icon: '🌅',
        title: 'Morning Glow',
        description: 'Desacelerar, cuidar, silenciar.',
        quote: 'A manhã é o momento em que a alma se espreguiça.',
        audioUrl: 'https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3?filename=lofi-study-112778.mp3',
        color: 'from-orange-200'
    },
    {
        id: 'night_ritual',
        icon: '🌙',
        title: 'Night Ritual',
        description: 'Um momento para se reconectar consigo mesma.',
        quote: 'O silêncio da noite é o espelho da alma.',
        audioUrl: 'https://cdn.pixabay.com/download/audio/2022/03/10/audio_c8c8a73467.mp3?filename=relaxing-music-vol1-124477.mp3',
        color: 'from-indigo-900'
    },
    {
        id: 'creative_flow',
        icon: '✨',
        title: 'Creative Flow',
        description: 'Energia para criar e transformar.',
        quote: 'A criatividade é a inteligência se divertindo.',
        audioUrl: 'https://cdn.pixabay.com/download/audio/2022/01/18/audio_d0a13f69d2.mp3?filename=soft-ambient-10255.mp3',
        color: 'from-purple-300'
    },
    {
        id: 'sunday_reset',
        icon: '🛁',
        title: 'Sunday Reset',
        description: 'Renovar energias para a nova semana.',
        quote: 'O descanso não é o oposto do trabalho, é parte dele.',
        audioUrl: 'https://cdn.pixabay.com/audio/2022/02/07/audio_194f454456.mp3',
        color: 'from-blue-200'
    }
];

const DailyMoodCard: React.FC = () => {
    const [isPlaying, setIsPlaying] = useState(false);
    const [activeMood, setActiveMood] = useState<Mood>(MOODS[0]);

    useEffect(() => {
        // Initial static mood based on date/hour
        // For MVP, simplistic daily rotation logic
        const dayOfMonth = new Date().getDate();
        const baseMood = MOODS[dayOfMonth % MOODS.length];
        setActiveMood(baseMood); // Set initial visual state

        // Fetch dynamic audio override
        fetchMoodConfig(baseMood);
    }, []);

    const fetchMoodConfig = async (baseMood: Mood) => {
        try {
            const { data } = await supabase
                .from('app_settings')
                .select('value')
                .eq('key', 'mood_config')
                .single();

            if (data?.value) {
                const config = data.value;
                const assignedPlaylistId = config.active_assignments?.[baseMood.id];

                if (assignedPlaylistId) {
                    const playlist = config.playlists?.find((p: any) => p.id === assignedPlaylistId);
                    if (playlist && playlist.songs?.length > 0) {
                        // Pick random song from playlist or first one
                        const randomSong = playlist.songs[Math.floor(Math.random() * playlist.songs.length)];

                        setActiveMood(prev => ({
                            ...prev,
                            audioUrl: randomSong.url,
                            description: `${prev.description} (Tocando: ${randomSong.title})` // Optional: show track name
                        }));
                    }
                }
            }
        } catch (err) {
            console.error('Error fetching mood config', err);
        }
    };

    return (
        <>
            <div className="lg:col-span-2 bg-white p-5 rounded-3xl border border-gray-50 shadow-sm flex flex-col justify-between min-h-[140px] relative overflow-hidden group">
                {/* Ambient glow background */}
                <div className={`absolute top-0 right-0 w-32 h-32 bg-gradient-to-br ${activeMood.color} to-transparent opacity-20 blur-2xl rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-1000`}></div>

                <div className="relative z-10">
                    <div className="flex justify-between items-start mb-2">
                        <span className="text-xl filter grayscale group-hover:grayscale-0 transition-all duration-500 transform group-hover:scale-110">{activeMood.icon}</span>
                        <span className="text-[8px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded border border-gray-100">Mood</span>
                    </div>

                    <h3 className="text-sm font-bold text-analux-primary leading-tight mb-1">{activeMood.title}</h3>
                    <p className="text-[10px] text-gray-400 font-normal leading-tight line-clamp-2">
                        {activeMood.description}
                    </p>
                </div>

                <div className="mt-auto pt-3 relative z-10">
                    <button
                        onClick={() => setIsPlaying(true)}
                        className="w-full flex items-center justify-between group/btn"
                    >
                        <span className="text-[9px] font-bold uppercase tracking-widest text-analux-secondary group-hover/btn:text-analux-primary transition-colors">
                            Ouvir agora
                        </span>
                        <div className="w-6 h-6 rounded-full bg-analux-secondary/10 text-analux-secondary flex items-center justify-center group-hover/btn:bg-analux-secondary group-hover/btn:text-white transition-all shadow-sm">
                            <Play size={10} className="ml-0.5" fill="currentColor" />
                        </div>
                    </button>
                </div>
            </div>

            {isPlaying && (
                <MoodPlayer
                    currentMood={activeMood}
                    isOpen={isPlaying}
                    onClose={() => setIsPlaying(false)}
                    autoPlay
                />
            )}
        </>
    );
};

export default DailyMoodCard;
