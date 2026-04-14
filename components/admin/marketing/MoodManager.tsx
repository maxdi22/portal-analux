import React, { useState, useEffect } from 'react';
import { Plus, Trash2, Music, Save, Play, CheckCircle2, AlertCircle, LayoutList } from 'lucide-react';
import { supabase } from '../../../services/supabase';

// Types
type Song = {
    id: string;
    title: string;
    artist: string;
    url: string;
};

type Playlist = {
    id: string;
    name: string;
    songs: Song[];
};

type MoodAssignment = {
    [key: string]: string; // mood_id -> playlist_id
};

type MoodConfig = {
    playlists: Playlist[];
    active_assignments: MoodAssignment;
};

const MOOD_KEYS = [
    { id: 'morning_glow', label: 'Morning Glow (Manhã)' },
    { id: 'night_ritual', label: 'Night Ritual (Noite)' },
    { id: 'creative_flow', label: 'Creative Flow' },
    { id: 'sunday_reset', label: 'Sunday Reset' }
];

const MoodManager: React.FC = () => {
    const [config, setConfig] = useState<MoodConfig>({ playlists: [], active_assignments: {} });
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

    // Editor State
    const [editingPlaylistId, setEditingPlaylistId] = useState<string | null>(null);
    const [newPlaylistName, setNewPlaylistName] = useState('');

    // Song Editor State
    const [newSong, setNewSong] = useState({ title: '', artist: '', url: '' });

    useEffect(() => {
        fetchConfig();
    }, []);

    const fetchConfig = async () => {
        try {
            const { data, error } = await supabase
                .from('app_settings')
                .select('value')
                .eq('key', 'mood_config')
                .single();

            if (error && error.code !== 'PGRST116') throw error;

            if (data?.value) {
                // Ensure defaults if partial data
                const loaded = data.value as MoodConfig;
                setConfig({
                    playlists: loaded.playlists || [],
                    active_assignments: loaded.active_assignments || {}
                });
            }
        } catch (error) {
            console.error('Error loading mood config:', error);
            setMessage({ type: 'error', text: 'Erro ao carregar configurações.' });
        } finally {
            setIsLoading(false);
        }
    };

    const handleSave = async () => {
        setIsSaving(true);
        setMessage(null);
        try {
            const { error } = await supabase
                .from('app_settings')
                .upsert({
                    key: 'mood_config',
                    value: config,
                    updated_at: new Date().toISOString()
                });

            if (error) throw error;
            setMessage({ type: 'success', text: 'Playlists salvas com sucesso!' });
        } catch (error) {
            console.error('Error saving:', error);
            setMessage({ type: 'error', text: 'Erro ao salvar.' });
        } finally {
            setIsSaving(false);
        }
    };

    const addPlaylist = () => {
        if (!newPlaylistName.trim()) return;
        const newPlaylist: Playlist = {
            id: `pl_${Date.now()}`,
            name: newPlaylistName,
            songs: []
        };
        setConfig(prev => ({
            ...prev,
            playlists: [...prev.playlists, newPlaylist]
        }));
        setNewPlaylistName('');
        setEditingPlaylistId(newPlaylist.id);
    };

    const deletePlaylist = (id: string) => {
        if (!confirm('Excluir esta playlist?')) return;
        setConfig(prev => ({
            ...prev,
            playlists: prev.playlists.filter(p => p.id !== id),
            // Clean up assignments
            active_assignments: Object.fromEntries(
                Object.entries(prev.active_assignments).filter(([_, pid]) => pid !== id)
            )
        }));
        if (editingPlaylistId === id) setEditingPlaylistId(null);
    };

    const addSongToPlaylist = (playlistId: string) => {
        if (!newSong.title || !newSong.url) return;

        setConfig(prev => ({
            ...prev,
            playlists: prev.playlists.map(p => {
                if (p.id === playlistId) {
                    return {
                        ...p,
                        songs: [...p.songs, { ...newSong, id: `s_${Date.now()}` }]
                    };
                }
                return p;
            })
        }));
        setNewSong({ title: '', artist: '', url: '' });
    };

    const removeSong = (playlistId: string, songId: string) => {
        setConfig(prev => ({
            ...prev,
            playlists: prev.playlists.map(p => {
                if (p.id === playlistId) {
                    return {
                        ...p,
                        songs: p.songs.filter(s => s.id !== songId)
                    };
                }
                return p;
            })
        }));
    };

    const handleAssignmentChange = (moodId: string, playlistId: string) => {
        setConfig(prev => ({
            ...prev,
            active_assignments: {
                ...prev.active_assignments,
                [moodId]: playlistId
            }
        }));
    };

    if (isLoading) return <div className="text-center py-8 text-gray-500">Carregando gerenciador...</div>;

    return (
        <div className="space-y-8">
            <div className="flex items-center justify-between">
                <div>
                    <h2 className="text-2xl font-serif text-analux-primary">Moods & Playlists</h2>
                    <p className="text-sm text-gray-500">Crie playlists e atribua aos moods diários.</p>
                </div>
                <button
                    onClick={handleSave}
                    disabled={isSaving}
                    className="px-6 py-2 bg-analux-primary text-white rounded-xl font-bold flex items-center gap-2 hover:bg-analux-dark transition-colors disabled:opacity-50"
                >
                    {isSaving ? 'Salvando...' : 'Salvar Tudo'} <Save size={16} />
                </button>
            </div>

            {message && (
                <div className={`p-4 rounded-xl flex items-center gap-2 ${message.type === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'}`}>
                    {message.type === 'success' ? <CheckCircle2 size={18} /> : <AlertCircle size={18} />}
                    {message.text}
                </div>
            )}

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {/* LEFT COL: Playlist Management */}
                <div className="lg:col-span-2 space-y-6">
                    <div className="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                        <h3 className="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <LayoutList size={20} className="text-analux-secondary" /> Minhas Playlists
                        </h3>

                        {/* Create New */}
                        <div className="flex gap-2 mb-6">
                            <input
                                type="text"
                                placeholder="Nome da nova playlist..."
                                className="flex-1 px-4 py-2 rounded-xl bg-gra-50 border border-gray-200 focus:ring-2 focus:ring-analux-secondary/20 outline-none"
                                value={newPlaylistName}
                                onChange={e => setNewPlaylistName(e.target.value)}
                            />
                            <button
                                onClick={addPlaylist}
                                className="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-analux-secondary hover:text-white transition-colors"
                            >
                                <Plus size={20} />
                            </button>
                        </div>

                        {/* List */}
                        <div className="space-y-4">
                            {config.playlists.map(playlist => (
                                <div key={playlist.id} className={`border rounded-2xl overflow-hidden transition-all ${editingPlaylistId === playlist.id ? 'border-analux-secondary ring-1 ring-analux-secondary/20 bg-analux-secondary/5' : 'border-gray-100 hover:border-gray-200'}`}>

                                    {/* Header */}
                                    <div
                                        className="p-4 flex items-center justify-between cursor-pointer bg-white"
                                        onClick={() => setEditingPlaylistId(editingPlaylistId === playlist.id ? null : playlist.id)}
                                    >
                                        <div className="flex items-center gap-3">
                                            <div className="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                                <Music size={14} />
                                            </div>
                                            <div>
                                                <h4 className="font-bold text-gray-700">{playlist.name}</h4>
                                                <p className="text-xs text-gray-400">{playlist.songs.length} músicas</p>
                                            </div>
                                        </div>
                                        <button
                                            onClick={(e) => { e.stopPropagation(); deletePlaylist(playlist.id); }}
                                            className="p-2 text-gray-300 hover:text-red-400 transition-colors"
                                        >
                                            <Trash2 size={16} />
                                        </button>
                                    </div>

                                    {/* Songs Editor (Expanded) */}
                                    {editingPlaylistId === playlist.id && (
                                        <div className="p-4 border-t border-gray-100 bg-white/50">

                                            {/* Song List */}
                                            <div className="space-y-2 mb-4">
                                                {playlist.songs.length === 0 && <p className="text-xs text-gray-400 italic text-center py-2">Nenhuma música adicionada.</p>}
                                                {playlist.songs.map(song => (
                                                    <div key={song.id} className="flex items-center gap-2 text-sm group">
                                                        <Play size={12} className="text-gray-300" />
                                                        <span className="font-medium text-gray-700 flex-1">{song.title}</span>
                                                        {/* Show Icon based on URL type */}
                                                        {song.url.includes('youtu') ? <span className="text-[10px] bg-red-100 text-red-600 px-1 rounded">YT</span> : <span className="text-[10px] bg-rose-100 text-rose-600 px-1 rounded">MP3</span>}
                                                        <span className="text-gray-400 text-xs truncate max-w-[100px]">{song.artist}</span>
                                                        <button onClick={() => removeSong(playlist.id, song.id)} className="opacity-0 group-hover:opacity-100 text-red-400 p-1">
                                                            <Trash2 size={12} />
                                                        </button>
                                                    </div>
                                                ))}
                                            </div>

                                            {/* Add Song Form */}
                                            <div className="bg-gray-50 p-3 rounded-xl space-y-3">
                                                <div className="grid grid-cols-2 gap-2">
                                                    <input
                                                        placeholder="Título da Música..."
                                                        className="px-3 py-2 rounded-lg border-none text-xs bg-white shadow-sm"
                                                        value={newSong.title}
                                                        onChange={e => setNewSong({ ...newSong, title: e.target.value })}
                                                    />
                                                    <input
                                                        placeholder="Artista..."
                                                        className="px-3 py-2 rounded-lg border-none text-xs bg-white shadow-sm"
                                                        value={newSong.artist}
                                                        onChange={e => setNewSong({ ...newSong, artist: e.target.value })}
                                                    />
                                                </div>

                                                {/* Upload / Link Tabs */}
                                                <div className="bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                                                    <div className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Fonte do Áudio</div>

                                                    {/* MP3 Upload */}
                                                    <div className="mb-3">
                                                        <label className="flex items-center gap-2 cursor-pointer bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors group">
                                                            <div className="bg-analux-secondary/10 text-analux-secondary p-1.5 rounded-md group-hover:bg-analux-secondary group-hover:text-white transition-colors">
                                                                <Plus size={14} />
                                                            </div>
                                                            <div className="flex-1 overflow-hidden">
                                                                <div className="text-xs font-medium text-gray-600 truncate">Upload MP3</div>
                                                                <p className="text-[9px] text-gray-400">Clique para selecionar</p>
                                                            </div>
                                                            <input
                                                                type="file"
                                                                accept="audio/mp3,audio/mpeg"
                                                                className="hidden"
                                                                onChange={async (e) => {
                                                                    const file = e.target.files?.[0];
                                                                    if (!file) return;

                                                                    setMessage({ type: 'success', text: 'Carregando música...' }); // Temporary loading state reuse

                                                                    try {
                                                                        const fileName = `${Date.now()}_${file.name.replace(/\s+/g, '_')}`;
                                                                        const { data, error } = await supabase.storage
                                                                            .from('mood-music')
                                                                            .upload(fileName, file);

                                                                        if (error) throw error;

                                                                        const { data: publicData } = supabase.storage
                                                                            .from('mood-music')
                                                                            .getPublicUrl(fileName);

                                                                        setNewSong(prev => ({
                                                                            ...prev,
                                                                            url: publicData.publicUrl,
                                                                            title: prev.title || file.name.replace('.mp3', '') // Auto-fill title if empty
                                                                        }));
                                                                        setMessage({ type: 'success', text: 'Upload concluído!' });
                                                                    } catch (err) {
                                                                        console.error(err);
                                                                        setMessage({ type: 'error', text: 'Erro no upload. Verifique se o bucket "mood-music" existe.' });
                                                                    }
                                                                }}
                                                            />
                                                        </label>
                                                    </div>

                                                    <div className="flex items-center gap-2 mb-2 text-[10px] text-gray-300 font-bold uppercase justify-center before:flex-1 before:h-px before:bg-gray-100 after:flex-1 after:h-px after:bg-gray-100">OU Link</div>

                                                    {/* URL Input */}
                                                    <div className="flex bg-gray-50 rounded-lg p-1">
                                                        <input
                                                            placeholder="Cole link do YouTube ou MP3 direto..."
                                                            className="flex-1 px-2 py-1 bg-transparent border-none text-xs outline-none"
                                                            value={newSong.url}
                                                            onChange={e => setNewSong({ ...newSong, url: e.target.value })}
                                                        />
                                                    </div>
                                                    {newSong.url && (
                                                        <p className="text-[9px] text-green-600 mt-1 px-1 truncate">
                                                            Link pronto: {newSong.url.substring(0, 30)}...
                                                        </p>
                                                    )}
                                                </div>

                                                <button
                                                    onClick={() => addSongToPlaylist(playlist.id)}
                                                    disabled={!newSong.url || !newSong.title}
                                                    className="w-full py-2 bg-analux-secondary text-white rounded-lg text-xs font-bold hover:bg-analux-secondary/90 transition-colors disabled:opacity-50 shadow-md shadow-analux-secondary/20"
                                                >
                                                    Salvar na Playlist
                                                </button>
                                            </div>

                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* RIGHT COL: Assignments */}
                <div className="space-y-6">
                    <div className="bg-analux-contrast p-6 rounded-[32px] border border-analux-secondary/10">
                        <h3 className="text-lg font-bold text-analux-primary mb-4">Ativar nos Moods</h3>
                        <div className="space-y-4">
                            {MOOD_KEYS.map(mood => (
                                <div key={mood.id} className="bg-white p-4 rounded-2xl shadow-sm border border-gray-50">
                                    <label className="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">{mood.label}</label>
                                    <select
                                        className="w-full p-2 rounded-lg bg-gray-50 border-none text-sm font-medium text-gray-700 focus:ring-2 focus:ring-analux-secondary/20 outline-none"
                                        value={config.active_assignments[mood.id] || ''}
                                        onChange={(e) => handleAssignmentChange(mood.id, e.target.value)}
                                    >
                                        <option value="">-- Padrão (Sem Playlist) --</option>
                                        {config.playlists.map(p => (
                                            <option key={p.id} value={p.id}>{p.name}</option>
                                        ))}
                                    </select>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    );
};

export default MoodManager;
