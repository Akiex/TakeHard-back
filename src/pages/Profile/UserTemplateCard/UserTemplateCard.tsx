import React from 'react';
import styles from './UserTemplateCard.module.scss';
import { Template, Exercise, Set } from './../../../types/typesCard';

interface Props {
  template: Template;
  isSelected: boolean;
  onSelect: () => void;
}

const UserTemplateCard: React.FC<Props> = ({ template, isSelected, onSelect }) => {
  if (!template || !template.sets?.length) {
    return <div className={styles.card}>Aucune donnée à afficher.</div>;
  }

  const exerciseMap: Record<number, { exercise: Exercise; sets: Set[] }> = {};
  template.sets.forEach((set) => {
    set.exercises.forEach((exercise) => {
      if (!exerciseMap[exercise.id]) {
        exerciseMap[exercise.id] = { exercise, sets: [] };
      }
      exerciseMap[exercise.id].sets.push(set);
    });
  });
  const exercisesWithSets = Object.values(exerciseMap);

  return (
    <div
      className={`${styles.card} ${isSelected ? styles.selected : ''}`}
      onClick={onSelect}
    >
      <h4 className={styles.title}>{template.name}</h4>
      <p className={styles.description}>{template.description}</p>

      {exercisesWithSets.map(({ exercise, sets }) => (
        <div key={exercise.id} className={styles.exerciseBlock}>
          <h5 className={styles.exerciseTitle}>{exercise.name}</h5>
          <ul className={styles.setList}>
            {sets.map((s) => (
              <li key={s.id} className={styles.setItem}>
                {s.sets}×{s.reps} : {s.weight}kg – repos {s.rest_time}s
              </li>
            ))}
          </ul>
        </div>
      ))}
    </div>
  );
};

export default UserTemplateCard;
