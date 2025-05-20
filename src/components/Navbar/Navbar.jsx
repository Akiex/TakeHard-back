import { useState } from "react";
import styles from "./Navbar.module.scss";
import { useAuth } from "../../context/AuthContext";
import { Link } from "react-router-dom";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { isConnected, isAdmin, logout, userId } = useAuth();
  return (
    <nav className={styles.navbar}>
      <button
        className={`${styles.burger} ${isOpen ? styles.open : ""}`}
        onClick={() => setIsOpen(!isOpen)}>
        <span aria-hidden="true"></span>
      </button>
      <div className={`${styles.navMenu} ${isOpen ? styles.open : ""}`}>
        <ul>
          <li><Link to="/home">Home</Link></li>
          {isConnected ? (
            <>
              {userId != null && (
                <li>
                  <Link to={`/AccountPage/${userId}`}>Mon compte</Link>
                </li>
              )}
              <li>
                <Link onClick={logout} to="/">Se déconnecter</Link>
              </li>
              {isAdmin && (
                <li><Link to="/bo">Admin</Link></li>
              )}
            </>
          ) : (
            <li><Link to="/login">Se connecter</Link></li>
          )}
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
