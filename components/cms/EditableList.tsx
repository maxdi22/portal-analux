import React, { useState } from 'react';
import * as Icons from 'lucide-react';
import { Plus, Trash2, ArrowUp, ArrowDown, Smile, Edit3, Check } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import IconPicker from './IconPicker';
import EditableText from './EditableText';

interface ListItem {
  id: string;
  icon: string;
  title: string;
  desc: string;
}

interface EditableListProps {
  items: ListItem[];
  onChange: (newItems: ListItem[]) => void;
  isEditing: boolean;
  renderItem: (item: ListItem, index: number) => React.ReactNode;
  containerClassName?: string;
}

const EditableList: React.FC<EditableListProps> = ({
  items = [],
  onChange,
  isEditing,
  renderItem,
  containerClassName = ""
}) => {
  const [editingItemId, setEditingItemId] = useState<string | null>(null);
  const [showIconPicker, setShowIconPicker] = useState<string | null>(null);

  if (!isEditing) {
    return (
      <div className={containerClassName}>
        {items.map((item, index) => renderItem(item, index))}
      </div>
    );
  }

  const handleAddItem = () => {
    const newItem: ListItem = {
      id: Math.random().toString(36).substring(2, 9),
      icon: 'Sparkles',
      title: 'Novo Tópico',
      desc: 'Descrição do tópico aqui.'
    };
    onChange([...items, newItem]);
  };

  const handleRemoveItem = (id: string, e: React.MouseEvent) => {
    e.stopPropagation();
    onChange(items.filter(item => item.id !== id));
  };

  const handleMove = (index: number, direction: 'up' | 'down', e: React.MouseEvent) => {
    e.stopPropagation();
    const newItems = [...items];
    const targetIndex = direction === 'up' ? index - 1 : index + 1;
    if (targetIndex < 0 || targetIndex >= items.length) return;
    
    [newItems[index], newItems[targetIndex]] = [newItems[targetIndex], newItems[index]];
    onChange(newItems);
  };

  const updateItem = (id: string, field: keyof ListItem, value: string) => {
    onChange(items.map(item => item.id === id ? { ...item, [field]: value } : item));
  };

  return (
    <div className={`${containerClassName} space-y-4`}>
      <AnimatePresence mode="popLayout">
        {items.map((item, index) => (
          <motion.div 
            key={item.id}
            layout
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, scale: 0.95 }}
            className="group relative"
          >
            {/* Control Bar (Absolute) */}
            <div className="absolute -left-12 top-0 bottom-0 flex flex-col items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
               <button 
                 onClick={(e) => handleMove(index, 'up', e)}
                 disabled={index === 0}
                 className="p-1.5 bg-white shadow-md rounded-lg text-slate-400 hover:text-analux-plum disabled:opacity-20"
               >
                 <ArrowUp size={14} />
               </button>
               <button 
                 onClick={(e) => handleMove(index, 'down', e)}
                 disabled={index === items.length - 1}
                 className="p-1.5 bg-white shadow-md rounded-lg text-slate-400 hover:text-analux-plum disabled:opacity-20"
               >
                 <ArrowDown size={14} />
               </button>
            </div>

            {/* Main Item Wrapper */}
            <div className={`relative rounded-3xl p-4 border-2 transition-all ${
              editingItemId === item.id 
                ? 'border-analux-gold-strong bg-analux-gold-soft/5 shadow-lg' 
                : 'border-white/0 hover:border-analux-gold-strong/20 hover:bg-white/5 shadow-none'
            }`}>
               
               <div className="flex gap-4">
                  {/* Icon Control */}
                  <div 
                    className="relative shrink-0 cursor-pointer"
                    onClick={() => setShowIconPicker(item.id)}
                  >
                     <div className="p-3 bg-analux-gold-soft/20 rounded-2xl text-analux-gold-strong group-hover:scale-110 transition-transform">
                        {React.createElement((Icons as any)[item.icon] || Icons.HelpCircle, { size: 24 })}
                     </div>
                     <div className="absolute -bottom-1 -right-1 bg-white shadow-md rounded-full p-1 text-[8px]">
                        <Edit3 size={10} />
                     </div>

                     {/* Icon Picker Popover */}
                     <AnimatePresence>
                        {showIconPicker === item.id && (
                          <div 
                            className="absolute top-0 left-full ml-4 z-[50] bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 w-80"
                            onClick={e => e.stopPropagation()}
                          >
                             <IconPicker 
                               currentIcon={item.icon}
                               onSelect={(name) => {
                                 updateItem(item.id, 'icon', name);
                                 setShowIconPicker(null);
                               }}
                               onClose={() => setShowIconPicker(null)}
                             />
                          </div>
                        )}
                     </AnimatePresence>
                  </div>

                  {/* Content Editing */}
                  <div className="flex-1 min-w-0">
                     <div className="flex justify-between items-start mb-1">
                        <EditableText 
                          value={item.title}
                          onChange={(v) => updateItem(item.id, 'title', v)}
                          isEditing={isEditing}
                          className="font-display text-2xl leading-tight block"
                        />
                        <button 
                          onClick={(e) => handleRemoveItem(item.id, e)}
                          className="p-1.5 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                        >
                          <Trash2 size={14} />
                        </button>
                     </div>
                     <EditableText 
                        value={item.desc}
                        onChange={(v) => updateItem(item.id, 'desc', v)}
                        isEditing={isEditing}
                        multiline
                        className="text-white/50 text-xs leading-relaxed block overflow-hidden"
                      />
                  </div>
               </div>
            </div>
          </motion.div>
        ))}
      </AnimatePresence>

      <motion.button
         whileHover={{ scale: 1.02 }}
         whileTap={{ scale: 0.98 }}
         onClick={handleAddItem}
         className="w-full py-4 border-2 border-dashed border-white/20 rounded-[2rem] text-white/40 font-bold uppercase tracking-widest text-[10px] flex items-center justify-center gap-2 hover:border-analux-gold-strong hover:text-analux-gold-strong hover:bg-analux-gold-strong/5 transition-all"
      >
        <Plus size={16} /> Adicionar Tópico
      </motion.button>
    </div>
  );
};

export default EditableList;
