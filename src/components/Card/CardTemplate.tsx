import React from 'react';
import styles from './Card.module.scss';
import { Template } from '../../types/typesCard';

interface Props {
  template: Template;
  isSelected: boolean;
  onSelect: () => void;
}

const CardTemplate: React.FC<Props> = ({ template, isSelected, onSelect }) => (
  <div
    className={`${styles.cardTemplate} ${isSelected ? styles.selected : ''}`}
    onClick={onSelect}
  >
    <h4>{template.title}</h4>
    <ul>
      {template.exercises.map((ex) => (
        <li key={ex.id}>
          <span className={styles.exerciseTitle}>{ex.name}</span>
          <ul>
            <li>{ex.sets} séries</li>
            <li>{ex.reps} reps</li>
            <li>{ex.rest} sec</li>
          </ul>
        </li>
      ))}
    </ul>
  </div>
);

export default CardTemplate;
