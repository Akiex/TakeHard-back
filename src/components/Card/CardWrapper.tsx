// CardWrapper.tsx
import React, { useEffect, useState } from 'react';
import CardTemplate from './../../components/Card/CardTemplate';
import CardExercice from './../../components/Card/CardExercices';
import { Exercise, Template } from '../../types/typesCard';
import { API_BASE_URL, API_ENDPOINTS, getDefaultHeaders } from '../../config/apiConfig';

interface Props {
  selectedCard: number | null;
  setSelectedCard: (id: number | null) => void;
  type: 'exercice' | 'template'; // Type pour savoir si c'est un exercice ou un template
}

const CardWrapper: React.FC<Props> = ({ selectedCard, setSelectedCard, type }) => {
  const [items, setItems] = useState<Exercise[] | Template[]>([]); // Type dynamique basé sur 'type'
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  // Fonction pour récupérer les données (exercices ou templates)
  const fetchData = () => {
    const randomId = Math.floor(Math.random() * 10) + 1; // Exemple d'ID aléatoire entre 1 et 10
    const url = type === 'exercice'
      ? `${API_BASE_URL}${API_ENDPOINTS.getExercise(randomId)}`
      : `${API_BASE_URL}${API_ENDPOINTS.getTemplate(randomId)}`;
    
    fetch(url, {
      headers: getDefaultHeaders(),
    })
      .then((res) => res.json())
      .then((data) => {
        setItems([data]); // Ajouter l'élément récupéré
        setLoading(false);
      })
      .catch((err) => {
        setError('Erreur lors du chargement');
        setLoading(false);
      });
  };

  useEffect(() => {
    fetchData();
  }, [type]); // Re-fetch si le type change

  if (loading) return <p>Chargement...</p>;
  if (error) return <p style={{ color: 'red' }}>{error}</p>;

  // Affichage des cartes en fonction du type
  return (
    <section className="card-list">
      {items.map((item: Exercise | Template) => (
        <div key={item.id}>
          {type === 'exercice' ? (
            <CardExercice
              exercise={item as Exercise} // Type assertion
              isSelected={selectedCard === item.id}
              onSelect={() => setSelectedCard(item.id === selectedCard ? null : item.id)}
            />
          ) : (
            <CardTemplate
              template={item as Template} // Type assertion
              isSelected={selectedCard === item.id}
              onSelect={() => setSelectedCard(item.id === selectedCard ? null : item.id)}
            />
          )}
        </div>
      ))}
    </section>
  );
};

export default CardWrapper;
