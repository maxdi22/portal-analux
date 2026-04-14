
import React, { useState } from 'react';
import { createPortal } from 'react-dom';
import {
  Heart,
  Share2,
  Pin,
  MessageCircle,
  Plus,
  Search,
  ShieldCheck,
  Award,
  Quote,
  Star,
  X,
  Send,
  Image as ImageIcon,
  Smile,
  CheckCircle2,
  TrendingUp,
  Camera,
  Trash2,
  ShieldAlert,
  Megaphone
} from 'lucide-react';
import { ForumTopic } from '../types';
import { useUser } from '../context/UserContext';

import { supabase } from '../services/supabase';
import EmojiPicker from 'emoji-picker-react';

const Community: React.FC = () => {
  const { user } = useUser();
  const [topics, setTopics] = useState<ForumTopic[]>([]);
  const [loading, setLoading] = useState(true);

  // Initial Fetch & Realtime Subscription
  React.useEffect(() => {
    fetchPosts();

    const channel = supabase
      .channel('forum_posts')
      .on('postgres_changes', { event: '*', schema: 'public', table: 'forum_posts' }, () => {
        fetchPosts();
      })
      .subscribe();

    return () => {
      supabase.removeChannel(channel);
    };
  }, []);

  const fetchPosts = async () => {
    try {
      const { data, error } = await supabase
        .from('forum_posts')
        .select('*')
        .order('created_at', { ascending: false });

      if (error) throw error;

      if (data) {
        const formattedTopics: ForumTopic[] = data.map(post => ({
          id: post.id,
          authorId: post.author_id,
          authorName: post.author_name,
          authorAvatar: post.author_avatar || 'https://i.pravatar.cc/150',
          authorBadge: post.author_badge as any, // Cast to match type
          title: post.title,
          content: post.content,
          category: post.category as any,
          likes: post.likes || 0,
          replies: post.replies || 0,
          images: post.images,
          isPinned: post.is_pinned,
          isOfficial: post.is_official,
          isHidden: post.is_hidden,
          reportsCount: post.reports_count,
          poll: post.poll,
          timestamp: new Date(post.created_at).toLocaleDateString('pt-BR', { hour: '2-digit', minute: '2-digit' }) // Simple formatting
        }));
        setTopics(formattedTopics);
      }
    } catch (err) {
      console.error('Error fetching posts:', err);
    } finally {
      setLoading(false);
    }
  };

  const [activeCategory, setActiveCategory] = useState('Todas');
  const [searchTerm, setSearchTerm] = useState('');
  const [isNewPostModalOpen, setIsNewPostModalOpen] = useState(false);
  const [newPostContent, setNewPostContent] = useState('');
  const [newPostTitle, setNewPostTitle] = useState('');
  const [newPostCategory, setNewPostCategory] = useState('Mural');
  const [votedPolls, setVotedPolls] = useState<Set<string>>(new Set());

  const [likedPosts, setLikedPosts] = useState<Set<string>>(new Set());

  // New features state
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
  const [showEmojiPicker, setShowEmojiPicker] = useState(false);
  const [isUploading, setIsUploading] = useState(false);

  const categories = ['Todas', 'Mural', 'Unboxing', 'Enquete', 'Estilo', 'Dúvidas'];

  const handleLike = async (id: string) => {
    const isLiked = likedPosts.has(id);
    const post = topics.find(t => t.id === id);
    if (!post) return;

    // Optimistic UI update
    const newLikes = isLiked ? post.likes - 1 : post.likes + 1;

    if (isLiked) {
      setLikedPosts(prev => {
        const next = new Set(prev);
        next.delete(id);
        return next;
      });
    } else {
      setLikedPosts(prev => new Set(prev).add(id));
    }

    setTopics(prev => prev.map(t => t.id === id ? { ...t, likes: newLikes } : t));

    // DB Update
    await supabase.from('forum_posts').update({ likes: newLikes }).eq('id', id);
  };

  const handleVote = (topicId: string, optionId: string) => {
    // Poll logic remains local for MVP as DB structure for polls is complex
    if (votedPolls.has(topicId)) return;
    setVotedPolls(prev => new Set(prev).add(topicId));
    setTopics(prev => prev.map(t => {
      if (t.id === topicId && t.poll) {
        return {
          ...t,
          poll: { ...t.poll, totalVotes: t.poll.totalVotes + 1, options: t.poll.options.map(opt => opt.id === optionId ? { ...opt, votes: opt.votes + 1 } : opt) }
        };
      }
      return t;
    }));
  };

  const handleReport = async (topicId: string) => {
    if (!confirm('Deseja denunciar esta postagem para moderação?')) return;

    try {
      // Create report record
      const { error: reportError } = await supabase.from('forum_reports').insert({
        post_id: topicId,
        reporter_id: user.id,
        reason: 'Reported by user'
      });

      if (reportError) throw reportError;

      // Increment reports count on post
      const post = topics.find(t => t.id === topicId);
      const newReportsCount = (post?.reportsCount || 0) + 1;

      const { error: postError } = await supabase
        .from('forum_posts')
        .update({ reports_count: newReportsCount })
        .eq('id', topicId);

      if (postError) throw postError;

      alert('Obrigada! Sua denúncia foi enviada para análise da equipe.');
    } catch (err) {
      console.error('Error reporting post:', err);
      alert('Erro ao enviar denúncia.');
    }
  };

  const handleFileSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      const newFiles = Array.from(e.target.files);
      setSelectedFiles(prev => [...prev, ...newFiles].slice(0, 4)); // Max 4 images
    }
  };

  const removeFile = (index: number) => {
    setSelectedFiles(prev => prev.filter((_, i) => i !== index));
  };

  const uploadImages = async (): Promise<string[]> => {
    if (selectedFiles.length === 0) return [];

    const urls: string[] = [];

    for (const file of selectedFiles) {
      const fileExt = file.name.split('.').pop();
      const fileName = `${Math.random().toString(36).substring(2)}_${Date.now()}.${fileExt}`;
      const filePath = `${fileName}`;

      const { error: uploadError } = await supabase.storage
        .from('forum-images')
        .upload(filePath, file);

      if (uploadError) {
        console.error('Error uploading image:', uploadError);
        continue;
      }

      const { data } = supabase.storage
        .from('forum-images')
        .getPublicUrl(filePath);

      if (data) urls.push(data.publicUrl);
    }
    return urls;
  };

  const handleCreatePost = async () => {
    if (!newPostTitle || !newPostContent) return;
    setIsUploading(true);

    try {
      const imageUrls = await uploadImages();

      const { error } = await supabase.from('forum_posts').insert({
        author_id: user.id || 'anonymous',
        author_name: user.name,
        author_avatar: user.avatar,
        author_badge: user.level,
        title: newPostTitle,
        content: newPostContent,
        category: newPostCategory,
        likes: 0,
        replies: 0,
        images: imageUrls,
        created_at: new Date().toISOString()
      });

      if (error) throw error;

      setNewPostTitle('');
      setNewPostContent('');
      setSelectedFiles([]);
      setIsNewPostModalOpen(false);
      // Fetch will happen automatically via realtime subscription
    } catch (err) {
      console.error('Error creating post:', err);
      alert('Erro ao criar post. Tente novamente.');
    } finally {
      setIsUploading(false);
    }
  };

  const handleDeletePost = async (id: string) => {
    if (!confirm('Tem certeza que deseja excluir esta postagem?')) return;

    try {
      const { error } = await supabase.from('forum_posts').delete().eq('id', id);
      if (error) throw error;

      // Realtime subscription should handle the removal, but we can optimistically update
      setTopics(prev => prev.filter(t => t.id !== id));
      setTopics(prev => prev.filter(t => t.id !== id));
    } catch (err) {
      console.error('Error deleting post:', err);
      alert('Erro ao excluir postagem.');
    }
  };

  const handleTogglePin = async (topic: ForumTopic) => {
    try {
      // Optimistic update
      const newStatus = !topic.isPinned;
      setTopics(prev => prev.map(t =>
        t.id === topic.id ? { ...t, isPinned: newStatus } : t
      ));

      // If we are pinning this one, we might want to unpin others? 
      // For now, let's just toggle this one. The UI will just pick the first pinned one.

      const { error } = await supabase
        .from('forum_posts')
        .update({ is_pinned: newStatus })
        .eq('id', topic.id);

      if (error) throw error;
    } catch (err) {
      console.error('Error pinning post:', err);
      // Revert optimistic update
      setTopics(prev => prev.map(t =>
        t.id === topic.id ? { ...t, isPinned: !topic.isPinned } : t
      ));
      alert('Erro ao fixar postagem.');
    }
  };

  const pinnedPost = topics.find(t => t.isPinned);

  const filteredTopics = topics.filter(t => {
    // Hide reported/hidden content from regular users
    if (t.isHidden && !user.isAdmin) return false;
    
    const matchesCategory = activeCategory === 'Todas' || t.category === activeCategory;
    const matchesSearch = t.title.toLowerCase().includes(searchTerm.toLowerCase()) || t.content.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  const renderPoll = (topicId: string, poll: NonNullable<ForumTopic['poll']>) => {
    const hasVoted = votedPolls.has(topicId);
    return (
      <div className="mt-6 p-8 bg-white/40 backdrop-blur-sm rounded-[40px] border border-analux-secondary/10 shadow-inner">
        <p className="text-base font-serif text-analux-primary mb-6">{poll.question}</p>
        <div className="space-y-4">
          {poll.options.map(opt => {
            const percentage = poll.totalVotes > 0 ? Math.round((opt.votes / poll.totalVotes) * 100) : 0;
            return (
              <div
                key={opt.id}
                onClick={() => handleVote(topicId, opt.id)}
                className={`relative group transition-all duration-500 rounded-2xl overflow-hidden ${hasVoted ? 'cursor-default' : 'cursor-pointer'}`}
              >
                <div className="flex justify-between text-[10px] font-bold uppercase tracking-widest mb-2 px-1 relative z-10">
                  <span className={hasVoted ? 'text-analux-primary' : 'text-gray-400 group-hover:text-analux-secondary'}>{opt.text}</span>
                  {hasVoted && <span className="text-analux-secondary">{percentage}%</span>}
                </div>
                <div className="h-4 bg-white rounded-full overflow-hidden relative shadow-sm border border-gray-50">
                  <div
                    className={`h-full transition-all duration-1000 ease-out ${hasVoted ? 'bg-analux-secondary' : 'bg-gray-100 group-hover:bg-analux-secondary/20'}`}
                    style={{ width: hasVoted ? `${percentage}%` : '0%' }}
                  ></div>
                </div>
              </div>
            );
          })}
        </div>
        {hasVoted && (
          <div className="mt-6 text-center">
            <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-[0.3em] flex items-center justify-center gap-2">
              <CheckCircle2 size={12} /> Sua opinião foi registrada
            </span>
          </div>
        )}
      </div>
    );
  };

  return (
    <div className="space-y-12 animate-fadeIn pb-32">
      {/* Header Editorial */}
      <header className="flex flex-col md:flex-row justify-between items-end gap-8 border-b border-analux-secondary/10 pb-12">
        <div className="space-y-4 max-w-2xl">
          <div className="flex items-center gap-3 text-analux-secondary">
            <TrendingUp size={18} />
            <span className="text-[10px] font-bold uppercase tracking-[0.4em]">Inside the Club</span>
          </div>
          <h1 className="text-6xl md:text-7xl font-serif text-analux-primary leading-tight italic">O Mural<br /><span className="text-analux-secondary not-italic">das Musas.</span></h1>
          <p className="text-gray-400 text-lg font-light leading-relaxed">
            Um diário visual e editorial compartilhado por quem faz da Analux sua marca registrada.
          </p>
        </div>
        <button
          onClick={() => setIsNewPostModalOpen(true)}
          className="bg-analux-primary text-white px-10 py-5 rounded-full font-bold flex items-center gap-3 shadow-2xl shadow-analux-primary/20 hover:scale-105 transition-all active:scale-95 group"
        >
          <span className="uppercase text-xs tracking-widest">Compartilhar Brilho</span>
          <Plus size={20} className="group-hover:rotate-90 transition-transform" />
        </button>
      </header>

      {/* Featured Magazine Banner */}
      <section className="relative h-96 rounded-[60px] overflow-hidden group shadow-2xl">
        {pinnedPost ? (
          <>
            {pinnedPost.images && pinnedPost.images.length > 0 ? (
              <img
                src={pinnedPost.images[0]}
                className="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 group-hover:scale-110 transition-all duration-[3000ms]"
                alt="Musa Feature"
              />
            ) : (
              <div className="w-full h-full bg-gradient-to-br from-analux-primary to-[#5b2d66]"></div>
            )}

            <div className="absolute inset-0 bg-gradient-to-r from-analux-primary/90 via-analux-primary/50 to-transparent mix-blend-multiply"></div>
            <div className="absolute inset-0 bg-gradient-to-t from-analux-primary/80 via-transparent to-transparent"></div>

            <div className="absolute inset-0 flex flex-col justify-center p-16 space-y-6 z-10">
              <div className="bg-[#C5A365] text-white w-fit px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] shadow-lg">
                Destaque da Semana
              </div>
              <h2 className="text-4xl md:text-6xl font-serif text-white max-w-2xl leading-tight drop-shadow-xl italic">
                "{pinnedPost.title}"
              </h2>
              {/* Content snippet if title is short? No, keep it clean like the design */}

              <div className="flex items-center gap-4 mt-4">
                <img src={pinnedPost.authorAvatar} className="w-16 h-16 rounded-full border-4 border-white/20 object-cover shadow-xl" />
                <div>
                  <p className="text-white font-bold text-lg tracking-wide">{pinnedPost.authorName}</p>
                  <p className="text-[#E3A483] text-[10px] font-bold uppercase tracking-widest">{pinnedPost.authorBadge}</p>
                </div>
              </div>
            </div>
            <Quote size={180} className="absolute -bottom-10 right-10 text-white/5 rotate-12 pointer-events-none" />
          </>
        ) : (
          /* Fallback Static Banner */
          <>
            <img
              src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=1200&h=600"
              className="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 group-hover:scale-110 transition-all duration-[3000ms]"
              alt="Musa Feature"
            />
            <div className="absolute inset-0 bg-gradient-to-r from-analux-primary/80 via-analux-primary/40 to-transparent"></div>
            <div className="absolute inset-0 flex flex-col justify-center p-16 space-y-6">
              <div className="bg-analux-secondary text-white w-fit px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] animate-pulse">
                Destaque da Semana
              </div>
              <h2 className="text-4xl md:text-5xl font-serif text-white max-w-lg leading-tight">"Joias que contam a história da minha própria evolução."</h2>
              <div className="flex items-center gap-4">
                <img src="https://i.pravatar.cc/150?u=leticia" className="w-12 h-12 rounded-2xl border-2 border-white/20 object-cover" />
                <div>
                  <p className="text-white font-bold text-sm tracking-wide">Letícia Montes</p>
                  <p className="text-analux-secondary text-[10px] font-bold uppercase">Musa Diamond • Assinante há 2 anos</p>
                </div>
              </div>
            </div>
            <Quote size={120} className="absolute bottom-0 right-10 text-white/5 -mb-10 rotate-12" />
          </>
        )}
      </section>

      {/* Filtering & Layout Controls */}
      <div className="sticky top-6 z-30 flex flex-col md:flex-row gap-6 items-center justify-between bg-analux-contrast/80 backdrop-blur-2xl p-4 rounded-[40px] border border-white shadow-xl shadow-analux-secondary/5">
        <div className="flex gap-2 overflow-x-auto w-full md:w-auto no-scrollbar scroll-smooth p-1">
          {categories.map(cat => (
            <button
              key={cat}
              onClick={() => setActiveCategory(cat)}
              className={`px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] transition-all whitespace-nowrap ${activeCategory === cat
                ? 'bg-analux-primary text-white shadow-lg scale-105'
                : 'text-gray-400 hover:text-analux-primary'
                }`}
            >
              {cat}
            </button>
          ))}
        </div>
        <div className="relative w-full md:w-72">
          <Search size={14} className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300" />
          <input
            type="text"
            placeholder="Pesquisar estilo..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full bg-white border-0 rounded-full py-4 pl-14 pr-6 text-xs shadow-inner focus:ring-2 focus:ring-analux-secondary/10 transition-all outline-none"
          />
        </div>
      </div>

      {/* Grid do Mural Editorial */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
        {filteredTopics.length > 0 ? filteredTopics.map((topic, idx) => (
          <article
            key={topic.id}
            onClick={() => {/* Navigate or expand */ }}
            className={`group bg-white rounded-[60px] overflow-hidden flex flex-col md:flex-row shadow-sm hover:shadow-2xl transition-all duration-700 border border-analux-secondary/5 relative ${topic.isOfficial ? 'border-amber-400 ring-2 ring-amber-400/20' : ''} ${idx % 3 === 0 ? 'md:col-span-2 md:flex-row' : ''} ${topic.isPinned ? 'border-2 border-analux-secondary/20 shadow-xl shadow-analux-secondary/5' : ''}`}
          >
            {topic.isHidden && (
              <div className="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex items-center justify-center p-10 text-center">
                <div className="bg-slate-900 text-white px-6 py-3 rounded-full flex items-center gap-3 shadow-xl">
                  <ShieldAlert size={18} className="text-amber-500" />
                  <span className="text-xs font-bold uppercase tracking-widest">Conteúdo Sob Análise</span>
                </div>
              </div>
            )}

            {topic.isOfficial && (
              <div className="absolute top-8 left-1/2 -translate-x-1/2 z-10">
                <div className="bg-amber-500 text-slate-950 px-4 py-1.5 rounded-full flex items-center gap-2 shadow-lg scale-90">
                  <Megaphone size={12} />
                  <span className="text-[9px] font-bold uppercase tracking-[0.2em]">Comunicado Analux</span>
                </div>
              </div>
            )}

            {/* Imagem / Poll Placeholder */}
            {(topic.images || topic.poll) && (
              <div className={`relative overflow-hidden ${idx % 3 === 0 ? 'md:w-5/12 h-96 md:h-[500px]' : 'h-80 md:h-[450px]'}`}>
                {topic.images ? (
                  <div className="h-full w-full relative">
                    <img src={topic.images[0]} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000" />
                    {topic.images.length > 1 && (
                      <div className="absolute bottom-6 right-6 bg-white/90 backdrop-blur-md p-3 rounded-2xl shadow-lg flex items-center gap-2">
                        <Camera size={14} className="text-analux-primary" />
                        <span className="text-[10px] font-bold text-analux-primary">+{topic.images.length - 1} fotos</span>
                      </div>
                    )}
                  </div>
                ) : (
                  <div className="h-full w-full bg-gradient-to-br from-analux-primary/5 to-analux-secondary/5 flex items-center justify-center">
                    <div className="w-full px-8">{topic.poll && renderPoll(topic.id, topic.poll)}</div>
                  </div>
                )}
                <div className="absolute top-6 left-6 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full shadow-sm">
                  <span className="text-[9px] font-bold text-analux-secondary uppercase tracking-widest">{topic.category}</span>
                </div>
              </div>
            )}

            {/* Conteúdo do Post */}
            <div className={`p-10 flex flex-col ${(!topic.images && !topic.poll) ? 'w-full' : (idx % 3 === 0 ? 'md:w-7/12' : 'flex-1')}`}>
              <div className="space-y-6">
                <div className="flex justify-between items-center">
                  <div className="flex items-center gap-4">
                    <img src={topic.authorAvatar} className="w-10 h-10 rounded-xl object-cover shadow-sm" />
                    <div>
                      <p className="text-xs font-bold text-analux-primary">{topic.authorName}</p>
                      <p className="text-[8px] font-bold text-analux-secondary uppercase tracking-wider">{topic.authorBadge}</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-2">
                    {!user.isAdmin && topic.isPinned && <Pin size={14} className="text-analux-secondary fill-analux-secondary" />}

                    {user.isAdmin && (
                      <button
                        onClick={(e) => { e.stopPropagation(); handleTogglePin(topic); }}
                        className={`transition-colors p-1 ${topic.isPinned ? 'text-analux-secondary' : 'text-gray-300 hover:text-analux-secondary'}`}
                        title={topic.isPinned ? "Remover destaque" : "Destacar postagem"}
                      >
                        <Pin size={14} className={topic.isPinned ? "fill-analux-secondary" : ""} />
                      </button>
                    )}

                    {(user.isAdmin || user.id === topic.authorId) && (
                      <button
                        onClick={(e) => { e.stopPropagation(); handleDeletePost(topic.id); }}
                        className="text-gray-300 hover:text-red-500 transition-colors p-1"
                        title="Excluir postagem"
                      >
                        <Trash2 size={14} />
                      </button>
                    )}

                    {!user.isAdmin && user.id !== topic.authorId && (
                      <button
                        onClick={(e) => { e.stopPropagation(); handleReport(topic.id); }}
                        className="text-gray-300 hover:text-red-500 transition-colors p-1"
                        title="Denunciar postagem"
                      >
                        <ShieldAlert size={14} />
                      </button>
                    )}
                  </div>
                </div>

                <div className="space-y-4">
                  <h3 className="text-3xl font-serif text-analux-primary leading-tight group-hover:text-analux-secondary transition-colors italic">
                    {topic.title}
                  </h3>
                  <p className="text-gray-400 text-sm leading-relaxed line-clamp-4 font-light">
                    {topic.content}
                  </p>
                </div>
              </div>

              <div className="flex items-center justify-between pt-10 mt-10 border-t border-gray-50">
                <div className="flex items-center gap-6">
                  <button
                    onClick={() => handleLike(topic.id)}
                    className={`flex items-center gap-2 text-[10px] font-bold transition-all uppercase tracking-widest ${likedPosts.has(topic.id) ? 'text-red-500' : 'text-gray-300 hover:text-red-400'}`}
                  >
                    <Heart size={18} className={likedPosts.has(topic.id) ? 'fill-red-500' : ''} /> {topic.likes}
                  </button>
                  <button className="flex items-center gap-2 text-[10px] font-bold text-gray-300 uppercase tracking-widest hover:text-analux-primary transition-all">
                    <MessageCircle size={18} /> {topic.replies}
                  </button>
                </div>
                <div className="flex items-center gap-4">
                  <span className="text-[9px] text-gray-300 font-bold uppercase tracking-widest">{topic.timestamp}</span>
                  <button className="text-gray-300 hover:text-analux-secondary transition-colors">
                    <Share2 size={16} />
                  </button>
                </div>
              </div>
            </div>
          </article>
        )) : (
          <div className="md:col-span-2 py-32 bg-white rounded-[60px] text-center border border-dashed border-gray-200">
            <div className="bg-analux-contrast w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200">
              <Search size={40} />
            </div>
            <h3 className="text-2xl font-serif text-analux-primary mb-3">Nenhum brilho capturado.</h3>
            <p className="text-gray-400 max-w-sm mx-auto font-light">Seja a primeira a postar e inspire outras musas da comunidade!</p>
          </div>
        )}
      </div>

      {/* Modal Editorial de Postagem */}
      {isNewPostModalOpen && createPortal(
        <div className="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-analux-primary/20 backdrop-blur-xl animate-fadeIn">
          <div className="bg-white w-full max-w-2xl rounded-[60px] shadow-[0_40px_120px_-30px_rgba(0,0,0,0.3)] p-12 relative animate-scaleIn">
            <button
              onClick={() => setIsNewPostModalOpen(false)}
              className="absolute top-10 right-10 p-3 text-gray-300 hover:text-analux-primary hover:bg-analux-contrast rounded-full transition-all"
            >
              <X size={24} />
            </button>

            <div className="mb-12 space-y-2">
              <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-[0.4em]">Novo Registro de Brilho</span>
              <h2 className="text-5xl font-serif text-analux-primary italic">Escreva sua História.</h2>
            </div>

            <div className="space-y-8">
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">Título do Editorial</label>
                <input
                  type="text"
                  value={newPostTitle}
                  onChange={(e) => setNewPostTitle(e.target.value)}
                  placeholder="Dê um nome marcante ao seu momento..."
                  className="w-full bg-analux-contrast border-0 rounded-[24px] py-5 px-8 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none transition-all placeholder:text-gray-300"
                />
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">Categoria</label>
                <div className="flex gap-2 flex-wrap">
                  {categories.filter(c => c !== 'Todas').map(cat => (
                    <button
                      key={cat}
                      onClick={() => setNewPostCategory(cat)}
                      className={`px-6 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all border ${newPostCategory === cat
                        ? 'bg-analux-primary text-white border-analux-primary'
                        : 'bg-white text-gray-400 border-gray-100 hover:border-analux-primary/30'
                        }`}
                    >
                      {cat}
                    </button>
                  ))}
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">Seu Relato</label>
                <textarea
                  rows={5}
                  value={newPostContent}
                  onChange={(e) => setNewPostContent(e.target.value)}
                  placeholder="Compartilhe como você se sente hoje..."
                  className="w-full bg-analux-contrast border-0 rounded-[24px] py-5 px-8 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none transition-all resize-none placeholder:text-gray-300"
                />
              </div>

              <div className="flex flex-col gap-4 py-4 border-y border-gray-50 relative">
                {/* Image Previews */}
                {selectedFiles.length > 0 && (
                  <div className="flex gap-3 overflow-x-auto pb-2">
                    {selectedFiles.map((file, i) => (
                      <div key={i} className="relative w-20 h-20 shrink-0 rounded-lg overflow-hidden border border-gray-100 group">
                        <img src={URL.createObjectURL(file)} className="w-full h-full object-cover" />
                        <button onClick={() => removeFile(i)} className="absolute top-1 right-1 bg-black/50 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                          <X size={12} />
                        </button>
                      </div>
                    ))}
                  </div>
                )}

                <div className="flex items-center gap-6">
                  <label className="flex items-center gap-2 text-[10px] font-bold text-analux-secondary uppercase tracking-widest hover:opacity-70 group cursor-pointer">
                    <div className="w-10 h-10 rounded-xl bg-analux-secondary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                      <ImageIcon size={18} />
                    </div>
                    Adicionar Fotos
                    <input type="file" multiple accept="image/*" onChange={handleFileSelect} className="hidden" />
                  </label>
                  <button
                    onClick={() => setShowEmojiPicker(!showEmojiPicker)}
                    className="flex items-center gap-2 text-[10px] font-bold text-analux-secondary uppercase tracking-widest hover:opacity-70 group"
                  >
                    <div className="w-10 h-10 rounded-xl bg-analux-secondary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                      <Smile size={18} />
                    </div>
                    Expressão
                  </button>
                </div>

                {showEmojiPicker && (
                  <div className="absolute top-full left-0 mt-2 z-50">
                    <div className="fixed inset-0" onClick={() => setShowEmojiPicker(false)}></div>
                    <div className="relative z-10">
                      <EmojiPicker onEmojiClick={(emojiData) => setNewPostContent(prev => prev + emojiData.emoji)} />
                    </div>
                  </div>
                )}
              </div>

              <div className="flex gap-4 pt-4">
                <button
                  onClick={handleCreatePost}
                  disabled={!newPostTitle || !newPostContent}
                  className="flex-1 py-6 bg-analux-primary text-white rounded-full font-bold flex items-center justify-center gap-3 shadow-2xl shadow-analux-primary/30 hover:scale-105 transition-all disabled:opacity-50 disabled:scale-100"
                >
                  <Send size={18} />
                  <span className="uppercase text-xs tracking-[0.2em]">{isUploading ? 'Publicando...' : 'Publicar no Mural'}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
        , document.body)}

      {/* Aesthetic Floating Hint */}
      <div className="fixed bottom-12 right-12 z-40 hidden lg:flex flex-col items-center gap-4 group">
        <div className="bg-white/90 backdrop-blur-md px-6 py-2 rounded-2xl shadow-2xl text-[10px] font-bold uppercase tracking-widest text-analux-primary opacity-0 group-hover:opacity-100 transition-all pointer-events-none translate-x-4 group-hover:translate-x-0 border border-gray-100">
          Inspire a Comunidade
        </div>
        <button
          onClick={() => setIsNewPostModalOpen(true)}
          className="w-20 h-20 bg-analux-secondary text-white rounded-full flex items-center justify-center shadow-[0_20px_50px_-10px_rgba(227,164,131,0.5)] hover:scale-110 active:scale-95 transition-all"
        >
          <Plus size={36} />
        </button>
      </div>

      <style>{`
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
      `}</style>
    </div>
  );
};

export default Community;
