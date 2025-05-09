// Card.jsx
import React from 'react';
import styles from './Card.module.scss';

const Card = ({ id, title, isSelected, onSelect }) => {
  return (
    <div
      className={`${styles.cardTemplate} ${
        isSelected ? styles.selected : ''
      }`}
      onClick={onSelect}
    >
      <h4>{title}</h4>
      <ul>
        <li>
          <span className={styles.exerciseTitle}>Squat</span>
          <ul>
            <li>4 séries</li>
            <li>12 reps</li>
            <li>90 sec</li>
          </ul>
        </li>
        <li>
          <span className={styles.exerciseTitle}>Développé couché</span>
          <ul>
            <li>3 séries</li>
            <li>10 reps</li>
            <li>60 sec</li>
          </ul>
        </li>
      </ul>
    </div>
  );
};

export default Card;
