import React, { useState, useEffect } from 'react';
import {
    MessageSquare,
    TrendingUp,
    ShieldAlert,
    Pin,
    Trash2,
    EyeOff,
    Send,
    Megaphone,
    CheckCircle2,
    BarChart3,
    MoreHorizontal,
    Search
} from 'lucide-react';
import { supabase } from '../../services/supabase';
import { ForumTopic } from '../../types';

interface PostStats {
    totalPosts: number;
    activeUsers: number;
    pendingReports: number;
    engagementRate: string;
}

const AdminCommunity: React.FC = () => {
    const [stats, setStats] = useState<PostStats>({
        totalPosts: 0,
        activeUsers: 0,
        pendingReports: 0,
        engagementRate: '0%'
    });
    const [posts, setPosts] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    
    // Announcement Form
    const [announcementTitle, setAnnouncementTitle] = useState('');
    const [announcementContent, setAnnouncementContent] = useState('');
    const [isPostingAnnouncement, setIsPostingAnnouncement] = useState(false);

    useEffect(() => {
        fetchData();
        
        const channel = supabase
            .channel('admin_community_changes')
            .on('postgres_changes', { event: '*', schema: 'public', table: 'forum_posts' }, () => {
                fetchData();
            })
            .subscribe();

        return () => {
            supabase.removeChannel(channel);
        };
    }, []);

    const fetchData = async () => {
        try {
            const { data: postsData, error: postsError } = await supabase
                .from('forum_posts')
                .select('*')
                .order('reports_count', { ascending: false })
                .order('created_at', { ascending: false });

            if (postsError) throw postsError;

            if (postsData) {
                setPosts(postsData);
                
                // Calculate basic stats
                const totalPosts = postsData.length;
                const reported = postsData.filter(p => p.reports_count > 0).length;
                const uniqueAuthors = new Set(postsData.map(p => p.author_id)).size;
                
                // Simplified engagement calculation
                const totalEngagements = postsData.reduce((acc, p) => acc + (p.likes || 0) + (p.replies || 0), 0);
                const rate = totalPosts > 0 ? (totalEngagements / totalPosts).toFixed(1) : '0';

                setStats({
                    totalPosts,
                    activeUsers: uniqueAuthors,
                    pendingReports: reported,
                    engagementRate: `${rate}/post`
                });
            }
        } catch (err) {
            console.error('Error fetching admin community data:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleDeletePost = async (id: string) => {
        if (!confirm('Tem certeza que deseja excluir esta postagem permanentemente?')) return;
        
        try {
            const { error } = await supabase.from('forum_posts').delete().eq('id', id);
            if (error) throw error;
        } catch (err) {
            alert('Erro ao excluir postagem.');
        }
    };

    const handleToggleHide = async (id: string, currentStatus: boolean) => {
        try {
            const { error } = await supabase
                .from('forum_posts')
                .update({ is_hidden: !currentStatus })
                .eq('id', id);
            if (error) throw error;
        } catch (err) {
            alert('Erro ao alterar visibilidade.');
        }
    };

    const handleTogglePin = async (id: string, currentStatus: boolean) => {
        try {
            const { error } = await supabase
                .from('forum_posts')
                .update({ is_pinned: !currentStatus })
                .eq('id', id);
            if (error) throw error;
        } catch (err) {
            alert('Erro ao fixar postagem.');
        }
    };

    const handlePostAnnouncement = async () => {
        if (!announcementTitle || !announcementContent) return;
        setIsPostingAnnouncement(true);

        try {
            const { error } = await supabase.from('forum_posts').insert({
                title: announcementTitle,
                content: announcementContent,
                category: 'Mural',
                is_official: true,
                is_pinned: true,
                author_name: 'Equipe Analux',
                author_badge: 'Staff'
            });

            if (error) throw error;

            setAnnouncementTitle('');
            setAnnouncementContent('');
            alert('Comunicado publicado com sucesso!');
        } catch (err) {
            alert('Erro ao publicar comunicado.');
        } finally {
            setIsPostingAnnouncement(false);
        }
    };

    const filteredPosts = posts.filter(p => 
        p.title.toLowerCase().includes(searchTerm.toLowerCase()) || 
        p.author_name.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="space-y-8 animate-fadeIn">
            <header className="flex justify-between items-end">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Gestão da Comunidade</h1>
                    <p className="text-slate-500 text-sm">Painel de Ops: Moderação, Anúncios e Analytics</p>
                </div>
            </header>

            {/* Dashboard Express */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                {[
                    { label: 'Total de Posts', value: stats.totalPosts, icon: <MessageSquare />, color: 'rose' },
                    { label: 'Musas Ativas', value: stats.activeUsers, icon: <TrendingUp />, color: 'purple' },
                    { label: 'Denúncias Pendentes', value: stats.pendingReports, icon: <ShieldAlert />, color: 'red' },
                    { label: 'Média Engajamento', value: stats.engagementRate, icon: <BarChart3 />, color: 'green' }
                ].map((kpi, i) => (
                    <div key={i} className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div className={`p-3 rounded-xl bg-${kpi.color}-50 text-${kpi.color}-600`}>
                            {kpi.icon}
                        </div>
                        <div>
                            <p className="text-xs font-semibold text-slate-400 uppercase tracking-widest leading-none mb-1">{kpi.label}</p>
                            <p className="text-2xl font-bold text-slate-800 leading-none">{kpi.value}</p>
                        </div>
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Official Announcement Form */}
                <div className="lg:col-span-1 bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-900/20 flex flex-col justify-between overflow-hidden relative group">
                    <Megaphone size={120} className="absolute -bottom-10 -right-10 text-white/5 -rotate-12 group-hover:scale-110 transition-transform duration-700" />
                    
                    <div className="relative z-10 space-y-6">
                        <div className="space-y-2">
                            <span className="text-[10px] font-bold text-amber-500 uppercase tracking-[0.3em]">Editor de Ops</span>
                            <h2 className="text-3xl font-serif italic">Comunicado Oficial.</h2>
                        </div>

                        <div className="space-y-4">
                            <input 
                                type="text"
                                placeholder="Título do anúncio..."
                                value={announcementTitle}
                                onChange={(e) => setAnnouncementTitle(e.target.value)}
                                className="w-full bg-white/10 border-0 rounded-xl px-4 py-3 placeholder:text-slate-500 text-sm focus:ring-2 focus:ring-amber-500/50 transition-all outline-none"
                            />
                            <textarea 
                                rows={4}
                                placeholder="Escreva a mensagem para as musas..."
                                value={announcementContent}
                                onChange={(e) => setAnnouncementContent(e.target.value)}
                                className="w-full bg-white/10 border-0 rounded-xl px-4 py-3 placeholder:text-slate-500 text-sm focus:ring-2 focus:ring-amber-500/50 transition-all outline-none resize-none"
                            />
                        </div>

                        <button 
                            onClick={handlePostAnnouncement}
                            disabled={isPostingAnnouncement || !announcementTitle || !announcementContent}
                            className="w-full bg-amber-500 text-slate-950 font-bold py-4 rounded-xl flex items-center justify-center gap-2 hover:bg-amber-400 transition-all active:scale-95 disabled:opacity-50"
                        >
                            <Send size={18} />
                            {isPostingAnnouncement ? 'Publicando...' : 'Publicar Agora'}
                        </button>
                    </div>
                </div>

                {/* Moderation List */}
                <div className="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col">
                    <div className="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <h3 className="font-bold text-slate-800 flex items-center gap-2">
                            <ShieldAlert size={18} className="text-red-500" />
                            Moderação Ativa
                        </h3>
                        <div className="relative w-full md:w-64">
                            <Search size={14} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" />
                            <input 
                                type="text" 
                                placeholder="Filtrar por título ou musa..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="w-full bg-slate-50 border-0 rounded-full py-2 pl-10 pr-4 text-xs focus:ring-2 focus:ring-slate-200 transition-all outline-none"
                            />
                        </div>
                    </div>

                    <div className="flex-1 overflow-x-auto">
                        <table className="w-full text-sm text-left">
                            <thead className="bg-slate-50 text-slate-500 font-bold uppercase tracking-widest text-[10px]">
                                <tr>
                                    <th className="px-6 py-4">Post/Musa</th>
                                    <th className="px-6 py-4 text-center">Denúncias</th>
                                    <th className="px-6 py-4 text-center">Status</th>
                                    <th className="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-50">
                                {filteredPosts.map(post => (
                                    <tr key={post.id} className="hover:bg-slate-50/50 transition-colors">
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <img src={post.author_avatar || `https://ui-avatars.com/api/?name=${post.author_name}`} className="w-8 h-8 rounded-lg object-cover" />
                                                <div>
                                                    <p className="font-bold text-slate-800 leading-none mb-1">{post.title}</p>
                                                    <p className="text-[10px] text-slate-400">por {post.author_name}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            {post.reports_count > 0 ? (
                                                <span className="bg-red-100 text-red-600 px-2 py-1 rounded-full text-[10px] font-bold">
                                                    {post.reports_count} {post.reports_count === 1 ? 'denúncia' : 'denúncias'}
                                                </span>
                                            ) : (
                                                <span className="text-slate-300">-</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <div className="flex items-center justify-center gap-1.5">
                                                {post.is_official && <span className="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]" title="Oficial"></span>}
                                                {post.is_pinned && <Pin size={12} className="text-rose-600 fill-rose-600" title="Fixado" />}
                                                {post.is_hidden && <EyeOff size={12} className="text-slate-400" title="Oculto" />}
                                                {!post.is_official && !post.is_pinned && !post.is_hidden && <CheckCircle2 size={12} className="text-green-500" title="Ativo" />}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <div className="flex items-center justify-end gap-2">
                                                <button 
                                                    onClick={() => handleTogglePin(post.id, post.is_pinned)}
                                                    className={`p-1.5 rounded-lg transition-colors ${post.is_pinned ? 'bg-rose-50 text-rose-600' : 'text-slate-400 hover:bg-slate-100'}`}
                                                    title="Fixar/Desafixar"
                                                >
                                                    <Pin size={14} />
                                                </button>
                                                <button 
                                                    onClick={() => handleToggleHide(post.id, post.is_hidden)}
                                                    className={`p-1.5 rounded-lg transition-colors ${post.is_hidden ? 'bg-slate-100 text-slate-600' : 'text-slate-400 hover:bg-slate-100'}`}
                                                    title="Ocultar/Exibir"
                                                >
                                                    <EyeOff size={14} />
                                                </button>
                                                <button 
                                                    onClick={() => handleDeletePost(post.id)}
                                                    className="p-1.5 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                                    title="Excluir"
                                                >
                                                    <Trash2 size={14} />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {filteredPosts.length === 0 && (
                            <div className="py-12 text-center text-slate-400">
                                <p>Nenhum post encontrado para esta busca.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AdminCommunity;
