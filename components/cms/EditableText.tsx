import React, { useState, useEffect, useRef } from 'react';

interface EditableTextProps {
  value: string;
  onChange: (newValue: string) => void;
  isEditing: boolean;
  className?: string;
  multiline?: boolean;
}

const EditableText: React.FC<EditableTextProps> = ({ 
  value, 
  onChange, 
  isEditing, 
  className = "", 
  multiline = false 
}) => {
  const [localValue, setLocalValue] = useState(value);
  const contentRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    setLocalValue(value);
  }, [value]);

  const handleBlur = () => {
    if (contentRef.current) {
      const newValue = contentRef.current.innerText;
      if (newValue !== value) {
        onChange(newValue);
      }
    }
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !multiline) {
      e.preventDefault();
      contentRef.current?.blur();
    }
  };

  if (!isEditing) {
    return <span className={className}>{value}</span>;
  }

  return (
    <div
      ref={contentRef}
      contentEditable
      suppressContentEditableWarning
      onBlur={handleBlur}
      onKeyDown={handleKeyDown}
      className={`${className} outline-none border-b border-dashed border-analux-gold-strong/40 focus:border-analux-gold-strong bg-analux-gold-soft/5 transition-all px-1 rounded-sm`}
    >
      {localValue}
    </div>
  );
};

export default EditableText;
