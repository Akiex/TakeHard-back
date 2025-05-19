import ReactDOM from 'react-dom';
import styles from './CardModal.module.scss';
import Button from '../Button/Button';
import CardTemplate from './CardTemplate';
import CardExercice  from './CardExercices';

interface CardModalProps {
  card: any;
cardType: 'exercices' | 'templates';
  onClose: () => void;
}

export default function CardModal({ card, cardType, onClose }: CardModalProps) {
  return ReactDOM.createPortal(
    <div className={styles.modalBackdrop} onClick={onClose}>
      <div className={styles.modalContent} onClick={e => e.stopPropagation()}>
        {cardType === 'exercices'
          ? <CardExercice exercise={card} isSelected onSelect={onClose} />
          : <CardTemplate template={card} isSelected onSelect={onClose} />
        }
        <Button text="Fermer" onClick={onClose} />
      </div>
    </div>,
    document.body
  );
}
