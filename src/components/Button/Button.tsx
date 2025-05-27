import styles from "./Button.module.scss";

type ButtonProps = {
  text: string;
  onClick?: () => void;
  variant?: "primary" | "secondary" | "danger";
  type?: "button" | "submit" | "reset";
  onKeyDown?: (event: React.KeyboardEvent<HTMLButtonElement>) => void;
};

const Button = ({ text, onClick, variant = "primary" }: ButtonProps) => {
  return (
    <button className={styles[variant]} onClick={onClick}>
      {text}
    </button>
  );
};

export default Button;
