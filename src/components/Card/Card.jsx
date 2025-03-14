import styles from "./Card.module.scss";

const Card = () => {
  return (
    <div className={styles.cardTemplate}>
      <h4>Card</h4>
      <ul>
        <ul>
          Squat
          <li>4 série</li>
          <li>12 rep</li>
          <li>90 sc </li>
        </ul>
        <li>2</li>
        <li>3</li>
        <li>4</li>
      </ul>
    </div>
  );
};
export default Card;
