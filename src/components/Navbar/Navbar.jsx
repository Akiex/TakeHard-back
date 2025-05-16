import { useState } from "react";
import styles from "./Navbar.module.scss";
import { useAuth } from "../../context/AuthContext";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { isConnected, isAdmin, logout } = useAuth();
  return (
    <nav className={styles.navbar}>
      <button className={styles.burger} onClick={() => setIsOpen(!isOpen)}>
        &#9776;
      </button>
      <div className={`${styles.navMenu} ${isOpen ? styles.open : ""}`}>
        <ul>
          <li><a href="Home">Home</a></li>
          <li><a href="Templates">Mes templates</a></li>

      {isConnected ? (
        <>
          <li><a href="Profile">Mon compte</a></li>
          <li><a onClick={logout}>Se déconnecter</a></li>
          {isAdmin && <li><a href="BO">Admin</a></li>}
        </>
      ) : (
        <>
          <li><a href="Login">Se connecter</a></li>
          {/* <li><a href="Register">S'inscrire</a></li> */}
        </>
      )}
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
