import React from 'react';
import * as Icons from 'lucide-react';
import { X, Search } from 'lucide-react';
import { motion } from 'framer-motion';

interface IconPickerProps {
  currentIcon: string;
  onSelect: (iconName: string) => void;
  onClose: () => void;
}

const CURATED_ICONS = [
  'Gift', 'Sparkles', 'ShieldCheck', 'Star', 'Heart', 'Diamond', 
  'Crown', 'Gem', 'Package', 'Award', 'Camera', 'Eye', 
  'Fingerprint', 'Hand', 'History', 'Infinity', 'Lightbulb', 'MagicWand',
  'Moon', 'Music', 'Pencil', 'Redo', 'RefreshCw', 'Shield',
  'ShoppingBag', 'Smile', 'Sun', 'Target', 'Trophy', 'User',
  'Zap', 'Clock', 'Compass', 'Globe', 'MapPin', 'Phone'
];

const IconPicker: React.FC<IconPickerProps> = ({ currentIcon, onSelect, onClose }) => {
  const [searchTerm, setSearchTerm] = React.useState('');

  const filteredIcons = CURATED_ICONS.filter(name => 
    name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="flex flex-col gap-4 font-body">
      <div className="flex justify-between items-center">
        <h3 className="text-sm font-bold uppercase tracking-widest text-slate-800">Escolha um Ícone</h3>
        <button onClick={onClose} className="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
          <X size={18} />
        </button>
      </div>

      <div className="relative">
        <Search size={14} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
        <input 
          type="text"
          placeholder="Buscar ícone..."
          value={searchTerm}
          onChange={e => setSearchTerm(e.target.value)}
          className="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none focus:ring-1 focus:ring-analux-gold-strong transition-all"
        />
      </div>

      <div className="grid grid-cols-6 gap-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
        {filteredIcons.map(iconName => {
          const IconComponent = (Icons as any)[iconName];
          if (!IconComponent) return null;

          return (
            <button
              key={iconName}
              onClick={() => onSelect(iconName)}
              className={`p-3 rounded-xl flex items-center justify-center transition-all ${
                currentIcon === iconName 
                  ? 'bg-analux-plum text-white shadow-lg' 
                  : 'bg-slate-50 text-slate-600 hover:bg-analux-gold-soft/20 hover:text-analux-gold-strong'
              }`}
              title={iconName}
            >
              <IconComponent size={20} />
            </button>
          );
        })}
      </div>
    </div>
  );
};

export default IconPicker;
