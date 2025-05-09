// CardWrapper.jsx
import React, { useEffect, useRef } from 'react';
import Card from './Card';

const cards = [
  { id: 'template-0', title: 'Card 0' },
  { id: 'template-1', title: 'Card 1' },
  { id: 'template-2', title: 'Card 2' },
];

const CardWrapper = ({ selectedTemplate, setSelectedTemplate }) => {
  const wrapperRef = useRef(null);

  useEffect(() => {
    const handleClickOutside = (e) => {
      if (
        selectedTemplate &&
        wrapperRef.current &&
        !wrapperRef.current.contains(e.target)
      ) {
        setSelectedTemplate(null);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, [selectedTemplate, setSelectedTemplate]);

  return (
    <section ref={wrapperRef} className="card-list">
      {cards.map(({ id, title }) => (
        <Card
          key={id}
          id={id}
          title={title}
          isSelected={selectedTemplate === id}
          onSelect={() =>
            setSelectedTemplate(selectedTemplate === id ? null : id)
          }
        />
      ))}
    </section>
  );
};

export default CardWrapper;
