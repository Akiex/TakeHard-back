import styles from "./Button.module.scss";

const Button = ({ text, onClick, variant = "primary" }) => {
  return (
    <button className={styles[variant]} onClick={onClick}>
      {text}
    </button>
  );
};

export default Button;
