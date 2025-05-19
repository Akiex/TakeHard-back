import { useState } from "react";
import styles from "./Navbar.module.scss";
import { useAuth } from "../../context/AuthContext";
import { Link } from "react-router-dom";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { userId, isConnected, isAdmin, logout } = useAuth();
  
  return (
    <nav className={styles.navbar}>
      <button className={styles.burger} onClick={() => setIsOpen(!isOpen)}>
        &#9776;
      </button>
      <div className={`${styles.navMenu} ${isOpen ? styles.open : ""}`}>
        <ul>
          <li><Link to="/Home">Home</Link></li>
      {isConnected ? (
        <>
              <li>
                <Link to={`/AccountPage/${userId}`}>Mon compte</Link>
              </li>
          <li><Link onClick={logout} to="/">Se déconnecter</Link></li>
          {isAdmin && <li><Link to="/BO">Admin</Link></li>}
        </>
      ) : (
        <>
          <li><Link to="/Login">Se connecter</Link></li>
        </>
      )}
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;

