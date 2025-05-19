import React, { useState, useEffect } from "react";
import CardExercice from "./../../components/Card/CardExercices";
import CardTemplate from "./../../components/Card/CardTemplate";
import { API_BASE_URL } from "./../../config/apiConfig";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import Slider from "react-slick";
import styles from "./Card.module.scss";
import CardModal from './cardModal';

interface CardWrapperProps {
  type: 'exercices' | 'templates';
}

const CardWrapper: React.FC<CardWrapperProps> = ({ type }) => {
  const [selectedCard, setSelectedCard] = useState<number | null>(null);
  const [cards, setCards] = useState<any[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  const settings = {
    centerMode: false,
    dots: true,
    infinite: true,
    autoplay: true,
    speed: 1000,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    adaptiveHeight: false,
    autoplaySpeed: 7000,
    cssEase: "linear",
    pauseOnHover: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  };

  const fetchCards = () => {
    setIsLoading(true);
    const apiUrl = type === "exercices" ? "/exercises" : "/templates";

    fetch(`${API_BASE_URL}${apiUrl}`)
      .then(res => {
        if (!res.ok) throw new Error(`Erreur HTTP: ${res.status}`);
        return res.json();
      })
      .then(data => {
        if (Array.isArray(data)) {
          setCards(data);
        } else {
          console.error("Données inattendues:", data);
          setError("Format de données inattendu.");
        }
      })
      .catch(err => {
        console.error("Erreur de récupération:", err);
        setError("Une erreur est survenue lors de la récupération des données.");
      })
      .finally(() => {
        setIsLoading(false);
      });
  };

  useEffect(() => {
    fetchCards();
  }, [type]);

  if (isLoading) return <p>Chargement des cartes...</p>;
  if (error) return <p>{error}</p>;
  if (cards.length === 0) return <p>Aucune carte trouvée.</p>;


     return (
    <>
      <Slider {...settings} className={styles.slider}>
        {cards.map(card => {
          const isSel = selectedCard === card.id;
          const onClick = () => setSelectedCard(isSel ? null : card.id);
          return (
            <div key={card.id} className={styles.slideWrapper}>
              <div
                className={`${styles.cardContainer}`}
                onClick={onClick}
              >
              {type === "exercices" ? (
                <CardExercice
                  exercise={card}
                  isSelected={isSel}
                  onSelect={onClick}
                />
              ) : (
                <CardTemplate
                  template={card}
                  isSelected={isSel}
                  onSelect={onClick}
                />
              )}
            </div>
            </div>
          );
        })}
      </Slider>

      {selectedCard !== null && (
        <CardModal
          card={cards.find(c => c.id === selectedCard)!}
          onClose={() => setSelectedCard(null)}
            cardType={type}
        />
      )}
    </>
  );
};

export default CardWrapper;

