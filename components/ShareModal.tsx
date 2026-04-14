import React, { useState } from 'react';
import { createPortal } from 'react-dom';
import {
    X,
    Copy,
    Check,
    MessageCircle,
    Facebook,
    Instagram,
    Smartphone, // Fallback for TikTok
    Image, // Fallback for Pinterest
    Share2
} from 'lucide-react';

interface ShareModalProps {
    referralCode: string;
    onClose: () => void;
}

const ShareModal: React.FC<ShareModalProps> = ({ referralCode, onClose }) => {
    const baseUrl = "https://box.analux.shop/convite";
    const shareLink = `${baseUrl}/${referralCode}`;

    const [copied, setCopied] = useState(false);
    const [message, setMessage] = useState(`Oi! Estou amando minha assinatura da Analux e consegui um cupom de R$ 20,00 pra você assinar também. Use o código ${referralCode} ou clica no link: ${shareLink}`);

    const handleCopy = () => {
        navigator.clipboard.writeText(shareLink);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const handleShare = (platform: string) => {
        let url = '';
        const encodedMsg = encodeURIComponent(message);
        const encodedLink = encodeURIComponent(shareLink);

        switch (platform) {
            case 'whatsapp':
                url = `https://wa.me/?text=${encodedMsg}`;
                break;
            case 'facebook':
                url = `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}`;
                break;
            case 'pinterest':
                url = `https://pinterest.com/pin/create/button/?url=${encodedLink}&description=${encodedMsg}`;
                break;
            case 'twitter': // Using as generic for now
                url = `https://twitter.com/intent/tweet?text=${encodedMsg}`;
                break;
            case 'email':
                url = `mailto:?subject=Um presente Analux pra você&body=${encodedMsg}`;
                break;
            default:
                // For Instagram/TikTok, usually requires native clipboard copy + open app
                navigator.clipboard.writeText(message);
                alert('Texto copiado! Abra o aplicativo para colar e compartilhar.');
                return;
        }
        window.open(url, '_blank', 'width=600,height=400');
    };

    return createPortal(
        <div className="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 animate-in fade-in duration-200">
            <div
                className="bg-white rounded-[32px] w-full max-w-lg shadow-2xl relative overflow-hidden flex flex-col animate-in zoom-in-95 duration-200"
                onClick={e => e.stopPropagation()}
            >
                {/* Header */}
                <div className="bg-analux-contrast p-6 text-center relative border-b border-gray-100">
                    <button
                        onClick={onClose}
                        className="absolute right-4 top-4 p-2 bg-white rounded-full text-gray-400 hover:text-gray-600 shadow-sm transition-colors"
                    >
                        <X size={20} />
                    </button>

                    <div className="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-analux-secondary mx-auto mb-4 shadow-sm">
                        <Share2 size={32} />
                    </div>
                    <h3 className="text-2xl font-serif text-analux-primary">Espalhe o Brilho</h3>
                    <p className="text-sm text-gray-500 mt-1">Convide amigas e ganhem benefícios juntas.</p>
                </div>

                {/* Body */}
                <div className="p-8 space-y-6">

                    {/* Link Box */}
                    <div>
                        <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Seu Link Exclusivo</label>
                        <div className="mt-2 flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 group focus-within:ring-2 ring-analux-secondary/20 transition-all">
                            <input
                                readOnly
                                value={shareLink}
                                className="bg-transparent border-none text-sm text-gray-600 w-full outline-none font-medium truncate"
                            />
                            <button
                                onClick={handleCopy}
                                className={`p-2 rounded-lg transition-all ${copied ? 'bg-green-500 text-white' : 'bg-white text-gray-500 hover:text-analux-primary shadow-sm'}`}
                            >
                                {copied ? <Check size={18} /> : <Copy size={18} />}
                            </button>
                        </div>
                    </div>

                    {/* Message Customization */}
                    <div>
                        <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1 mb-2 block">Personalizar Mensagem</label>
                        <textarea
                            value={message}
                            onChange={(e) => setMessage(e.target.value)}
                            className="w-full p-4 bg-white border border-gray-200 rounded-2xl text-sm text-gray-600 focus:border-analux-secondary focus:ring-1 focus:ring-analux-secondary/20 outline-none resize-none h-28 leading-relaxed"
                        />
                    </div>

                    {/* Social Icons Grid */}
                    <div>
                        <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1 mb-3 block">Compartilhar via</label>
                        <div className="grid grid-cols-5 gap-3">
                            <SocialButton
                                icon={<MessageCircle size={24} />}
                                color="bg-[#25D366]"
                                label="WhatsApp"
                                onClick={() => handleShare('whatsapp')}
                            />
                            <SocialButton
                                icon={<Instagram size={24} />}
                                color="bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888]"
                                label="Instagram"
                                onClick={() => handleShare('instagram')}
                            />
                            <SocialButton
                                icon={<Facebook size={24} />}
                                color="bg-[#1877F2]"
                                label="Facebook"
                                onClick={() => handleShare('facebook')}
                            />
                            <SocialButton
                                icon={<Image size={24} />} // Pinterest Icon placeholder
                                color="bg-[#E60023]"
                                label="Pinterest"
                                onClick={() => handleShare('pinterest')}
                            />
                            <SocialButton
                                icon={<Smartphone size={24} />} // TikTok/Mobile placeholder
                                color="bg-black"
                                label="TikTok"
                                onClick={() => handleShare('tiktok')}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>,
        document.body
    );
};

const SocialButton = ({ icon, color, label, onClick }: any) => (
    <button
        onClick={onClick}
        className="flex flex-col items-center gap-2 group"
    >
        <div className={`w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg transition-transform group-hover:-translate-y-1 ${color}`}>
            {icon}
        </div>
        <span className="text-[10px] text-gray-400 font-medium group-hover:text-analux-primary transition-colors">{label}</span>
    </button>
);

export default ShareModal;
