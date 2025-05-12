import React, { useState, useEffect } from "react";
import CardExercice from "./../../components/Card/CardExercices";
import CardTemplate from "./../../components/Card/CardTemplate";

interface CardWrapperProps {
  type: "exercices" | "templates"; // Pour déterminer le type de cartes à afficher
}

const CardWrapper: React.FC<CardWrapperProps> = ({ type }) => {
  const [selectedCard, setSelectedCard] = useState<string | null>(null); // Suivi de la carte sélectionnée
  const [cards, setCards] = useState<any[]>([]); // État pour les cartes récupérées

  // Fonction pour récupérer les cartes selon le type
  const fetchCards = () => {
    const randomId = Math.floor(Math.random() * 10) + 1; // ID aléatoire pour l'API
    let apiUrl = "";

    if (type === "exercices") {
      apiUrl = `/exercises/${randomId}`;
    } else {
      apiUrl = `/templates/${randomId}`;
    }

    fetch(apiUrl)
      .then((res) => res.json())
      .then((data) => {
        setCards(data); // Remplir l'état avec les cartes reçues
      })
      .catch((err) => {
        console.error("Erreur de récupération:", err);
      });
  };

  useEffect(() => {
    fetchCards();
  }, [type]);

  return (
    <section className="card-list">
      {cards.length === 0 ? (
        <p>Chargement des cartes...</p>
      ) : (
        cards.map((card) => (
          <div
            key={card.id}
            className={`card ${selectedCard === card.id ? "selected" : ""}`}
            onClick={() => setSelectedCard(card.id)}
          >
          {type === "exercices" ? (
            <CardExercice
              exercise={card}
              isSelected={selectedCard === card.id}
              onSelect={() => setSelectedCard(card.id)}
            />
          ) : (
            <CardTemplate
            template={card}
            isSelected={selectedCard === card.id}
            onSelect={() => setSelectedCard(card.id)}
            />
          )}
          </div>
        ))
      )}
    </section>
  );
};

export default CardWrapper;
