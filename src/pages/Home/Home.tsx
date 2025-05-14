import React, { useState } from 'react';
import CardWrapper from '../../components/Card/CardWrapper';
import ErrorBoundary  from './../../utils/ErrorBoundary';
const Home: React.FC = () => {
  // Deux états séparés :
  const [selectedTemplate, setSelectedTemplate] = useState<number | null>(null);
  const [selectedExercise, setSelectedExercise] = useState<number | null>(null);

  return (
    <>
      <ErrorBoundary>

      <section className="Templates">
        <h2>Templates à la une</h2>
        <CardWrapper
          type="templates"
          selectedCard={selectedTemplate}
          setSelectedCard={setSelectedTemplate}
        />
      </section>

      <section className="Exercices">
        <h2>Exercices à la une</h2>
        <CardWrapper
          type="exercices"
          selectedCard={selectedExercise}
          setSelectedCard={setSelectedExercise}
        />
      </section>

      </ErrorBoundary>
    </>
  );
};

export default Home;
