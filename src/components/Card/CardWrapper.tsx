import React, { useState, useEffect } from "react";
import CardExercice from "./../../components/Card/CardExercices";
import CardTemplate from "./../../components/Card/CardTemplate";
import { API_BASE_URL } from "./../../config/apiConfig";
import styles from "./Card.module.scss";
interface CardWrapperProps {
  type: 'exercices' | 'templates';
  selectedCard: number | null;
  setSelectedCard: (id: number) => void;
}

const CardWrapper: React.FC<CardWrapperProps> = ({ type }) => {
  const [selectedCard, setSelectedCard] = useState<number | null>(null); // Suivi de la carte sélectionnée
  const [cards, setCards] = useState<any[]>([]); // État pour les cartes récupérées
  const [isLoading, setIsLoading] = useState<boolean>(true); // Pour afficher un message de chargement
  const [error, setError] = useState<string | null>(null); // Pour gérer les erreurs

  // Fonction pour récupérer les cartes selon le type
  const fetchCards = () => {

    let apiUrl = "";

    if (type === "exercices") {
      apiUrl = `/exercises`;
    } else {
      apiUrl = `/templates`;
    }
  console.log("Fetching from:", `${API_BASE_URL}${apiUrl}`);
fetch(`${API_BASE_URL}${apiUrl}`)
  .then(res => {
    if (!res.ok) throw new Error(`Erreur HTTP: ${res.status}`);
    return res.json();
  })
  .then(data => {
    console.log("Données reçues :", data); // Vérifie ici
    if (Array.isArray(data)) {
      setCards(data);
    } else {
      console.error("Données inattendues:", data);
    }
  })
  .catch(err => {
    setError("Une erreur est survenue lors de la récupération des données.");
    console.error("Erreur de récupération:", err);
  })
  .finally(() => {
    setIsLoading(false);
  });
  };

  useEffect(() => {
    fetchCards();
  }, [type]);

  return (
    <section className="card-list">
      {isLoading ? (
        <p>Chargement des cartes...</p>
      ) : error ? (
        <p>{error}</p>
      ) : cards.length === 0 ? (
        <p>Aucune carte trouvée.</p>
      ) : (
        cards.map((card) => (
          <div
            key={card.id}
            className={`cardTemplate card ${selectedCard === card.id ? "selected" : ""}`}
            onClick={() => setSelectedCard(selectedCard === card.id ? null : card.id)}
          >
            {type === "exercices" ? (
              <CardExercice
                exercise={card}
                isSelected={selectedCard === card.id}
                onSelect={() => setSelectedCard(selectedCard === card.id ? null : card.id)}
              />
            ) : (
              <CardTemplate
                template={card}
                isSelected={selectedCard === card.id}
                onSelect={() => setSelectedCard(selectedCard === card.id ? null : card.id)}
              />
            )}
          </div>
        ))
      )}
    </section>
  );
};

export default CardWrapper;
