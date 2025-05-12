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
    <ul>
      <li>{exercise.sets} séries</li>
      <li>{exercise.reps} reps</li>
      <li>{exercise.rest} sec</li>
    </ul>
  </div>
);

export default CardExercice;
