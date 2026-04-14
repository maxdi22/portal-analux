import React, { useState } from 'react';

interface UserAvatarProps {
    src?: string;
    alt?: string;
    className?: string;
    size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
}

const UserAvatar: React.FC<UserAvatarProps> = ({ src, alt = "User", className = "", size = 'md' }) => {
    const [error, setError] = useState(false);

    const sizeClasses = {
        sm: 'w-8 h-8',
        md: 'w-12 h-12',
        lg: 'w-24 h-24',
        xl: 'w-32 h-32',
        '2xl': 'w-full h-full' // For custom containers
    };

    // Artistic Fallback SVG (Elegant Woman Silhouette/Line Art)
    const FallbackAvatar = () => (
        <div className={`bg-analux-contrast flex items-center justify-center overflow-hidden ${className}`}>
            <svg viewBox="0 0 200 200" className="w-full h-full opacity-80" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="skin" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stopColor="#fdfbf7" />
                        <stop offset="100%" stopColor="#f5efe6" />
                    </linearGradient>
                </defs>
                <rect width="200" height="200" fill="url(#skin)" />

                {/* Abstract/Artistic Line Art Face */}
                <path
                    d="M100 160 C 60 160, 40 120, 40 80 C 40 40, 70 20, 100 20 C 130 20, 160 40, 160 80 C 160 120, 140 160, 100 160 Z"
                    fill="#f3e5dc"
                />
                {/* Hair Flow */}
                <path
                    d="M100 20 C 120 10, 170 30, 180 100 C 190 150, 160 180, 120 190"
                    fill="none"
                    stroke="#d4b89e"
                    strokeWidth="2"
                    strokeLinecap="round"
                />
                <path
                    d="M100 20 C 80 10, 30 30, 20 100 C 10 150, 40 180, 80 190"
                    fill="none"
                    stroke="#d4b89e"
                    strokeWidth="2"
                    strokeLinecap="round"
                />

                {/* Simple Features */}
                <path d="M70 90 Q 100 100 130 90" fill="none" stroke="#cfb29b" strokeWidth="2" strokeLinecap="round" />
            </svg>
        </div>
    );

    if (!src || error || src.includes('ui-avatars.com')) {
        // Check if it IS a ui-avatar url (often used as placeholder), we might want to override valid ui-avatars too if we prefer our art.
        // For now, let's treat ui-avatars as "valid" unless user wants to replace them too. 
        // Actually, user said "avatar representing a woman type", implying replacing the generic initials. 
        // If src is explicitly empty or error, show art.
        // If src is the default initial mock data (often ui-avatars), let's replace it? 
        // Let's stick to: if no src or error -> fallback.
        return <FallbackAvatar />;
    }

    return (
        <img
            src={src}
            alt={alt}
            className={`${size === '2xl' ? 'w-full h-full' : sizeClasses[size]} object-cover ${className}`}
            onError={() => setError(true)}
        />
    );
};

export default UserAvatar;
