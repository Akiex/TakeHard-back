import React from "react";
import styles from "./Card.module.scss";
import { Exercise } from "../../types/typesCard";

interface Props {
  exercise: Exercise;
  isSelected: boolean;
  onSelect: () => void;
}
const src = {
  Pectoraux_Tricpes: "/cardAssets/Pectoraux_Tricpes.png",
  Dos_Biceps: "/cardAssets/Dos_Biceps.png",
  Abdominaux: "/cardAssets/Abdo.png",
  Jambes: "/cardAssets/jambes.png",
};

const getImageSrcFromDescription = (description: string): string | null => {
  if (description.toLowerCase().includes("triceps")
  || description.toLowerCase().includes("pectoraux")) {
    return src.Pectoraux_Tricpes;

  } else if (description.toLowerCase().includes("biceps")) {
    return src.Dos_Biceps;
  } else if (description.toLowerCase().includes("abdominaux")) {
    return src.Abdominaux;
  } else if (description.toLowerCase().includes("jambes")
    || description.toLowerCase().includes("fessiers")) {
    return src.Jambes;
  }
  return null;
};

const CardExercice: React.FC<Props> = ({ exercise, isSelected, onSelect }) => {
  const firstGroupName =
    exercise.muscle_groups && exercise.muscle_groups.length > 0
      ? exercise.muscle_groups[0].name
      : "Pas de groupe musculaire associé";

        const imageSrc = getImageSrcFromDescription(exercise.description || "");

  return (
    <section
      className={`${styles.Exercises} ${isSelected ? styles.selected : ""}`}
      onClick={onSelect}
    >
      <h4>{exercise.name || "Exercice sans nom"} </h4>
      <p>{exercise.description}</p>
      <p>Groupe musculaire: {firstGroupName}</p>
      {imageSrc && <img className={styles.imageBlock} src={imageSrc} alt="illustration groupe musculaire" />}
    </section>
  );
};

export default CardExercice;
