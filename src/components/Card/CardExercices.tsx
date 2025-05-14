import React from 'react';
import styles from './Card.module.scss';
import { Exercise } from '../../types/typesCard';

interface Props {
  exercise: Exercise;
  isSelected: boolean;
  onSelect: () => void;
}

const CardExercice: React.FC<Props> = ({ exercise, isSelected, onSelect }) => (
  <div
    className={`${styles.cardExercice} ${isSelected ? styles.selected : ''}`}
    onClick={onSelect}
  >
    <h4>{exercise.name}</h4>
    <p>{exercise.description}</p>
    <p>Groupe muscluaire: {exercise.muscle_groups[0].name}</p>
  </div>
);

export default CardExercice;
