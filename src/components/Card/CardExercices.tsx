import React from 'react';
import styles from './Card.module.scss';
import { Exercise } from '../../types/typesCard';

interface Props {
  exercise: Exercise;
  isSelected: boolean;
  onSelect: () => void;
}

const CardExercice: React.FC<Props> = ({ exercise, isSelected, onSelect }) => {
  // Sécurisation de l'accès au premier groupe musculaire
  const firstGroupName = exercise.muscle_groups && exercise.muscle_groups.length > 0
    ? exercise.muscle_groups[0].name
    : 'Pas de groupe';

  return (
    <div
      className={`${styles.cardExercice} ${isSelected ? styles.selected : ''}`}
      onClick={onSelect}
    >
      <h4>{exercise.name}</h4>
      <p>{exercise.description}</p>
      <p>Groupe musculaire: {firstGroupName}</p>
    </div>
  );
};

export default CardExercice;
