import React, { useState, useEffect, useRef } from 'react';
import { X, Volume2, VolumeX, Minimize2, Play, Pause, ExternalLink } from 'lucide-react';
import ReactPlayer from 'react-player';

// Keep the Mood interface export for other components
export interface Mood {
    id: string;
    icon: string;
    title: string;
    description: string;
    quote?: string;
    audioUrl: string;
    color: string;
}

interface MoodPlayerProps {
    currentMood: Mood; // Changed from 'mood' to 'currentMood' to match Parent
    isOpen: boolean;
    onClose: () => void;
    autoPlay?: boolean;
}

const MoodPlayer: React.FC<MoodPlayerProps> = ({ currentMood, isOpen, onClose, autoPlay = false }) => {
    const [isPlaying, setIsPlaying] = useState(false);
    const [isMuted, setIsMuted] = useState(false);
    const [isMinimized, setIsMinimized] = useState(false);
    const [progress, setProgress] = useState(0);
    const [duration, setDuration] = useState(0);
    const [isReady, setIsReady] = useState(false);
    
    // Reset state when opening/changing mood
    useEffect(() => {
        if (isOpen) {
            setIsPlaying(autoPlay);
            setProgress(0);
            setIsMinimized(false);
        } else {
            setIsPlaying(false);
        }
    }, [isOpen, currentMood, autoPlay]);

    if (!isOpen && !isMinimized) return null;

    // Mini Player Mode
    if (isMinimized && isOpen) {
        return (
            <div className={`fixed bottom-24 right-4 z-50 animate-slide-up`}>
                <div className={`bg-white rounded-full shadow-xl border border-gray-100 p-2 pr-4 flex items-center gap-3 relative overflow-hidden`}>
                     {/* Progress Ring (Visual Only for now) */}
                     <div className={`absolute top-0 bottom-0 left-0 bg-gradient-to-r ${currentMood.color} opacity-20 transition-all duration-1000`} style={{ width: `${(progress/duration)*100}%` }}></div>
                     
                     <button 
                        onClick={() => setIsPlaying(!isPlaying)}
                        className={`w-10 h-10 rounded-full bg-gradient-to-br ${currentMood.color} text-white flex items-center justify-center shadow-md z-10`}
                     >
                        {isPlaying ? <Pause size={18} fill="currentColor" /> : <Play size={18} fill="currentColor" className="ml-0.5" />}
                     </button>
                     
                     <div className="flex flex-col z-10 cursor-pointer" onClick={() => setIsMinimized(false)}>
                        <span className="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tocando agora</span>
                        <span className="text-xs font-bold text-analux-primary truncate max-w-[120px]">{currentMood.title}</span>
                     </div>
                     
                     <button onClick={onClose} className="text-gray-400 hover:text-red-400 z-10">
                        <X size={16} />
                     </button>
                     
                     {/* Hidden Player */}
                     <div className="hidden">
                        <ReactPlayer
                            url={currentMood.audioUrl}
                            playing={isPlaying}
                            muted={isMuted}
                            volume={0.8}
                            onProgress={({ playedSeconds }) => setProgress(playedSeconds)}
                            onDuration={setDuration}
                            onEnded={() => setIsPlaying(false)}
                            width="0"
                            height="0"
                            config={{
                                youtube: {
                                    playerVars: { showinfo: 0, controls: 0 }
                                }
                            }}
                        />
                     </div>
                </div>
            </div>
        );
    }

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
             {/* Backdrop */}
            <div 
                className="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-500"
                onClick={onClose}
            ></div>

            {/* Modal Card */}
            <div className="bg-white w-full max-w-md rounded-[48px] overflow-hidden shadow-2xl relative animate-scale-in">
                
                {/* Dynamic Background */}
                <div className={`absolute inset-0 bg-gradient-to-br ${currentMood.color} to-white opacity-20`}></div>
                
                {/* Content */}
                <div className="relative z-10 p-8 flex flex-col items-center text-center max-h-[90vh] overflow-y-auto">
                    
                    {/* Header Controls */}
                    <div className="w-full flex justify-between items-center mb-8">
                        <button onClick={() => setIsMinimized(true)} className="p-2 bg-white/50 hover:bg-white rounded-full transition-colors text-analux-primary">
                            <Minimize2 size={20} />
                        </button>
                        <span className="text-xs font-bold uppercase tracking-[0.2em] text-analux-primary/60">Mood de Hoje</span>
                        <button onClick={onClose} className="p-2 bg-white/50 hover:bg-white rounded-full transition-colors text-analux-primary">
                            <X size={20} />
                        </button>
                    </div>

                    {/* Visualizer / Icon */}
                    <div className="relative mb-8 group">
                         <div className={`absolute inset-0 bg-gradient-to-br ${currentMood.color} blur-3xl opacity-40 group-hover:opacity-60 transition-opacity duration-1000 animate-pulse`}></div>
                         <div className="relative w-40 h-40 bg-white rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                            <div className="text-6xl transform group-hover:scale-110 transition-transform duration-700">
                                {typeof currentMood.icon === 'string' ? currentMood.icon : '✨'}
                            </div>
                         </div>
                         {/* Spinning border if playing */}
                         {isPlaying && (
                             <div className="absolute inset-0 rounded-full border-2 border-analux-secondary border-t-transparent animate-spin ml-[-4px] mt-[-4px] w-[calc(100%+8px)] h-[calc(100%+8px)]"></div>
                         )}
                    </div>

                    {/* Track Info */}
                    <h2 className="text-3xl font-serif text-analux-primary mb-2 line-clamp-2">{currentMood.title}</h2>
                    <p className="text-sm text-gray-500 mb-6 px-4">{currentMood.description}</p>
                    
                    {currentMood.quote && (
                        <div className="mb-8 px-6 py-3 bg-white/60 rounded-2xl italic text-analux-secondary text-sm font-serif">
                            "{currentMood.quote}"
                        </div>
                    )}

                    {/* Player Controls */}
                    <div className="mt-auto w-full bg-white/80 backdrop-blur-md rounded-3xl p-4 border border-white shadow-sm">
                        
                        {/* Progress Bar (Visual only for valid duration) */}
                        <div className="w-full bg-gray-100 h-1.5 rounded-full mb-4 overflow-hidden relative cursor-pointer group/progress">
                             <div 
                                className={`h-full bg-gradient-to-r ${currentMood.color} transition-all duration-300`} 
                                style={{ width: duration > 0 ? `${(progress / duration) * 100}%` : '0%' }}
                             ></div>
                        </div>

                        <div className="flex items-center justify-between px-4">
                             <button onClick={() => setIsMuted(!isMuted)} className="text-gray-400 hover:text-analux-primary transition-colors">
                                {isMuted ? <VolumeX size={20} /> : <Volume2 size={20} />}
                             </button>

                             <button 
                                onClick={() => setIsPlaying(!isPlaying)}
                                className={`w-14 h-14 rounded-full bg-gradient-to-br ${currentMood.color} text-white flex items-center justify-center shadow-lg hover:scale-105 transition-transform`}
                             >
                                 {isPlaying ? <Pause size={28} fill="currentColor" /> : <Play size={28} fill="currentColor" className="ml-1" />}
                             </button>

                             <a 
                                href={currentMood.audioUrl} 
                                target="_blank" 
                                rel="noreferrer" 
                                className="text-gray-400 hover:text-analux-primary transition-colors"
                                title="Abrir original"
                             >
                                 <ExternalLink size={20} />
                             </a>
                        </div>
                    </div>

                    {/* THE PLAYER (Invisible but active) */}
                    <ReactPlayer
                        url={currentMood.audioUrl}
                        playing={isPlaying}
                        muted={isMuted}
                        volume={0.8}
                        onProgress={({ playedSeconds }) => setProgress(playedSeconds)}
                        onDuration={(d) => {
                            setDuration(d);
                            setIsReady(true);
                        }}
                        onEnded={() => setIsPlaying(false)}
                        width="0"
                        height="0"
                        style={{ display: 'none' }}
                        config={{
                            youtube: {
                                playerVars: { showinfo: 0, controls: 0 }
                            }
                        }}
                        onError={(e) => console.log('Player Error:', e)}
                    />

                </div>
            </div>
        </div>
    );
};

export default MoodPlayer;
