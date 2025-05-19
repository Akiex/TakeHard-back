import React from 'react';
import styles from './Card.module.scss';
import { Template, Exercise, Set } from '../../types/typesCard';

interface Props {
  template: Template;
  isSelected: boolean;
  onSelect: () => void;
}

const CardTemplate: React.FC<Props> = ({ template, isSelected, onSelect }) => {
  if (!template || !template.sets?.length) {
    return <div>Aucune donnée à afficher.</div>;
  }

  // 1. Construire un map { exercice.id -> { exercice, sets: Set[] } }
  const exerciseMap: Record<number, { exercise: Exercise; sets: Set[] }> = {};

  template.sets.forEach((set) => {
    set.exercises.forEach((exercise) => {
      if (!exerciseMap[exercise.id]) {
        exerciseMap[exercise.id] = { exercise, sets: [] };
      }
      exerciseMap[exercise.id].sets.push(set);
    });
  });

  // 2. Transformer en tableau pour itérer dans le JSX
  const exercisesWithSets = Object.values(exerciseMap);

  return (
    <div
      className={` ${isSelected ? styles.selected : ''}`}
      onClick={onSelect}
    >
      <h4>{template.name}</h4>
      <p>{template.description}</p>

      {exercisesWithSets.map(({ exercise, sets }) => (
        <div key={exercise.id} className={styles.exerciseBlock}>
          <h5 className={styles.exerciseTitle}>{exercise.name}</h5>
          <ul className={styles.setList}>
            {sets.map((s) => (
              <li key={s.id} className={styles.setItem} style ={{listStyle: 'none'  }}>
                {s.sets}×{s.reps} : {s.weight}kg – repos {s.rest_time}s
              </li>
            ))}
          </ul>
        </div>
      ))}
    </div>
  );
};

export default CardTemplate;
