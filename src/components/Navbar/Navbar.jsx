import { useState } from "react";
import styles from "./Navbar.module.scss";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <nav className={styles.navbar}>
      <button className={styles.burger} onClick={() => setIsOpen(!isOpen)}>
        &#9776;
      </button>
      <div className={`${styles.navMenu} ${isOpen ? styles.open : ""}`}>
        <ul>
          <li>
            <a href="Home">Home</a>
          </li>
          <li>
            <a href="Templates">Mes templates</a>
          </li>
        </ul>
        <ul>
          <li>
            <a href="Login">Se Connecter</a>
          </li>
          <li>
            <a href="Register">S'inscrire</a>
          </li>
          <li>
            <a href="BO">Admin</a>
          </li>
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
